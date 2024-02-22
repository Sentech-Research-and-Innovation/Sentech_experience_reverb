<?php


namespace App\Models\System;


class SouthAfricanIDNumber
{

    public function validate($id)
    {

        $tempTotal = 0;
        $checkSum = 0;
        $multiplier = 1;
        $x = [];


        for ($i = 0; $i < 13; $i++) {
            $tempTotal = (int) substr($id, $i, 1) * $multiplier;
            array_push($x, $tempTotal);
            if ($tempTotal > 9) {
                $tempTotal = substr($tempTotal, 0, 1) + substr($tempTotal, 1, 1);
            }
            $checkSum = $checkSum + $tempTotal;
            $multiplier = ($multiplier % 2 === 0) ? 1 : 2;

        }

        if (($checkSum % 10) !== 0) {
            return 'Please enter a valid South African ID Number';
        } else {
            return true;
        }


    }
}
