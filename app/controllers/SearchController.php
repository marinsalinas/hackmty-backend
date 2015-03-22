<?php

class SearchController extends BaseController{

    function __construct() {
        //Con esto le digo que no le aplique el filtro a la funcion store.
        //$this->beforeFilter('auth.token', array('except' => array('store')));
        // ...
        \Parse\ParseClient::initialize( "C5WL7pQmVGOnQ8Ln3WF4XWEauewoYXMpaqjLF16W", "qSXH19D28DbAYRakmfl9lhOfxdcf5wFBciRMkWH2", "ggzBT8OCaVJdFvOrYLz4Iy7b3UGJCgiVjksWWfpq" );
    }

    public function index($query){


       $client = new \GuzzleHttp\Client();

        $response = $client->get("http://api.walmartlabs.com/v1/search?apiKey=rtg9az72zb6k3q5gw4ygjxma&query={$query}&sort=price&ord=asc");

        $json = $response->json();
        print_r($json);
        die();

        //return Response::json(array('error' => false, 'events'=> $response->json()->items), 200);

    }


    public function show($queryS){


        $client = new \GuzzleHttp\Client();

        $response = $client->get("http://api.walmartlabs.com/v1/search?apiKey=rtg9az72zb6k3q5gw4ygjxma&query={$queryS}&sort=price&ord=asc");

        $items = array();

        foreach ($response->json()["items"] as $item) {

            $itemElement = array(
                'itemId'=>$item["itemId"],
                'price' =>$item["salePrice"],
                'category' => $item["categoryPath"],
                'image' => $item['thumbnailImage'],
                'name' => $item["name"],
                'description' =>$item["longDescription"],
                'market' => "walmart"


            );

            array_push($items, $itemElement);
        }



        $query = new \Parse\ParseQuery('TargetItems');



        $results =  $query->find();

        foreach ($results as $result) {

            if(preg_match("/{$queryS}/", strtolower($result->get('name')))) {

                $itemElement = array(
                    'itemId' => $result->get('itemId'),
                    'name' => $result->get('name'),
                    'category' => $result->get('category'),
                    'image' => $result->get('image'),
                    'price' => $result->get('price'),
                    'market' => $result->get('market'),
                    'description'=> ""
                );
                array_push($items, $itemElement);
            }
        }


        return Response::json(array('error' => false, 'items'=> $items), 200);

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