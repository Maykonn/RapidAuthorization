<?php

/**
 * Working with Tasks - Advanced_02
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

// List all roles that can access a Task
$taskId = 1;
echo 'ALL ROLES THAT CAN ACCES TASK #' . $taskId . '<pre>';
$rolesThaCanAccess = $authorization->task()->getRolesThatHasAccess($taskId);
print_r($rolesThaCanAccess);
echo '</pre>';

$taskId = 2; // in default examples not exist
echo 'ALL ROLES THAT CAN ACCES TASK #' . $taskId . '<pre>';
$rolesThaCanAccess = $authorization->task()->getRolesThatHasAccess($taskId);
print_r($rolesThaCanAccess);
echo '</pre>';

$taskId = 3;
echo 'ALL ROLES THAT CAN ACCES TASK #' . $taskId . '<pre>';
$rolesThaCanAccess = $authorization->task()->getRolesThatHasAccess($taskId);
print_r($rolesThaCanAccess);
echo '</pre>';

$taskId = 3;
echo 'DETACH TASK #' . $taskId . ' FROM FIRST ROLE THAT CAN ACCESS THE TASK: ';
var_dump($authorization->task()->removeTaskFromRole($taskId, $rolesThaCanAccess[0]['id_role']));