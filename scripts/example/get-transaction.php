<?php
//Requires Required Files
require_once(__DIR__ . '/../../global.php');
require_once(__DIR__ . '/../../src/log.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

//Set timezone
date_default_timezone_set('America/Sao_Paulo');

//Require Required Classes
$log = new log();
use Elasticsearch\ClientBuilder;

//Define host connection - ELASTIC SEARCH
$hosts = [
     // This is effectively equal to: "https://username:password!#$?*abc@foo.com:9200/"
     [
         'host' => ELASTICSEARCH_HOST,
         'port' => ELASTICSEARCH_PORT,
         'scheme' => ELASTICSEARCH_SCHEME
     ]
 ];

//Create - Capturate DATA to insert in elasticsearch
$data = array();
$data['TRANSACTION_ID'] = (int)165615714;

$log->logar("Get Transaction: ".$data['TRANSACTION_ID']." ".print_r($data,true),"get-transaction/");

//Connect to Elastic Search
try {    

    $client = ClientBuilder::create()       // Instantiate a new ClientBuilder
                    ->setHosts($hosts)      // Set the hosts
                    ->build();              // Build the client object
    
    //Define params to send elastic search
    $params = [
        'index' => 'transaction',
        'type' => 'transaction',
        'id'    => $data['TRANSACTION_ID']
    ];
      
    $response = $client->get($params);
    $log->logar(print_r($response,true),"get-transaction/");
    
} catch (Exception $e) {    
    $log->logar(print_r($e,true),"get-transaction/");   
}