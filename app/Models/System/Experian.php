<?php


namespace App\Models\System;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use ZipArchive;

class Experian
{

    private $soapConfig;

    public function __construct()
    {
        $this->soapConfig = [
            'url' => 'https://webservices-uat.compuscan.co.za/NormalSearchService?wsdl',
            'user' => '2557-uat',
            'password' => 't=Qyv4tivT',
        ];
    }

    public function report($request)
    {

        $xml = self::xml($request);
        $client = new Client();
        $response = $client->request('POST', $this->soapConfig['url'], [
            'headers' => [
                'Content-Type' => 'text/xml; charset=UTF8',
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "Content-length: " . strlen($xml),
            ],
            'body' => $xml
        ]);

        $response = $response->getBody()->getContents();

        $token = "<retData>";
        $result = "";
        $index = strpos($response, $token);

        if ($index !== false) {
            $result = substr($response, $index + strlen($token));
        }

        $token = "</retData>";
        $index = strpos($result, $token);
        if ($index !== false) {
            $result = substr($result, 0, $index + strlen($token));
        }

        $result = str_replace("</retData>", '', $result);

        if ((bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $result)) {


            $company = DataStorage::dataByID($request['company_id'], config('system_config.models.company'));
            if (!is_null($company)) {
                $branch = DataStorage::dataByID($request['branch_id'], config('system_config.models.branch'));
                if (!is_null($branch)) {
                    $path = public_path('/documents/' . $company->company_unique_id . '/' . $branch->branch_unique_id . '/reports/' . $request['id_number'] . '/');
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0777, true, true);
                    }
                    $zip_contents = base64_decode($result);
                    $file = $path . $request['id_number'] . '.zip';
                    if (file_put_contents($file, $zip_contents) !== false) {
                       return self::unzip($request, $path);
                    }

                }
            }


        }


        return ActionResponse::success('An error occurred',[]);
    }

    public function unzip($request, $path)
    {

        $zip = new ZipArchive();
        $status = $zip->open($path . '' . $request['id_number'] . '.zip');
        if ($status) {
            $zipFileArr = $zip->statIndex(0);
            if (count($zipFileArr)) {
                $oldFileName = $zip->getNameIndex(0);
                $newFileName = $request['id_number'] . '.json';
                $zip->renameName($oldFileName, $newFileName);
                $zip->close();
                $extract = new ZipArchive();
                $extract->open($path . '' . $request['id_number'] . '.zip');
                $extract->extractTo($path);
                if ($extract) {
                    $validate = CRUD::validate('application_id', config('system_config.models.credit_report_data'), $request['application']['id']);
                    if ($validate['success'] == false) {
                        $reportArr = [
                            'application_id' => $request['application']['id'],
                            'credit_report_data' => File::get($path . '' . $request['id_number'] . '.json'),
                            'credit_report_source' => 'compuscan',
                            'user_id' => $request['application']['user_id'],
                            'created_by' => Auth::user()->id,
                        ];
                        return CRUD::create(RequestEncrypt::encrypt($reportArr), config('system_config.models.credit_report_data'), 'credit_report_data');

                    }

                }
                $extract->close();

            }


        }


    }

    public function xml($request)
    {

//        7304125018084
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://webServices/">
                    <soapenv:Header/>
                    <soapenv:Body>
                        <web:DoNormalEnquiry>
                            <request>
                                <pUsrnme>' . $this->soapConfig['user'] . '</pUsrnme>
                                <pPasswrd>' . $this->soapConfig['password'] . '</pPasswrd>
                                <pVersion>1.0</pVersion>
                                <pOrigin>QATEST</pOrigin>
                                <pOrigin_Version>1.0</pOrigin_Version>
                                <pInput_Format>XML</pInput_Format>
                                <pTransaction><![CDATA[
                                    <Transactions>
                                        <Search_Criteria>
                                            <CS_Data>Y</CS_Data>
                                            <CPA_Plus_NLR_Data>Y</CPA_Plus_NLR_Data>
                                            <Deeds_Data>N</Deeds_Data>
                                            <Directors_Data>N</Directors_Data>
                                            <Identity_number>' . $request['id_number'] . '</Identity_number>
                                            <Surname>' . $request['last_name'] . '</Surname>
                                            <Forename>' . $request['first_name'] . '</Forename>
                                            <Forename2></Forename2>
                                            <Forename3></Forename3>
                                            <Gender></Gender>
                                            <Passport_flag>N</Passport_flag>
                                            <DateOfBirth></DateOfBirth>
                                            <Address1> </Address1>
                                            <Address2> </Address2>
                                            <Address3></Address3>
                                            <Address4></Address4>
                                            <PostalCode> </PostalCode>
                                            <HomeTelCode></HomeTelCode>
                                            <HomeTelNo></HomeTelNo>
                                            <WorkTelCode></WorkTelCode>
                                            <WorkTelNo></WorkTelNo>
                                            <CellTelNo>' . $request['mobile_number'] . '</CellTelNo>
                                            <ResultType>JSON</ResultType>
                                            <RunCodix>n</RunCodix>
                                            <CodixParams></CodixParams>
                                            <Adrs_Mandatory>Y</Adrs_Mandatory>
                                            <Enq_Purpose>12</Enq_Purpose>
                                            <Run_CompuScore>N</Run_CompuScore>
                                            <ClientConsent>Y</ClientConsent>
                                        </Search_Criteria>
                                    </Transactions>
                                    ]]></pTransaction>
                            </request>
                        </web:DoNormalEnquiry>
                    </soapenv:Body>
                </soapenv:Envelope>';

        return $xml;


    }


}
