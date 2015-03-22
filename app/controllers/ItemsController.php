<?php

class ItemsController extends BaseController{

    function __construct() {
        //Con esto le digo que no le aplique el filtro a la funcion store.
        //$this->beforeFilter('auth.token', array('except' => array('store')));
        // ...
        \Parse\ParseClient::initialize( "C5WL7pQmVGOnQ8Ln3WF4XWEauewoYXMpaqjLF16W", "qSXH19D28DbAYRakmfl9lhOfxdcf5wFBciRMkWH2", "ggzBT8OCaVJdFvOrYLz4Iy7b3UGJCgiVjksWWfpq" );
    }

    public function index(){

        $query = new \Parse\ParseQuery('ItemsObject');



        $results =  $query->find();

        $events = array();
        foreach ($results as $result) {
            $events[$result->getObjectId()] = array(
                'name' => $result->get('name'),
                'date' => $result->get('description'),
                'location' => $result->get('price'),
                'owner_id' => $result->get('event_id'),
                'description' => $result->get('market'),
            );
        }
        return Response::json(array('error' => false, 'events'=> $events), 200);

    }

    public function store(){
        $validacion = Validator::make(Input::all(), [

            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'event_id' => 'required',
            'market' => 'required'
        ]);

        if ($validacion->fails()) {



            return Response::json(array('error'=>true, 'messages' => $validacion->messages()), 400);
        }


        $object = \Parse\ParseObject::create("ItemsObject");
        $objectId = $object->getObjectId();
        //$php = $object->get("elephant");

        // Set values:
        $object->set("name", Input::get('name'));
        $object->set("description", Input::get('date'));
        $object->set('price', Input::get('location'));
        $object->set('event_id', Input::get('event_id'));
        $object->set('market', Input::get('market'));
        // Save:
        $object->save();


        return Response::json(array('error'=>false, 'event'=>Input::all()), 200);
    }

    public function update($id){


        return Response::json(array('error'=>false, 'user'=>"aajhj"), 200);
    }

    public function destroy($id){

        $query = new \Parse\ParseQuery('ItemsObject');
        $object =  $query->get($id);
        $object->destroy();


        return Response::json(array('error'=>false, 'message'=>'Evento borrado'), 200);
    }

}