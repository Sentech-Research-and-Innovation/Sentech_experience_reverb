<?php


namespace App\Models\System;


use App\Models\Web\DRAData;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DRAHandler
{
    private $projectUid;
    private $partnerReferenceId;
    private $nationalityCode;
    private $personalDetails;
    private $identityNumber;
    private $postData;
    private $uri;
    private $key;
    private $value;
    private $returnUrl;

    public function __construct()
    {
        $this->nationalityCode = 'KE';
        $this->projectUid = config('system_config.dra_config.projectUid');
        $this->postData = [];
        $this->uri = '';
        $this->key = config('system_config.dra_config.key');
        $this->value = config('system_config.dra_config.value');

    }

    public function draPost()
    {
        $user = Auth::user();
        $this->personalDetails = $user->personalDetails;
        if (!is_null($this->personalDetails)) {
            $this->nationalityCode = $this->personalDetails->country->alpha_2;
            $this->identityNumber = $this->personalDetails->id_number;
            if (!is_null($this->identityNumber)) {
                $this->partnerReferenceId = Str::random(30);
//                $this->returnUrl = env('APP_URL') . '/apply/' . $this->partnerReferenceId;
                $this->returnUrl = env('APP_URL').'/application/dra-odyssey';
                $this->uri = 'https://dev.odyssess.com/api/integration/v1-assessment-create-b';
                $this->postData = [
                    'projectUid' => $this->projectUid,
                    'partnerReferenceId' => $this->partnerReferenceId,
                    'lastName' => $user->lastname,
                    'firstName' => $user->firstname,
                    'identityNumber' => $this->identityNumber . Str::random(1),
                    'email' => $user->user_email,
                    'returnUrl' => $this->returnUrl,
                    'reference' => $this->partnerReferenceId . '-Izwe-' . $this->nationalityCode . 'Loan-(' . $this->identityNumber . ')',
                    'nationalityCode' => $this->nationalityCode,
                    'emailCandidate' => false,
                ];
                if (count($this->postData)) {
                    $this->postData = json_encode($this->postData);
                    try {
                        $client = new Client(['base_uri' => $this->uri]);
                        $response = $client->request('POST', $this->uri, [
                            'body' => $this->postData,
                            'headers' => [
                                $this->key => $this->value,
                                'Content-Type' => 'application/json'
                            ]
                        ]);
                        $response = json_decode($response->getBody()->getContents());
                        if (isset($response->code)) {
                            if ($response->code == 200) {
                                if ($response->message === 'success') {
                                    $draData =
                                        [
                                            'partnerReferenceId' => $this->partnerReferenceId,
                                            'dra_reference' => $this->partnerReferenceId,
                                            'user_id' => $user->id,
                                            'dra_response' => json_encode($response),
                                        ];
                                    DRAData::validateDraData($draData);
                                    return ActionResponse::success('Success', $response, true);
                                }
                            }
                        }

                    } catch (\Exception $e) {
                        $draError = [
                            'code' => $e->getCode(),
                            'message' => $e->getMessage(),
                        ];
                        return ActionResponse::error('An error occurred', $draError, false);
                    }
                }
            }
        }

    }

    public function draGet()
    {
        $user = Auth::user();
        $draData = CRUD::validate('user_id', config('system_config.models.dra'), $user->id);
        if ($draData['success']) {
            $draData = RequestEncrypt::decrypt($draData['data']->toArray());
            $draResponse = json_decode($draData['dra_response']);

            $draResponse->dra_test_score = json_decode($draData['dra_test_score']);
            $draResponse->test_score = 0;
            if (!is_null($draResponse->dra_test_score)) {
                if (!is_null($draResponse->dra_test_score)) {
                    $draResponse->test_score = 1;

                }
            }
            $draResponse->link_status = $draData['link_status'];
            $draResponse->partnerReferenceId = $draData['partnerReferenceId'];
            return ActionResponse::success('Dra records found', $draResponse, true);

        }
        return $draData;
    }

    public function draGetResults()
    {
        $user = Auth::user();

        try {
            $client = new Client();
            $dra = self::draGet();
            $getResults = false;
            $takeTheTest = false;
            $linkStatus = 'take_the_test';
            if ($dra['data']->message === 'success') {

                if (isset($dra['data']->dra_test_score)) {

                    if (!is_null($dra['data']->dra_test_score->totalRiskScore)) {
                        $linkStatus = 'test_taken';
                    } else {
                        $linkStatus = 'take_the_test';
                        $draData = [
                            'user_id' => $user->id,
                            'link_status' => $linkStatus,
                        ];
                        DRAData::validateDraData($draData);

                    }
                }

                if (is_null($dra['data']->dra_test_score)) {
                    self::callDra($client,$dra,$this->uri,$this->key,$this->value,$user,$linkStatus);
                } else {

                    if (!is_null($dra['data']->dra_test_score->totalRiskScore)) {
                        return CRUD::validate('user_id', config('system_config.models.dra'), $user->id);
                    }else{
                        self::callDra($client,$dra,$this->uri,$this->key,$this->value,$user,$linkStatus);
                    }

                }

            }

        } catch (\Exception $e) {
            $draError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
            return ActionResponse::error('An error occurred', $draError, false);
        }
    }

public  function callDra($client,$dra,$uri,$key,$value,$user,$linkStatus){
    $uri = 'https://dev.odyssess.com/api/integration/v1-dra-results-get?partnerReferenceId=' . $dra['data']->partnerReferenceId;
    $response = $client->request('GET', $uri, [
        'headers' => [
            $key => $value,
        ]
    ]);
    $response = json_decode($response->getBody()->getContents());
    $draData =
        [
            'user_id' => $user->id,
            'dra_test_score' => json_encode($response),
            'link_status' => $linkStatus,
        ];
    DRAData::validateDraData($draData);
    return ActionResponse::success('Success', $response, true);
}
}
