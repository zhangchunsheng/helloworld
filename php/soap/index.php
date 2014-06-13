<?php
/**
 * @title soap
 * @description
 * soap
 * @author zhangchunsheng@yongche.com
 * @version V3.0
 * @date 2014-06-12
 * @copyright  Copyright (c) 2010-2014 yongche Inc. (http://www.yongche.com)
 */
$url = "http://202.108.100.194:8010/eb_b2b_b2c/services/ebridgeWsVendor?wsdl";

class Luomor_SoapClient extends SoapClient {
    const TIMEOUT = 20;

    function __construct($wsdl, $options = NULL) {
        parent::__construct($wsdl, ($options ? $options : array()));
    }

    function __doRequest($request, $location, $action, $version) {
        $sxe = new SimpleXMLElement($request);
        $namespaces = $sxe->getNamespaces(true);
        $first_namespace = array_keys($namespaces)[0];
        $second_namespace = array_keys($namespaces)[1];
        $child = $sxe->children($namespaces[$first_namespace]);
        $child = $child->children($namespaces[$second_namespace]);
        $params = $child->{$child->getName()};
        $url = substr($location, 0, strripos($location, ".")) . "/" . str_replace("urn:", "", $action);

        $params = (array)$params;
        $param = "";
        foreach($params as $key => $value) {
            $param .= $key . "=" . $value . "&";
        }
        $param = substr($param, 0, strlen($param) - 1);
        $url = $url . "?" . $param;
        $result = $this->curl($url);
        $result = parent::__doRequest($request, $location, $action, $version);
        //print_r($result);
        /**
         *
         * <?xml version='1.0' encoding='utf-8'?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Body><ns:getImeiStatusResponse xmlns:ns="http://impl.ws.b2b.ebridge.dcms"><ns:return>&lt;?xml version="1.0" encoding="UTF8"?&gt;&lt;ImeiStatus&gt;&lt;MsgNo&gt;861586000085254&lt;/MsgNo&gt;&lt;Status&gt;F&lt;/Status&gt;&lt;ErrorReason&gt;串码：861586000085254非优惠政策产品串码&lt;/ErrorReason&gt;&lt;/ImeiStatus&gt;</ns:return></ns:getImeiStatusResponse></soapenv:Body></soapenv:Envelope>
         */
        return $result;
    }

    public static function curl($url, $method = "GET", $post_fields = NULL, $header = array()) {
        $ch = curl_init();
        $class = get_called_class();

        try {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, 0);// TRUE to include the header in the output
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

            if($method == "POST") {
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            }
            $result = curl_exec($ch);

            if(curl_error($ch)) {
                error_log("access $url from $class error:" . curl_error($ch));
            }
            curl_close($ch);
        } catch(Exception $e) {
            curl_close($ch);
            throw $e;
        }

        if(empty($result)) {
            return false;
        }

        return $result;
    }
}

function test() {
    $xmlstr = <<<XML
<?xml version='1.0' standalone='yes'?>
<movies>
 <movie>
  <title>PHP: Behind the Parser</title>
  <characters>
   <character>
    <name>Ms. Coder</name>
    <actor>Onlivia Actora</actor>
   </character>
   <character>
    <name>Mr. Coder</name>
    <actor>El Act&#211;r</actor>
   </character>
  </characters>
  <plot>
   So, this language. It's like, a programming language. Or is it a
   scripting language? All is revealed in this thrilling horror spoof
   of a documentary.
  </plot>
  <great-lines>
   <line>PHP solves all my web problems</line>
  </great-lines>
  <rating type="thumbs">7</rating>
  <rating type="stars">5</rating>
 </movie>
</movies>
XML;

    $xml = simplexml_load_string($xmlstr);
    $xmlstr = <<<XML
<SOAP-ENV xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://impl.ws.b2b.ebridge.dcms"><SOAP-ENV><ns1><ns1>&lt;?xml version="1.0" encoding="UTF8"?&gt;&lt;ImeiStatus&gt;&lt;Imei&gt;861586000085254&lt;/Imei&gt;&lt;QueryFrom&gt;易到&lt;/QueryFrom&gt;&lt;QueryType&gt;0&lt;/QueryType&gt;&lt;QueryDate&gt;2014-06-09 21:23:22&lt;/QueryDate&gt;&lt;Node1&gt;&lt;/Node1&gt;&lt;Node2&gt;&lt;/Node2&gt;&lt;Node3&gt;&lt;/Node3&gt;&lt;Node4&gt;&lt;/Node4&gt;&lt;Node5&gt;&lt;/Node5&gt;&lt;Node6&gt;&lt;/Node6&gt;&lt;/ImeiStatus&gt;</ns1><ns1>YIDAO</ns1><ns1>666666</ns1></ns1></SOAP-ENV></SOAP-ENV>
XML;
}

