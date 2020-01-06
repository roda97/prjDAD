<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Wallet;
use App\Movement;
use Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Movement as MovementResource;

class MovementControllerAPI extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user= auth()->user()->id;
        if(count($request->except('page'))){
            $movements = Movement::query();
      
            if ($request->filled('id')){
                $movements->where('id','=', $request->id);
            }

            if ($request->filled('type')){
                $movements->where('type','=', $request->type);
            }

            if ($request->filled('category_name')){
                $category = DB::table('categories')->where('name', 'like' , '%'.$request->category_name.'%')->pluck('id');
                //$category = DB::table('categories')->where('name', 'like' , '%'.$request->category_name.'%')->pluck('id'); */
                //$category = DB::table('categories')->select('id')->where('name', $request->category_name)->get();
                if($category->isEmpty()){
                    return response()->json("Category doesn't exist!",402);
                }
                $movements->whereIn('category_id', $category);
            }

            if ($request->filled('type_payment')){
                $movements->where('type_payment','=', $request->type_payment);
            }

            if ($request->filled('email')){
                $email = DB::table('wallets')->select('id')->where('email', $request->email)->get();
                if($email->isEmpty() ){
                    return response()->json("Email doesn't exist!",402); 
                }
                $movements->where('transfer_wallet_id','=', $email[0]->id);
            }

            if ($request->filled('data_inf')){
                $movements->where('date','>=',$request->data_inf);
            }

            if ($request->filled('data_sup')){
                $movements->where('date','<=',$request->data_sup);
            }
            //$movements = MovementResource::collection($movements->orderby('date','desc')->paginate(10));
            $movements = MovementResource::collection($movements->orderby('date','desc')->where(function($q) use($user){ //tenho que usar este "use($user)" para a funçao ter conhecimento do valor da mesma
                $q->where('wallet_id', $user);
                //->orWhere('transfer_wallet_id', $user);
                })->paginate(10)); //este where function é o que me permite juntar as condiçoes do search e as condições daquilo a que o utilizador pode ver
        }
        else{    
            //$movements = MovementResource::collection(Movement::orderby('date','desc')->where('wallet_id', $user)->orWhere('transfer_wallet_id', $user)->paginate(10));
            $movements = MovementResource::collection(Movement::orderby('date','desc')->where('wallet_id', $user)->paginate(10));
        }

        return $movements;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*$request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email',
                //'age' => 'integer|between:18,75',
                'password' => 'min:3'
            ]);*/
        $movement = new movement();
        $movement->fill($request->all());
        $movement->save();
        return response()->json(new MovementResource($movement), 201);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show(Movement $movement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function edit(Movement $movement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $movement = Movement::findOrFail($id);
        $movement->update($request->all());
        $category = DB::table('categories')->select('id')->where('name', $request->category_name)->where('type', $movement->type)->get();
        if($category->isEmpty()){
            return response()->json("This type of movement can't have that category!", 402);
        }
        //return response()->json($movement->type,402);
        $movement->category_id = $category[0]->id;
        $movement->description = $request->description;
        $movement->save();
        return new MovementResource($movement);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movement $movement)
    {
        //
    }

    public function addCredit(Request $request, Movement $movement) {

        if($request->type_payment == 'bt'){
            $request->validate([
                'emailIncome' => 'required|email',
                'value' => 'required|numeric|between:0.01,5000.00',
                'type_payment' => 'required|in:c,bt,mb',
                'iban' => 'required|regex:^[A-Z]{2}\d{23}$^',
                'source_description' => 'required',
            ]);
        }else{
            $request->validate([
                'emailIncome' => 'required|email',
                'value' => 'required|numeric|between:0.01,5000.00',
                'type_payment' => 'required|in:c,bt,mb',
            ]);
        };     

        $walletId = DB::table('wallets')->select('id')->where('email', $request->emailIncome)->get(); 
        if($walletId->isEmpty()){
            return response()->json(("Email doesn't exist!"), 402);
        }

        $balance = DB::table('wallets')->select('balance')->where('email', $request->emailIncome)->get(); 

        //data
        $date = Carbon::now();
       
        $movement = new Movement();
        $movement->fill($request->all());
        $movement->wallet_id = $walletId[0]->id;
        //$movement->email = $request->email;
        $movement->type = "i";
        $movement->start_balance = $balance[0]->balance;
        $movement->end_balance = $balance[0]->balance + $request->value;
        $movement->transfer = 0;
        $movement->date = $date->toDateTimeString();
        $movement->save();

        //comandos para alterar a balance da wallet de destino:
        $wallet = Wallet::findOrFail($walletId[0]->id);
        $wallet->balance = $balance[0]->balance + $request->value;
        $wallet->save();

        return new MovementResource($movement);
    }

    public function addDebit(Request $request) {

        $request->validate([
            'transfer' => 'required|in:0,1',
            //'email' => 'required|email',
            'value' => 'required|numeric|between:0.01,5000.00',
            //'type_payment' => 'required|in:bt,mb',
            'category_name' => 'required',
            'description' => 'required'
        ]); 
        
        if($request->transfer == '0'){
            $request->validate([
                'type_payment' => 'required|in:bt,mb'
            ]);
        }
        if($request->transfer == '1'){
            $request->validate([
                'email' => 'required|email',
                'source_description' => 'required'
            ]);
        }
        if($request->type_payment == 'bt'){
            $request->validate([
                //'email' => 'required|email',
                //'value' => 'required|numeric|between:0.01,5000.00',
                //'type_payment' => 'required|in:bt,mb',
                'iban' => 'required|regex:^[A-Z]{2}\d{23}$^',
                //'source_description' => 'required',
            ]);
        }
        if($request->type_payment == 'mb'){
            $request->validate([
                'mb_entity_code' => 'required|digits:5',
                'mb_payment_reference' => 'required|digits:9'
            ]);
        }

        $email = auth()->user()->email;
        $walletId = DB::table('wallets')->select('id')->where('email', $email)->get();  
        //return response()->json(($walletEmail), 402);
        //return response()->json(($email), 402);
        if($walletId->isEmpty()){
            //return response()->json(($email), 402);
            return response()->json(("Email doesn't exist!"), 402);
        }

        if($request->filled('email')){
            $walletId2 = DB::table('wallets')->select('id')->where('email', $request->email)->get();
            $walletEmail = DB::table('wallets')->where('email', $request->email)->get();
            //return response()->json(($walletEmail[0]->email), 402);
            //return response()->json(($walletId2), 402); 
            if($walletId2->isEmpty()){
                return response()->json(("Email doesn't exist!"), 402);
            }
        }else{
            $walletId2 = DB::table('wallets')->get();
        }

        if ($request->filled('category_name')){
            $category = DB::table('categories')->select('id')->where('name', $request->category_name)->get();
        }

        $balance = DB::table('wallets')->select('balance')->where('email', $email)->get(); 
 
        //data
        $date = Carbon::now();
       
        $movement = new Movement();
        $movement->fill($request->all());
        if($request->filled('email')){
            if($walletEmail[0]->email != $email){
                $movement->wallet_id = $walletId[0]->id;
            }
            else{
                return response()->json(("You can't transfer for your own wallet!"), 402);
            }
        }else{
            $movement->wallet_id = $walletId[0]->id;
        }
        $movement->type = "e";
        if($request->value <= $balance[0]->balance){
            $movement->start_balance = $balance[0]->balance;
            $movement->end_balance = $balance[0]->balance - $request->value; 
        }
        else{
            return response()->json(("You don't have enought money!"), 402);
        }

        $movement->date = $date->toDateTimeString();
        if($request->transfer == 1){
            $movement->transfer = $request->transfer;
            $movement->transfer_wallet_id = $walletId2[0]->id;
            $movement->category_id = $category[0]->id;
        }else{
            $movement->transfer = $request->transfer;
        }
        $movement->save();
        //return response()->json($movement->id, 402);

        //comandos para alterar a balance da wallet de destino:
        $wallet = Wallet::findOrFail($walletId[0]->id);
        $wallet->balance = $balance[0]->balance - $request->value;
        $wallet->save();

        //////////////CODIGO PARA FAZER O INCOME AUTOMATICAMENTE//////////////         
        if($request->filled('email')){

            $balance2 = DB::table('wallets')->select('balance')->where('email', $request->email)->get(); 
            //return response()->json(($balance2), 402); 

            //$movementI = movement Income
            $movementI = new Movement();
            $movementI->fill($request->all());
            $movementI->wallet_id = $walletId2[0]->id;
            $movementI->type = "i";
            $movementI->start_balance = $balance2[0]->balance; 
            $movementI->end_balance = $balance2[0]->balance + $request->value;
            $movementI->date = $date->toDateTimeString();
            $movementI->transfer = $request->transfer; 
            $movementI->transfer_movement_id = $movement->id;
            $movementI->description = null;
            //return response()->json($movement->id, 402);
            $movementI->transfer_wallet_id = $walletId[0]->id;
            $movementI->save();

            //atualizar o transfer_movement_id do movimento expense(debito) com o id do movimento income (credito)
            $movement->transfer_movement_id = $movementI->id;
            $movement->save();

            //comandos para alterar a balance da wallet de destino:
            $wallet = Wallet::findOrFail($walletId2[0]->id);
            $wallet->balance = $balance2[0]->balance + $request->value;

            $wallet->save();
        }
        //return $this->addCredit($wallet);

        return new MovementResource($movement);
    }

    public function send_reactivate_email($emailIncome){
        
        //return response()->json(($emailIncome), 402);
        $to_name = 'Notification Email';
        
        //$to_email = 'luis25mateus@gmail.com';
        $to_email = $emailIncome;
        //$to_email = "contaahabbo@gmail.com";
        
        $data = array( "body" => "You received an movement in your virtual wallet!");
        
        Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
            ->subject('Notification Email');
            $message->from('dadproject24@gmail.com','Notification Email');
        });
        
    }

    public function getBalance($id)
    {
        $balance = DB::table('wallets')->select('balance')->where('id', $id)->get();
        return $balance[0]->balance;
    }

    //////////////////////ESTATÍSTICAS - US14//////////////////////

    public function getTotalsMovements($id){
        $incomes = MovementResource::collection(Movement::select('value')->where('wallet_id', $id)->where('type','i')->get());
        $expenses = MovementResource::collection(Movement::select('value')->where('wallet_id', $id)->where('type','e')->get());

        $totalIncome = 0;
        $totalExpense= 0;

        for($i=0; $i < sizeof($incomes); $i++){
            $totalIncome += $incomes[$i]->value; 
        }

        for($i=0; $i < sizeof($expenses); $i++){
            $totalExpense += $expenses[$i]->value; 
        }

        $totals[0] = number_format((float)$totalIncome, 2, '.', '');
        $totals[1] = number_format((float)$totalExpense, 2, '.', '');
        
        return $totals;
    }

    public function getTypePayment($id){
        $cash = MovementResource::collection(Movement::where('wallet_id', $id)->where('type_payment','c')->get());
        //mb -> MB Payment
        $mb = MovementResource::collection(Movement::where('wallet_id', $id)->where('type_payment','mb')->get());
        //bt -> brank transfer
        $bt = MovementResource::collection(Movement::where('wallet_id', $id)->where('type_payment','bt')->get());
        //outros -> quando é null (é porque é uma transferencia e portanto um transfer email)
        $outros = MovementResource::collection(Movement::where('wallet_id', $id)->where('type_payment', null)->get());

        $totalCash = sizeof($cash);
        $totalMB = sizeof($mb);
        $totalBt = sizeof($bt);
        $totalOutros = sizeof($outros);

        $totals[0] = $totalCash;
        $totals[1] = $totalMB;
        $totals[2] = $totalBt;
        $totals[3] = $totalOutros;
        
        return $totals;
    }

    public function getCategoryIncome($id){
        $categories = DB::table('categories')->select('name', 'id')->where('type','i')->get();

        for ($i=0; $i < sizeof($categories); $i++){
            $totalIncomes[$i] = MovementResource::collection(Movement::where('wallet_id', $id)->where('category_id', $categories[$i]->id)->where('type', 'i')->get());
            $countIncomes = sizeof($totalIncomes[$i]);
            $totalCategories[$i]['category'] = $categories[$i]->name;
            $totalCategories[$i]['total'] = $countIncomes;
        }

        for ($i=0; $i < sizeof($totalCategories); $i++){
            $labels[] = $totalCategories[$i]['category'];
            $rows[] = $totalCategories[$i]['total'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows
        ];

        return $data;
    }

    public function getCategoryExpense($id){
        $categories = DB::table('categories')->select('name', 'id')->where('type','e')->get();

        for ($i=0; $i < sizeof($categories); $i++){
            $totalExpenses[$i] = MovementResource::collection(Movement::where('wallet_id', $id)->where('category_id', $categories[$i]->id)->where('type', 'e')->get());
            $countExpenses = sizeof($totalExpenses[$i]);
            $totalCategories[$i]['category'] = $categories[$i]->name;
            $totalCategories[$i]['total'] = $countExpenses;
        }

        for ($i=0; $i < sizeof($totalCategories); $i++){
            $labels[] = $totalCategories[$i]['category'];
            $rows[] = $totalCategories[$i]['total'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows
        ];

        return $data;

    }

    public function getCategoryIncomeMoney($id){
        $categories = DB::table('categories')->select('name', 'id')->where('type','i')->get();

        for ($i=0; $i < sizeof($categories); $i++){
            $totalIncomes[$i] = MovementResource::collection(Movement::select('value')->where('wallet_id', $id)->where('category_id', $categories[$i]->id)->where('type', 'i')->get());
            $countIncomesMoney = 0;

            for($j=1; $j < count($totalIncomes[$i]); $j++){
                $countIncomesMoney += $totalIncomes[$i][0]->value;
            }

            $totalCategories[$i]['category'] = $categories[$i]->name;
            $totalCategories[$i]['total'] = number_format((float)$countIncomesMoney, 2, '.', '');
        }

        for ($i=0; $i < sizeof($totalCategories); $i++){
            $labels[] = $totalCategories[$i]['category'];
            $rows[] = $totalCategories[$i]['total'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows
        ];

        return $data;
    }

    public function getCategoryExpenseMoney($id){
        $categories = DB::table('categories')->select('name', 'id')->where('type','e')->get();

        for ($i=0; $i < sizeof($categories); $i++){
            $totalExpenses[$i] = MovementResource::collection(Movement::select('value')->where('wallet_id', $id)->where('category_id', $categories[$i]->id)->where('type', 'e')->get());
            $countExpensesMoney = 0;

            for($j=1; $j < count($totalExpenses[$i]); $j++){
                $countExpensesMoney += $totalExpenses[$i][0]->value;
            }

            $totalCategories[$i]['category'] = $categories[$i]->name;
            $totalCategories[$i]['total'] = number_format((float)$countExpensesMoney, 2, '.', '');
        }

        for ($i=0; $i < sizeof($totalCategories); $i++){
            $labels[] = $totalCategories[$i]['category'];
            $rows[] = $totalCategories[$i]['total'];
        }
        $data = [
            'labels' => $labels,
            'rows' => $rows
        ];

        return $data;

    }

}





