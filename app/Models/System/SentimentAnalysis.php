<?php


namespace App\Models\System;


class SentimentAnalysis
{

    private $uri;
    private $usr;
    private $password;
    private $content_type;
    public function __construct(){
        $this->uri ='http://13.244.120.32:9200/twitter/';
        $this->content_type ='application/json';
        $this->usr ='test';
        $this->password ='test123';
    }
//13.244.120.32
    public  function analysis($request){

        $response =[];
        $data =[
            'uri'=>$this->uri.'_search?size='.$request['size'],
            'content_type'=>'',
            'usr'=>$this->usr,
            'password'=>$this->password,
        ];
        $analysis = (array)HttpClientRequest::get($data);

        $hits = (array)$analysis['hits'];

        if(isset($hits['hits'])){
            $hits = $hits['hits'];
        }
        if(count($hits)){
            foreach ($hits as $key => $value){
                $response[] = $value->_source;
//                if(count((array)$value->_source)){
//                    foreach ((array)$value->_source as $i => $item){
//
//                    }
//                }

            }
        }
     return $response;
//        return
    }
}
