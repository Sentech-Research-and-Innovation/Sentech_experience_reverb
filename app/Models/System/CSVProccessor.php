<?php


namespace App\Models\System;


class CSVProccessor
{

    public  function process($file){
        $file_to_read = fopen($file, 'r');
        $data =[];
        while (!feof($file_to_read)) {
            $data[] = fgetcsv($file_to_read, 1000, ';');
        }
        return $data;
    }
}
