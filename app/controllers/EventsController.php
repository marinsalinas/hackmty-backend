<?php

class EventsController extends BaseController{

    function __construct() {
        //Con esto le digo que no le aplique el filtro a la funcion store.
        //$this->beforeFilter('auth.token', array('except' => array('store')));
        // ...
    }

    public function index(){
        return Response::json(array('error' => false, 'user'=> "USER"), 200);
    }

    public function store(){
        $validacion = Validator::make(Input::all(), [

            'name' => 'required',
            'date' => 'required',
            'location' => 'required',
            'host' => 'required',
        ]);

        if ($validacion->fails()) {

            return Response::json(array('error'=>true, 'messages' => $validacion->messages()), 400);
        }

        return Response::json(array('error'=>false, 'event'=>Input::all()), 200);
    }

    public function update($id){
        $user = Auth::user()->user();

        if($user->id != $id){
            return Response::json(array('error'=>true, 'message'=> 'Operacion No permitida'), 400);
        }

        $validador = Validator::make(Input::all(),[
            'username' => 'required|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|unique:users,phone,'.$id,
            'fullname' => 'required',
            'gender' => 'required',
            'birthday'=>'required|date',
        ]);

        if($validador->fails()) {
            return Response::json(array('error' => true, 'messages' => $validador->messages()), 400);
        }

        $user->username = Input::get('username');
        $user->email = Input::get('email');
        $user->fullname = Input::get('fullname');
        $user->phone = Input::get('phone');
        $user->gender = Input::get('gender');
        $user->birthday = Input::get('birthday');
        $user->save();

        return Response::json(array('error'=>false, 'user'=>$user), 200);
    }

    public function destroy($id){
        $user = User::find($id);

        $user->delete();
        return Response::json(array('error'=>true, 'message'=>'Usuario borrado'), 200);
    }

    public function password($id){
        $user = Auth::user()->user();

        if($user->id != $id){
            return Response::json(array('error'=>true, 'message'=> 'Operacion No permitida'), 400);
        }


        $validador = Validator::make(Input::all(),[
            'password' => 'required'
        ]);

        if($validador->fails()) {
            return Response::json(array('error' => true, 'message'=>'No puede ir el campo vacío'), 400);
        }

        $user->password = Hash::make(Input::get('password'));
        $user->push();

        return Response::json(array("error"=>false, "message"=>"Contraseña Actualizada para: ".$user->username), 200);
    }

}