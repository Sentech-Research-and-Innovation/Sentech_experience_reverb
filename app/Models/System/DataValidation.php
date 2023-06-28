<?php


namespace App\Models\System;


use App\Models\User;
use App\Models\Web\PersonalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class DataValidation
{

    protected $errorBag;

    public function __construct()
    {
        $this->errorBag = [];
    }

    public static function age($dob)
    {

        $response = false;
        $age = Carbon::parse($dob)->age;
        if ($age < 18 || $age > 60) {
            $ageText = '';
            if ($age < 18) {
                $ageText = "18 or older";
            }
            if ($age > 60) {
                $ageText = '60 or younger';
            }
            $response = 'Sorry you do not qualify for a loan, you must be ' . $ageText . ' to register';
        }
        return $response;
    }

    public static function ageQualifying($dob)
    {

        $response = false;
        $age = Carbon::parse($dob)->age;
        if ($age < 21 || $age > 60) {
            $ageText = '';
            if ($age < 21) {
                $ageText = "21 or older";
            }
            if ($age > 60) {
                $ageText = '60 or younger';
            }
            $response = 'Sorry you do not qualify for a loan, you must be ' . $ageText . ' to apply';
        }
        return $response;
    }

    public static function idNumber($email)
    {
        $response = false;
        $validateIDNumber = User::where('email', $email)->first();
        if (!is_null($validateIDNumber)) {
            $response = 'ID Number is already taken, if this is your account please contact customer support for further assistance';
        }
        return $response;
    }

    public static function mobileNumberExists($number)
    {
        $response = false;
        $validateMobileNumber = PersonalDetail::where('mobile_number', $number)->first();
        if (!is_null($validateMobileNumber)) {
            $response = 'This mobile number is already taken, if this is your account please contact customer support for further assistance';
        }
        return $response;
    }

    public static function mobileNumberExistsLoggedIn($number)
    {
        $response = false;
        $validateMobileNumber = PersonalDetail::where('mobile_number', $number)->first();
        $number = (new DataValidation)->numberFormat($number, 10);
        if ($number !== false) {
            if (!is_null($validateMobileNumber)) {
                if ($validateMobileNumber->user_id !== Auth::user()->id) {
                    $response = 'This mobile number is already taken.';
                }
            }
        }
        return $response;
    }

    public static function mobileNumber($number, $country)
    {
        $response = false;

        $number = (new DataValidation)->numberFormat($number, 10);
        if ($number !== false) {
            if ($country === 'KE' || $country === 'ZA') {
                if (strlen((string)$number) > 9 || $number == '' || strlen((string)$number) !== 9) {
                    $response = 'Please enter a valid South African mobile number (10 digits)';
                }
            }
        } else {
            $response = 'Please enter a valid South African mobile number (10 digits)';
        }


        return $response;
    }

    public static function idNumberLength($id, $country)
    {
        $response = false;
        if ($country === 'KE' || $country === 'ZA') {
            if (strlen((string)$id) > 8 || $id == '' || strlen((string)$id) !== 8) {
                $response = 'Please enter a valid 8 digit ID number';
            }
        }
        return $response;
    }

    public function required($array)
    {
        if (count($array)) {
            foreach ($array as $key => $string) {
                if (is_null($string) || $string == "") {
                    $this->errorBag[$key] = 'The ' . str_replace('_', ' ', ucfirst($key)) . ' field is required';
                    if ($key === 'addr_complex_unit') {
                        $this->errorBag[$key] = 'The Unit number/complex  field is required';
                    }
                }
                if ($key === 'user_email') {
                    if (!filter_var($string, FILTER_VALIDATE_EMAIL)) {
                        $this->errorBag[$key] = "Please enter a valid email address";
                    }
                }
            }
            if (count($this->errorBag)) {
                foreach ($this->errorBag as $key => $value) {
                    foreach ($array as $k => $a) {
                        if (!isset($this->errorBag[$k])) {
                            $this->errorBag[$k] = 'warning';
                        }
                    }
                }
            }
        }


        return $this->errorBag;

    }

    public function exists($array)
    {

    }

    public function mipValidation($file, $idNumber, $mobileNumber)
    {

        $mobileNumber = $this->numberFormat($mobileNumber, 10);
        $path = public_path('mip/' . strtolower($file));
        $customerData = [];
        $response = [];
        if (File::exists($path)) {
            $customerData = CSVHandler::process_file('mip/' . strtolower($file) . '/' . $file . '.csv', ',');
        } else {
            $sftp = new SFTPConn();
            $customerData = $sftp->listFiles();
            if ($customerData['success']) {
                $customerData = $customerData['data'];
            }
        }
        $mobileNumber = $this->numberFormat($mobileNumber, 10);
        if ($mobileNumber !== false) {
            if (strlen($mobileNumber) == 9) {
                $mobileNumber = '+254' . $mobileNumber;
            }
        }
        $response ['customer'] = [];
        if (count($customerData)) {
            $response = [];
            $response ['id_match'] = false;
            $response['number_match'] = false;
            $response['active'] = false;
            $idNumberValidation = ArrayHandler::array_search($idNumber, $customerData, 'ID Number');
            if ($idNumberValidation !== false) {
                $customer = $customerData[$idNumberValidation];
                $response ['customer'] = $customer;
                if (count($customer)) {
                    if ($customer['ID Number'] === $idNumber)
                        $response ['id_match'] = true;

                    if ($customer['Mobile Number'] === $mobileNumber)
                        $response['number_match'] = true;

                    if ($customer['Active Debtor'] === 'Y')
                        $response['active'] = true;

                }
            } else {


//                071 611 9279
                $numberValidation = ArrayHandler::array_search($mobileNumber, $customerData, 'Mobile Number');
                if ($numberValidation !== false) {
                    $response['number_match'] = true;
                    $response['id_match'] = false;
                    if ($customerData[$numberValidation]['Active Debtor'] === 'Y') {
                        $response['active'] = true;
                    }
                    $response ['customer'] = $customerData[$numberValidation];


                }

            }
        }

        return $response;
    }

    public function numberFormat($number, $numberLength)
    {
        $numberFormatted = false;
        $length = strlen($number);
        if ($length == $numberLength) {
            $char = substr($number, 0, 1);
            if ($char === '0') {
                $numberFormatted = substr($number, 1, 9);
            }
        }
        if ($length == 9) {
            $char = substr($number, 0, 1);
            if ($char !== '0') {
                $numberFormatted = $number;
            }
        }
        return $numberFormatted;
    }
}
