<?php
//Requires Required Files
require_once(__DIR__ . '/../../global.php');
require_once(__DIR__ . '/../../src/log.php');
require_once(__DIR__ . '/../../src/general.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

//Require Required Classes
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Elasticsearch\ClientBuilder;

$log = new log();
$general = new general();

//Connect to RabbitMQ
$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
$channel = $connection->channel();

//Define RabbitMQ QUEUE
$channel->queue_declare('generate-transaction', false, true, false, false);

//Show in Screen - Waiting
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

//Instance RABBITMQ Queue
$callback = function($msg) {
    
    //Show in screen Data Received
    echo " [x] Received ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
        
    //Transform msg body in array to send elastic search
    $data = (array) json_decode($msg->body);    
    
    //Define host connection - ELASTIC SEARCH
    $hosts = [
         // This is effectively equal to: "https://username:password!#$?*abc@foo.com:9200/"
         [
             'host' => ELASTICSEARCH_HOST,
             'port' => ELASTICSEARCH_PORT,
             'scheme' => ELASTICSEARCH_SCHEME
         ]
     ];
    
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
        $log->logar(print_r($response,true),"insert-transaction-elasticsearch/");

    } catch (Exception $e) {    
        $log->logar(print_r($e,true),"insert-transaction-elasticsearch/");   
    }    
        
    echo " [x] Done", "\n";
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);

//Consumers
$consumer_0 = $channel->basic_consume('generate-transaction', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();