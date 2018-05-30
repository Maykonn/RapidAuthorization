<?php

/**
 * Working with Tasks - Advanced_01
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
require_once __DIR__ . '/vendor/autoload.php';

use RapidAuthorization\RapidAuthorization;

$configuration = Array(
	'mysqlHost' => 'localhost',
	'mysqlPort' => 3306,
	'mysqlUser' => 'root',
	'mysqlPass' => '123456',
	'dbName' => 'test_rbac',
	'dbCharset' => 'utf8', // optional param, default is utf8
	'userTable' => 'users', // optional param if is different of user, default is user
	'userTablePK' => 'id', // optional param if is different of id, default is id
	'autoGenerateTables' => true, // optional param, default is false
	//'useRapidAuthorizationAutoload' => true // optional param, default is false
);

$authorization = new RapidAuthorization($configuration);

// Attach Operation
$operationId = 3;
$taskId = 1;
echo 'ADD OPERATION #' . $operationId . ' TO TASK: #' . $taskId;
var_dump($authorization->task()->attachOperation($operationId, $taskId)) . '<br>';

$operationId = 4;
$taskId = 1;
echo 'ADD OPERATION #' . $operationId . ' TO TASK: #' . $taskId;
var_dump($authorization->task()->attachOperation($operationId, $taskId)) . '<br>';

$operationId = 1;
$taskId = 3;
echo 'ADD ROLE #' . $operationId . ' TO USER : #' . $taskId;
var_dump($authorization->task()->attachOperation($operationId, $taskId)) . '<br>';


// List all Operations that a Task has
$taskId = 1;
echo 'LISTING ALL OPERATION FROM TASK #' . $taskId . '<pre>';
$taskOperations = $authorization->task()->getOperations($taskId);
print_r($taskOperations);
echo '</pre>';

$taskId = 3;
echo 'LISTING ALL OPERATION FROM TASK #' . $taskId . '<pre>';
$taskOperations = $authorization->task()->getOperations($taskId);
print_r($taskOperations);
echo '</pre>';


// Verify if a Task has an Operation
$operationId = 1;
$taskId = 1;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 2; // in default example not exists
$taskId = 1;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 3;
$taskId = 1;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 4;
$taskId = 1;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 1;
$taskId = 2; // in default example not exists
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 1;
$taskId = 3;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 3;
$taskId = 3;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';

$operationId = 4;
$taskId = 3;
echo 'TASK #' . $taskId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->task()->hasOperation($operationId, $taskId)) . '<br>';