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
$dt = new DateTime();
$dt->setTimeZone(new DateTimeZone('America/Sao_Paulo'));

$data = array();
$data['MERCHANT_ID'] = (int)23859;
$data['TRANSACTION_ID'] = (int)165615714;
$data['ORDER_ID'] = '0A011599:015A40C913B3:7068:339CC618';
$data['REFERENCE_NUMBER'] = '1707GBDyq4Ys';
$data['TRANSACTION_DATE'] = $dt->format('Y-m-d\TH:i:s.\0\0\0\Z');
$data['TRANSACTION_TYPE'] = (int)0;
$data['TRANSACTION_STATE'] = (int)1;
$data['TRANSACTION_AMOUNT'] = (float)'70.50';
$data['INSTALLMENTS'] = (int)1;
$data['CREDIT_CARD_TYPE'] = (int)1;
$data['AUTH_CODE'] = '123456';
$data['RESPONSE_CODE'] = '0';
$data['NSU'] = '688692';
$data['GATEWAY_TRANSACTION_ID'] = '_232e8a03-2e91-4d2c-a019-1ab67d8a0901';
$data['CURRENCY_CODE'] = 'BRL';
$data['GATEWAY_CODE'] = '0';
$data['GATEWAY_MESSAGE'] = 'TRANSACAO_AUTORIZADA';
$data['RECURRING_PAYMENT'] = (int)0;
$data['GATEWAY_REFERENCE_NUMBER'] = '1034865';

$log->logar("Insert Transaction: ".$data['TRANSACTION_ID']." ".print_r($data,true),"insert-transaction/");

//Connect to Elastic Search
try {    

    $client = ClientBuilder::create()       // Instantiate a new ClientBuilder
                    ->setHosts($hosts)      // Set the hosts
                    ->build();              // Build the client object
    
    //Define params to send elastic search
    $params = [
        'index' => 'transaction',
        'type' => 'transaction',
        'id' => $data['TRANSACTION_ID'], //enviar order id
        'body' => $data
    ];
    
    $response = $client->index($params);
    $log->logar(print_r($response,true),"insert-transaction/");
    
} catch (Exception $e) {    
    $log->logar(print_r($e,true),"insert-transaction/");   
}