<?php
require_once 'src/classes/Router.php';
require_once 'src/classes/Encrypt.php';
require_once 'src/classes/WordList.php';
require_once 'src/classes/Trip.php';
require_once 'src/classes/Decrypt.php';
require_once 'src/classes/Rainbow.php';
require_once 'src/classes/Julius.php';

$router = new Router();

$router->addRoute('GET', '/', function () {
    header('Location: /ui/index.php');
    return;
});

// curl -X POST -d '{"stringToEncrypt":["sailboat","password"]}' localhost:8888/encrypt/list
$router->addRoute('POST', '/encrypt/list', function ($password) {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $requestBody = file_get_contents('php://input');
    file_put_contents('request_body.txt', $requestBody);
    //echo $requestData['stringToEncrypt']; exit;
    $enc = new Encrypt();
    $encryptedPassword = $enc->encryptString($requestData['stringToEncrypt']);
    
    echo $encryptedPassword;
    /*echo trim("{
        \"encrypted\": [\"xzsJ9xB1g2\"]
    }");*/     
    return $encryptedPassword;
});

// curl -X POST -d '{"tripcodeList":["QZ5rf9d3T.","ozOtJW9BFA","Ofltcn0wng"]}' -H "Content-Type: application/json" localhost:8888/decrypt/list
$router->addRoute('POST', '/decrypt/list', function () {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $decryptObj = new Decrypt();
    $decryptedTripcode = $decryptObj->decryptTripcodeList($requestData['tripcodeList']);
    
    //print_r($decryptedTripcode);
    echo $decryptedTripcode;
    return $decryptedTripcode;
});

// curl -X GET http://localhost:8888/rainbow/build
$router->addRoute('GET', '/rainbow/build', function () {
    $wordList = new WordList();
    $preparedWordList = $wordList->prepareWordListDir();

    $rainbow = new Rainbow();
    $rainbow->redisRowCount();
    $rainbow->addWordListDirToRainbow($preparedWordList);

    return $rainbow;
});

// curl -X GET localhost:8888/rainbow/count
$router->addRoute('GET', '/rainbow/count', function () {
    $rainbow = new Rainbow();
    //$rainbow->redisRowCount();

    $rainbow->redisRowCount();
});

// curl -X DELETE localhost:8888/rainbow/purge
$router->addRoute('DELETE', '/rainbow/purge', function () {
    $wordList = new WordList();
    $preparedWordList = $wordList->prepareWordListDir();

    $rainbow = new Rainbow();
    $rainbow->redisRowCount();
    $rainbow->purgeRainbowTable($preparedWordList);
    $rainbow->redisRowCount();

    return $rainbow;
});

//curl -X GET http://localhost:8888/julius/run
$router->addRoute('GET', '/julius/run', function () {
    $jul = new Julius();
    $jul->downloadAllPosts();

    return $jul;
});

// curl -X GET http://localhost:8888/julius/uniqueips
$router->addRoute('GET', '/julius/uniqueips', function () {
    $jul = new Julius();
    echo $jul->getUniqueIPs();

    return $jul;
});

// curl -X GET http://localhost:8888/julius/alltripcodes
$router->addRoute('GET', '/julius/alltripcodes', function () {
    $jul = new Julius();
    echo $jul->getAllTripcodes();
    return $jul;
});

// curl -X GET http://localhost:8888/julius/s2n
$router->addRoute('GET', '/julius/s2n', function () {
    $jul = new Julius();
    echo json_encode($jul->getSignal2Noise());
    //return $jul;
});

// curl -X GET http://localhost:8888/julius/capcode
$router->addRoute('GET', '/julius/capcode', function () {
    $jul = new Julius();
    echo json_encode($jul->getCapcode());
    return $jul;
});

// curl -X GET http://localhost:8888/julius/namedusers
$router->addRoute('GET', '/julius/namedusers', function () {
    $jul = new Julius();
    echo json_encode($jul->getNamedUsers());
    return $jul;
});

// curl -X POST -d '{"name": "LilAnon"}' -H "Content-Type: application/json" localhost:8888/julius/search/name
$router->addRoute('POST', '/julius/search/name', function () {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $jul = new Julius();
    echo json_encode($jul->searchName($requestData['name']));
    return $jul;
});

// curl -X POST -d '{"subject": "/div/ - Divination General"}' -H "Content-Type: application/json" localhost:8888/julius/search/subject
$router->addRoute('POST', '/julius/search/subject', function () {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $jul = new Julius();
    echo json_encode($jul->searchSubj($requestData['subject']));
    return $jul;
});

// curl -X POST -d '{"comment": "might as well"}' -H "Content-Type: application/json" localhost:8888/julius/search/comment
$router->addRoute('POST', '/julius/search/comment', function () {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $jul = new Julius();
    echo json_encode($jul->searchComment($requestData['comment']));
    return $jul;
});

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$router->handleRequest($requestMethod, $requestUri);