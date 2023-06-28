<?php
 function donotdeleethisisforcreditmate(){
//Data, connection, auth


    $soapUrl = "https://webservices-uat.compuscan.co.za/NormalSearchService?wsdl"; // asmx URL of WSDL
    $soapUser = "2557-uat";  //  username
    $soapPassword = "t=Qyv4tivT"; // password

// xml post structure

    $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://webServices/">
    <soapenv:Header/>
    <soapenv:Body>
        <web:DoNormalEnquiry>
            <request>
                <pUsrnme>2557-uat</pUsrnme>
                <pPasswrd>t=Qyv4tivT</pPasswrd>
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
                            <Identity_number>7304125018084</Identity_number>
                            <Surname>surname</Surname>
                            <Forename>surname</Forename>
                            <Forename2></Forename2>
                            <Forename3>surname</Forename3>
                            <Gender></Gender>
                            <Passport_flag>N</Passport_flag>
                            <DateOfBirth>20000202</DateOfBirth>
                            <Address1>2 QAX</Address1>
                            <Address2>SUB</Address2>
                            <Address3>SUB</Address3>
                            <Address4>TOWN</Address4>
                            <PostalCode>1111</PostalCode>
                            <HomeTelCode></HomeTelCode>
                            <HomeTelNo>(0111)1234567</HomeTelNo>
                            <WorkTelCode></WorkTelCode>
                            <WorkTelNo>(012)7145874</WorkTelNo>
                            <CellTelNo>71785486</CellTelNo>
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
</soapenv:Envelope>'

    ;

// data from the form, e.g. some ID number

    $headers = array(
        "Content-type: text/xml;charset=\"utf-8\"",
        "Accept: text/xml",
        "Cache-Control: no-cache",
        "Pragma: no-cache",
        "Content-length: ".strlen($xml_post_string),
    ); //SOAPAction: your op URL

    $url = $soapUrl;

// PHP cURL  for https connection with auth
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// converting
    $response = curl_exec($ch);

    $xml = simplexml_load_string($response);

    echo $xml->getName() . "<br />";
    dd($xml,$xml->children(),$xml->getName(),$response);
    foreach($xml->children() as $child)
    {
        dd('sadji');
        dd($child,'kisao');
        echo $child->getName() . ": " . $child . "<br />";
    }

    exit;


    dd($xml,$response);
    $zipStr = "UEsDBBQACAAIABprd_etc";
    $zip_Array = explode(";base64,", $zipStr);
    $zip_contents = base64_decode($zip_Array[1]);
    $file = $folderPath . uniqid() . '.zip';
    file_put_contents($file, $zip_contents);


    curl_close($ch);

// converting
    $response1 = str_replace("<soap:Body>","",$response);
    $response2 = str_replace("</soap:Body>","",$response1);

// convertingc to XML
//    $parser = simplexml_load_string($response2);
// user $parser to get your data out of XML response and to display it.
    echo  $response2;
//    dd($response);
}
