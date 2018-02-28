<?php
//Requires Required Files
require_once(__DIR__ . '/../../global.php');
require_once(__DIR__ . '/../../src/log.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

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

//Connect to Elastic Search
try {    

    $client = ClientBuilder::create()       // Instantiate a new ClientBuilder
                    ->setHosts($hosts)      // Set the hosts
                    ->build();              // Build the client object
    
    //Define params to send elastic search
    $params = [
     'index' => 'transaction',
     'body' => [
         'mappings' => [
             'transaction' => [
                 '_source' => [
                     'enabled' => true
                 ],
                 'properties' => [                     
                     'MERCHANT_ID' => [
                         'type' => 'integer'
                     ],
                     'TRANSACTION_ID' => [
                         'type' => 'integer'
                     ],
                     'ORDER_ID' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],
                     'REFERENCE_NUMBER' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],
                     'TRANSACTION_DATE' => [
                         'type' => 'date'
                     ],                      
                     'TRANSACTION_TYPE' => [
                         'type' => 'integer'
                     ],
                     'TRANSACTION_STATE' => [
                         'type' => 'integer'
                     ],
                     'TRANSACTION_AMOUNT' => [
                         'type' => 'float'
                     ],
                     'INSTALLMENTS' => [
                         'type' => 'integer'
                     ],
                     'CREDIT_CARD_TYPE' => [
                        'type' => 'integer'
                     ],
                     'AUTH_CODE' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],
                     'RESPONSE_CODE' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],
                     'NSU' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],
                     'GATEWAY_TRANSACTION_ID' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],                                          
                     'CURRENCY_CODE' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],                     
                     'GATEWAY_CODE' => [
                         'type' => 'integer'
                     ],
                     'GATEWAY_MESSAGE' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],                    
                     'RECURRING_PAYMENT' => [
                        'type' => 'integer'
                     ],
                     'GATEWAY_REFERENCE_NUMBER' => [
                         'type' => 'string',
                         'index' => 'not_analyzed'
                     ],     
                 ]
             ]
         ]
     ]
 ];

    $response = $client->indices()->create($params);
    $log->logar(print_r($response,true),"mapping-transaction/");
    
} catch (Exception $e) {    
    $log->logar(print_r($e,true),"mapping-transaction/");   
}