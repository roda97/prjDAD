<?php

namespace App\Http\Controllers;

use App\Movement;
use Illuminate\Http\Request;

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
        //return MovementResource::collection(Movement::orderby('date'));
        return MovementResource::collection(Movement::orderby('date')->paginate(10));

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
}

