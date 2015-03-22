<?php

class EventsController extends BaseController{

    function __construct() {
        //Con esto le digo que no le aplique el filtro a la funcion store.
        //$this->beforeFilter('auth.token', array('except' => array('store')));
        // ...
        \Parse\ParseClient::initialize( "C5WL7pQmVGOnQ8Ln3WF4XWEauewoYXMpaqjLF16W", "qSXH19D28DbAYRakmfl9lhOfxdcf5wFBciRMkWH2", "ggzBT8OCaVJdFvOrYLz4Iy7b3UGJCgiVjksWWfpq" );
    }

    public function index(){

        $query = new \Parse\ParseQuery('EventsObject');



        $results =  $query->find();

        $events = array();
        foreach ($results as $result) {
                $events[$result->getObjectId()] = array(
                    'name' => $result->get('name'),
                    'date' => $result->get('date'),
                    'location' => $result->get('location'),
                    'owner_id' => $result->get('owner_id'),
                    'description' => $result->get('description'),
                );
        }
        print_r($events);
        die();
        return Response::json(array('error' => false, 'events'=> $events), 200);

    }

    public function store(){
        $validacion = Validator::make(Input::all(), [

            'name' => 'required',
            'date' => 'required',
            'location' => 'required',
            'owner_id' => 'required',
            'description' => 'required'
        ]);

        if ($validacion->fails()) {



            return Response::json(array('error'=>true, 'messages' => $validacion->messages()), 400);
        }


        $object = \Parse\ParseObject::create("EventsObject");
        $objectId = $object->getObjectId();
        //$php = $object->get("elephant");

        // Set values:
        $object->set("name", Input::get('name'));
        $object->set("date", Input::get('date'));
        $object->set('location', Input::get('location'));
        $object->set('owner_id', Input::get('owner_user'));
        $object->set('description', Input::get('host'));
        // Save:
        $object->save();


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