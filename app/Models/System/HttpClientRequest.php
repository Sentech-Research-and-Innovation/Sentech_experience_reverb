<?php


namespace App\Models\System;


use GuzzleHttp\Client;

class HttpClientRequest
{
//
//    public static function post()
//    {
//        $client = new Client(['base_uri' => $this->uri]);
//        $response = $client->request('POST', $this->uri, [
//            'body' => $this->postData,
//            'headers' => [
//                $this->key => $this->value,
//                'Content-Type' => 'application/json'
//            ]
//        ]);
//        $response = json_decode($response->getBody()->getContents());
//    }

    public static function get($data)
    {


        $client = new Client(['base_uri' => $data['uri']]);
        $response = $client->request('GET',
            $data['uri'],
            [
                'auth' => [
                    $data['usr'], $data['password']
                ]
            ]
        );

        $response = json_decode($response->getBody()->getContents());

        return $response;
    }


}
