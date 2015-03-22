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
                $resultElement = array(
                    'objectId' => $result->getObjectId(),
                    'name' => $result->get('name'),
                    'date' => $result->get('date'),
                    'location' => $result->get('location'),
                    'owner_id' => $result->get('owner_id'),
                    'description' => $result->get('description'),
                );
            array_push($events, $resultElement);
        }
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


        return Response::json(array('error'=>false, 'user'=>"aajhj"), 200);
    }

    public function destroy($id){

        $query = new \Parse\ParseQuery('EventsObject');
        $object =  $query->get($id);
        $object->destroy();


        return Response::json(array('error'=>true, 'message'=>'Evento borrado'), 200);
    }

}