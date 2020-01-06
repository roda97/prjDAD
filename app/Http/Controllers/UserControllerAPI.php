<?php

namespace App\Http\Controllers;

use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use Laravel\Passport\HasApiTokens;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use function GuzzleHttp\Promise\all;
use App\Http\Controllers\Controller;

use App\User;
use App\Wallet;
use App\StoreUserRequest;
use Hash;

class UserControllerAPI extends Controller
{
    public function index(Request $request)
    {
        $user= auth()->user()->id;
        if(count($request->except('page'))){
            
            $users = User::query();

            if ($request->filled('type')){
                $users->where('type','=', $request->type);
            }
            
            if ($request->filled('name')){
                $users->where('name', 'like', '%' .$request->name . '%');
            }
        
            if ($request->filled('email')){
                $users->where('email', 'like', $request->email . '%');
            }

            if ($request->filled('active')){
                $users->where('active','=', $request->active);
            }
        
            $users = UserResource::collection($users->paginate(10));
        
        }else{
            
            $users = UserResource::collection(User::with('wallet')->select('*')->paginate(10));
        }

        return $users;
    }
    
    public function show(User $user)
    {
        return new UserResource($User);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:3',
            'nif'       => 'integer|digits:9'
        ]);
      
        if($request->photo['base64']) {
                $photo = $request->photo;
                $base64_string = explode(',', $photo['base64']);
                $imageBin = base64_decode($base64_string[1]);
                if (!Storage::disk('public')->exists('fotos/' . $photo['name'])) {
                    Storage::disk('public')->put('fotos/' . $photo['name'], $imageBin);
                }
        }
          

        $user = new User();
        $user->fill($request->all());
        $user->password = Hash::make($user->password);
        $user->photo = $request->photo['base64'] ? $request->photo['name'] : null;
        $user->save();
        $wallet = new Wallet();
        $wallet->id = $user->id;
        $wallet->balance = 0;
        $wallet->email = $user->email;
        $wallet->save();
        return response()->json(new UserResource($user), 201);
    }

    public function storeOperatorAdmin(Request $request) {
        $request->validate([
            'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:3',
            'type' => 'required|in:a,o,u'
        ]);

        $photo = $request->photo;
        $base64_string = explode(',', $photo['base64']);
        $imageBin = base64_decode($base64_string[1]);

        if (!Storage::disk('public')->exists('fotos/' . $photo['name'])) {
            Storage::disk('public')->put('fotos/' . $photo['name'], $imageBin);
        }    

        $user = new User();
        $user->fill($request->all());
        $user->photo = $request->photo['base64'] ? $request->photo['name'] : null;
        $user->password = Hash::make($user->password);
        $user->save();

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|min:3|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
                'email' => 'required|email|unique:users,email,'.$id,
            ]);
        $user = User::findOrFail($id);
        $user->update($request->all());
        return new UserResource($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }

    public function activateUser($id){
        $user = User::findOrFail($id);
        $active = DB::table('users')->select('active')->where('id', $id)->get();
        if($active[0]->active == 0){
            $user->active = 1;
            $user->save();
        }else{
            $user = User::findOrFail($id);
            $user->active = 0;
            $user->save();
            return new UserResource($user);
        }
        return new UserResource($user);
    }

    public function profileRefresh(Request $request)
    {
        return new UserResource($request->user());
    }

    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function updateProfilewithPass(Request $request){
        $id = $request->userId;
        $user = User::findOrFail($id);  
        if($user->type == "u"){
            $request->validate([
                'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
                'nif'       => 'integer|digits:9',   
                'password' => 'min:3',
               // 'photo' => 'regex:/png$/',
            ]);    
        }else{
            $request->validate([
                'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/', 
                'password' => 'min:3', 
                //'photo'     => 'required|regex:/^.+/.((jpg)|(gif)|(png))+$/'
            ]);
        $user->name = $request->name;
        
            $user->nif = $request->nif;

            if (!Storage::disk('public')->exists('fotos/' . $request->photo)) {
                $photo = $request->photo;
                $photoB64 = $request->base64;
                $base64_string = explode(',', $photoB64);
                $imageBin = base64_decode($base64_string[1]);
                Storage::disk('public')->delete('fotos/' . $user->photo);
                Storage::disk('public')->put('fotos/' . $request->photo, $imageBin);
            }
            $user->photo = $request->photo; 


            if (Hash::check($request->oldpassword, $user->password)) {
              //  console.log("Password Igual");
                $user->password = Hash::make($request->password);
                $user->save(); 
                return new UserResource($user);
            }
            //console.log("Password Diffe");
            return response()->json('Old Password is incorrect !', 402);    
    }
}

    public function updateProfilewithoutPass(Request $request){
        $id = $request->userId;
        $user = User::findOrFail($id);   
        if($user->type == "u"){
            $request->validate([
                'name'      => 'required|regex:/^[a-zA-Zà-Ú]+$/',
                'nif'       => 'integer|digits:9',   
                'photo'     => 'required|regex:/^.+\.((jpg)|(gif)|(png))$/' 
            ]);   
        }else{
            //return response()->json($request->photo,402);
            $request->validate([
                'name'      => 'required|regex:/^[a-zA-Zà-Ú ]+$/',
                //'photo'     => 'required|regex:/(\d)+.(?:jpg|png|gif)+$/',
                //'photo'     => 'required|regex:/^[(jpg)|(gif)|(png)]+$/'
            ]);
        }
       
        $user->name = $request->name;
        
        if (!Storage::disk('public')->exists('fotos/' . $request->photo)) {
            $photo = $request->photo;
            $photoB64 = $request->base64;
            $base64_string = explode(',', $photoB64);
            $imageBin = base64_decode($base64_string[1]);
            Storage::disk('public')->delete('fotos/' . $user->photo);
            Storage::disk('public')->put('fotos/' . $request->photo, $imageBin);
        }
        $user->photo = $request->photo; 
        $user->nif = $request->nif;
        $user->save();
        //return response()->json($user->name,402);
        return new UserResource($user);
    }

}