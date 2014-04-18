<?php
require('vendor/autoload.php');
require('routers/MyArticle.php');

use Respect\Rest\Router;
//var_dump($_SERVER);
//http://www.respect.com:8080/myapp/article/1

$router = new Router('/myapp');

$router -> isAutoDispatched = false;

$router -> get('/', function() {
	return 'Hello World';
});

$router -> get('/hello', function() {
	return 'Hello World';
});

$router -> get('/users', function() {
	echo "users index";
});

$router -> get('/users/*', function($screenName) {
	echo "User {$screenName}";
});

$router -> get('/users/*/lists/*', function($user, $list) {
	return "List {$list} from user {$user}.";
});

$router -> get('/posts/*/*/*', function($year, $month=null,$day=null) {
	/** list posts,month and day are optional */
});

$router -> get('/users/*/documents/**', function($user, $documentPath) {
	//return readfile(PATH_STORAGE, implode('/', $documentPath));
});

$router -> get('/user/*', function($userName) {
	return 'Hello ' . $userName;
});

$router -> any('/users/*', function($userName) {

});

$router -> any('/article/*', 'MyArticle');

print $router -> run();

//var_dump($router);


// $url ="http://example.com";
// $data = "The updated text message";
// $ch = curl_init();
// curl_setopt($ch,CURLOPT_URL,$url);
// curl_setopt($ch, CURLOPT_POST, true);
// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");  //for updating we have to use PUT method.
// curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
// $result = curl_exec($ch);
// $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// curl_close($ch);

function httpRequest($host, $port, $method, $path, $params) {
	// Params are a map from names to values
	$paramStr = "";
	foreach ($params as $name => $val) {
		$paramStr .= $name . "=";
		$paramStr .= urlencode($val);
		$paramStr .= "&";
	}

	// Assign defaults to $method and $port, if needed
	if (empty($method)) {
		$method = 'GET';
	}
	$method = strtoupper($method);
	if (empty($port)) {
		$port = 80; // Default HTTP port
	}

	// Create the connection
	$sock = fsockopen($host, $port);
	if ($method == "GET") {
		$path .= "?" . $paramStr;
	}
	fputs($sock, "$method $path HTTP/1.1\r\n");
	fputs($sock, "Host: $host\r\n");
	fputs($sock, "Content-type: " .
	           "application/x-www-form-urlencoded\r\n");
	if ($method == "POST") {
	fputs($sock, "Content-length: " . 
	             strlen($paramStr) . "\r\n");
	}
	fputs($sock, "Connection: close\r\n\r\n");
	if ($method == "POST") {
		fputs($sock, $paramStr);
	}

	// Buffer the result
	$result = "";
	while (!feof($sock)) {
		$result .= fgets($sock,1024);
	}

	fclose($sock);
	return $result;
}
$resp = httpRequest("www.acme.com",
    80, "POST", "/userDetails",
    array("firstName" => "John", "lastName" => "Doe"));