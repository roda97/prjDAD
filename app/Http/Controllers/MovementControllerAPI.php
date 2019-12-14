<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Wallet;
use App\Movement;

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

            $movements = MovementResource::collection($movements->orderby('date','desc')->paginate(10));//->appends($request->except('page'));
        }
        else{    
            $movements = MovementResource::collection(Movement::orderby('date','desc')->paginate(10));
        }

        return $movements;
        //return MovementResource::collection(Movement::orderby('date','desc')->paginate(10));

        /*if ($request->has('page')) {
            return MovementResource::collection(Movement::paginate(10));
        } else {
            return MovementResource::collection(Movement::all());
        }*/
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
        /*$request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email,'.$id,
                //'age' => 'integer|between:18,75'
            ]);*/
        $movement = Movement::findOrFail($id);
        $movement->update($request->all());
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

    public function addCredit(Request $request) {

        if($request->type_payment == 'bt'){
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|between:0.01,5000',
                'type_payment' => 'required|in:c,bt,mb',
                'iban' => 'required|regex:^[A-Z]{2}\d{23}$^',
                'source_description' => 'required',
            ]);
        }else{
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|between:0,5000',
                'type_payment' => 'required|in:c,bt,mb',
            ]);
        };     

        $walletId = DB::table('wallets')->select('id')->where('email', $request->email)->get(); 
        if($walletId->isEmpty()){
            return response()->json(("Email doesn't exist!"), 402);
        }

        $balance = DB::table('wallets')->select('balance')->where('email', $request->email)->get(); 

        //comandos para alterar a balance da wallet de destino:
        $wallet = Wallet::findOrFail($walletId[0]->id);
        $wallet->balance = $balance[0]->balance + $request->value;
        $wallet->save();

        //data
        $date = Carbon::now();
       
        $movement = new Movement();
        $movement->fill($request->all());
        $movement->wallet_id = $walletId[0]->id;
        $movement->type = "i";
        $movement->start_balance = $balance[0]->balance;
        $movement->end_balance = $balance[0]->balance + $request->value;
        $movement->transfer = 0;
        $movement->date = $date->toDateTimeString();
        $movement->save();

        return new MovementResource($movement);
    }

    public function addDebit(Request $request) {

        /*if($request->type_payment == 'bt'){
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|between:0.01,5000',
                'type_payment' => 'required|in:c,bt,mb',
                'iban' => 'required|regex:^[A-Z]{2}\d{23}$^',
                'source_description' => 'required',
            ]);
        }else{
            $request->validate([
                'email' => 'required|email',
                'value' => 'required|between:0,5000',
                'type_payment' => 'required|in:c,bt,mb',
            ]);
        };     

        $walletId = DB::table('wallets')->select('id')->where('email', $request->email)->get(); 
        if($walletId->isEmpty()){
            return response("Email isn't valid!");
        }

        $balance = DB::table('wallets')->select('balance')->where('email', $request->email)->get(); */

        //comandos para alterar a balance da wallet de destino:
        $wallet = Wallet::findOrFail($walletId[0]->id);
        $wallet->balance = $balance[0]->balance + $request->value;
        $wallet->save();

        //data
        $date = Carbon::now();
       
        $movement = new Movement();
        $movement->fill($request->all());
        $movement->wallet_id = $walletId[0]->id;
        $movement->type = "e";
        $movement->start_balance = $balance[0]->balance;
        $movement->end_balance = $balance[0]->balance - $request->value; //VERIFICAR QUAL A CONDIÇÃO AQUI
        //$movement->transfer = 0;
        $movement->date = $date->toDateTimeString();
        $movement->save();

        return new MovementResource($movement);
    }
}

