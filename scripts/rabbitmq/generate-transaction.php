<?php
//Requires Required Files
require_once(__DIR__ . '/../../global.php');
require_once(__DIR__ . '/../../src/log.php');
require_once(__DIR__ . '/../../src/general.php');
require_once(__DIR__ . '/../../vendor/autoload.php');

//Require Required Classes
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$log = new log();
$general = new general();

//Connect to RabbitMQ
$connection = new AMQPStreamConnection(RABBITMQ_HOST, RABBITMQ_PORT, RABBITMQ_USER, RABBITMQ_PASS);
$channel = $connection->channel();

//Define RabbitMQ QUEUE
$channel->queue_declare('generate-transaction', false, true, false, false);

//Sent Data to QUEUE - Create - Capturate DATA to insert in elasticsearch
$data = array();
$data['MERCHANT_ID'] = (int)rand(10,10000);
$data['TRANSACTION_ID'] = (int)rand(10,10000000);
$data['ORDER_ID'] = $general->randomString(20);
$data['REFERENCE_NUMBER'] = $general->randomString(10);
$data['TRANSACTION_DATE'] = date('Y-m-d H:i:s');
$data['TRANSACTION_TYPE'] = (int)rand(0,10);
$data['TRANSACTION_STATE'] = (int)rand(0,10);;
$data['TRANSACTION_AMOUNT'] = (float)$general->randomFloat(10, 5000);
$data['INSTALLMENTS'] = (int)rand(0,10);;
$data['CREDIT_CARD_TYPE'] = (int)rand(0,10);;
$data['AUTH_CODE'] = $general->randomString(6);
$data['RESPONSE_CODE'] = $general->randomString(2);
$data['NSU'] = $general->randomString(6);
$data['GATEWAY_TRANSACTION_ID'] = $general->randomString(20);;
$data['CURRENCY_CODE'] = 'BRL';
$data['GATEWAY_CODE'] = $general->randomString(2);;
$data['GATEWAY_MESSAGE'] = $general->randomString(20);;
$data['RECURRING_PAYMENT'] = (int)0;
$data['GATEWAY_REFERENCE_NUMBER'] = $general->randomString(6);

$dataJson = json_encode($data);
$msg = new AMQPMessage($dataJson, array('delivery_mode' => 2));
$channel->basic_publish($msg, '', 'generate-transaction');
echo " [x] Sent ", $dataJson, "\n";

$channel->close();
$connection->close();