$client = new Luomor_SoapClient($url);

$xml = <<<XML
<?xml version="1.0" encoding="UTF8"?>
<ImeiStatus>
    <Imei>861586000085254</Imei>
    <QueryFrom>易到</QueryFrom>
    <QueryType>0</QueryType>
    <QueryDate>2014-06-09 21:23:22</QueryDate>
    <Node1></Node1>
    <Node2></Node2>
    <Node3></Node3>
    <Node4></Node4>
    <Node5></Node5>
    <Node6></Node6>
</ImeiStatus>
XML;

$sxe = new SimpleXMLElement($xml);
$sxe->Imei = 861586000085254;
$sxe->QueryFrom = "易到";
$sxe->QueryType = 0;
$sxe->QueryDate = date("Y-m-d H:i:s");
$sxe->Node1 = '';
$sxe->Node2 = '';
$sxe->Node3 = '';
$sxe->Node4 = '';
$sxe->Node5 = '';
$sxe->Node6 = '';
print_r($sxe->asXML());
$xml = '<?xml version="1.0" encoding="UTF8"?><ImeiStatus><Imei>861586000085254</Imei><QueryFrom>易到</QueryFrom><QueryType>0</QueryType><QueryDate>2014-06-09 21:23:22</QueryDate><Node1></Node1><Node2></Node2><Node3></Node3><Node4></Node4><Node5></Node5><Node6></Node6></ImeiStatus>';
$UserID = 'YIDAO';
$pwd = '666666';

$params = new StdClass;
$params -> xml = $xml;
$params -> UserID = $UserID;
$params -> pwd = $pwd;

$paramInfo = array(
    'xml' => $xml,
    'UserID' => $UserID,
    'pwd' => $pwd
);
//var_dump($client->__getFunctions());
/**
 * array(6) {
 * [0]=>
 * string(50) "getPoImeiResponse getPoImei(getPoImei $parameters)"
 * [1]=>
 * string(35) "testResponse test(test $parameters)"
 * [2]=>
 * string(62) "getImeiStatusResponse getImeiStatus(getImeiStatus $parameters)"
 * [3]=>
 * string(50) "getPoImeiResponse getPoImei(getPoImei $parameters)"
 * [4]=>
 * string(35) "testResponse test(test $parameters)"
 * [5]=>
 * string(62) "getImeiStatusResponse getImeiStatus(getImeiStatus $parameters)"
 * }
 */
//var_dump($client->__getTypes());
/**
 *
array(6) {
[0]=>
string(28) "struct test {
string msg;
}"
[1]=>
string(39) "struct testResponse {
string return;
}"
[2]=>
string(62) "struct getPoImei {
string xml;
string UserID;
string pwd;
}"
[3]=>
string(44) "struct getPoImeiResponse {
string return;
}"
[4]=>
string(66) "struct getImeiStatus {
string xml;
string UserID;
string pwd;
}"
[5]=>
string(48) "struct getImeiStatusResponse {
string return;
}"
}
 */
//$result = $client->__soapCall("getImeiStatus", array($paramInfo));
$result = $client->getImeiStatus($paramInfo);

$xml = simplexml_load_string($result->return);
print_r($xml->MsgNo->__toString());
print_r($xml->Status->__toString());
print_r($xml->ErrorReason->__toString());

$getImeiStatusUrl = 'http://202.108.100.194:8010/eb_b2b_b2c/services/ebridgeWsVendor/getImeiStatus?xml=%3C?xml%20version=%221.0%22%20encoding=%22UTF8%22?%3E%3CImeiStatus%3E%3CImei%3E861586000085254%3C/Imei%3E%3CQueryFrom%3E%E6%98%93%E5%88%B0%3C/QueryFrom%3E%3CQueryType%3E0%3C/QueryType%3E%3CQueryDate%3E2014-06-09%2021:23:22%3C/QueryDate%3E%3CNode1%3E%3C/Node1%3E%3CNode2%3E%3C/Node2%3E%3CNode3%3E%3C/Node3%3E%3CNode4%3E%3C/Node4%3E%3CNode5%3E%3C/Node5%3E%3CNode6%3E%3C/Node6%3E%3C/ImeiStatus%3E&UserID=YIDAO&pwd=666666';
//echo file_get_contents($getImeiStatusUrl);