<?php

/**
 * Working with Roles - Advanced
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
$sep = DIRECTORY_SEPARATOR;
require_once '..' . $sep . 'RapidAuthorization.php';

use RapidAuthorization\RapidAuthorization;

$configuration = Array(
    'mysqlHost' => 'localhost',
    'mysqlPort' => 3306,
    'mysqlUser' => 'root',
    'mysqlPass' => '',
    'dbName' => 'rapid_authorization',
    'userTable' => 'user_table',
    'userTablePK' => 'user_pk',
    'useRapidAuthorizationAutoload' => true,
);

$authorization = new RapidAuthorization($configuration);

// Attach Task
$taskId = 1;
$roleId = 3;
echo 'ADD TASK #' . $taskId . ' TO ROLE: #' . $roleId;
var_dump($authorization->role()->attachTask($taskId, $roleId)) . '<br>';

$taskId = 3;
$roleId = 1;
echo 'ADD TASK #' . $taskId . ' TO ROLE: #' . $roleId;
var_dump($authorization->role()->attachTask($taskId, $roleId)) . '<br>';


// List all Tasks that a Role has access
$roleId = 1;
echo 'TASKs THAT ROLE #' . $roleId . ' HAS ACCESS: <pre>';
$tasksThatHasAccess = $authorization->role()->getTasks($roleId);
print_r($tasksThatHasAccess);
echo '</pre>';

$roleId = 2; // in default example not exist
echo 'TASKs THAT ROLE #' . $roleId . ' HAS ACCESS: <pre>';
$tasksThatHasAccess = $authorization->role()->getTasks($roleId);
print_r($tasksThatHasAccess);
echo '</pre>';

$roleId = 3;
echo 'TASKs THAT ROLE #' . $roleId . ' HAS ACCESS: <pre>';
$tasksThatHasAccess = $authorization->role()->getTasks($roleId);
print_r($tasksThatHasAccess);
echo '</pre>';


// List all Operations that a Role has access
$roleId = 1;
echo 'OPERATIONs THAT ROLE #' . $roleId . ' HAS ACCESS: <pre>';
$tasksThatHasAccess = $authorization->role()->getOperations($roleId);
print_r($tasksThatHasAccess);
echo '</pre>';

$roleId = 2; // in default example not exist
echo 'OPERATIONs THAT ROLE #' . $roleId . ' HAS ACCESS: <pre>';
$tasksThatHasAccess = $authorization->role()->getOperations($roleId);
print_r($tasksThatHasAccess);
echo '</pre>';

$roleId = 3;
echo 'OPERATIONs THAT ROLE #' . $roleId . ' HAS ACCESS: <pre>';
$tasksThatHasAccess = $authorization->role()->getOperations($roleId);
print_r($tasksThatHasAccess);
echo '</pre>';


// Verify if an Role has Access to a Task
$taskId = 1;
$roleId = 1;
echo 'ROLE #' . $roleId . ' HAS TASK: #' . $taskId;
var_dump($authorization->role()->hasAccessToTask($taskId, $roleId)) . '<br>';

$taskId = 2; // in default example not exist
$roleId = 1;
echo 'ROLE #' . $roleId . ' HAS TASK: #' . $taskId;
var_dump($authorization->role()->hasAccessToTask($taskId, $roleId)) . '<br>';

$taskId = 3;
$roleId = 1;
echo 'ROLE #' . $roleId . ' HAS TASK: #' . $taskId;
var_dump($authorization->role()->hasAccessToTask($taskId, $roleId)) . '<br>';


// Verify if an Role has Access to a Operation
$operationId = 1;
$roleId = 3;
echo 'ROLE #' . $roleId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->role()->hasAccessToOperation($operationId, $roleId)) . '<br>';

$operationId = 2; // in default example not exist
$roleId = 3;
echo 'ROLE #' . $roleId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->role()->hasAccessToOperation($operationId, $roleId)) . '<br>';

$operationId = 3;
$roleId = 3;
echo 'ROLE #' . $roleId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->role()->hasAccessToOperation($operationId, $roleId)) . '<br>';

$operationId = 4;
$roleId = 3;
echo 'ROLE #' . $roleId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->role()->hasAccessToOperation($operationId, $roleId)) . '<br>';

$operationId = 4;
$roleId = 1234; // in default example not exist
echo 'ROLE #' . $roleId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->role()->hasAccessToOperation($operationId, $roleId)) . '<br>';


// List all User that has permission of a Role
$roleId = 1;
echo 'IDs OF USERs THAT HAS PERMISSIONS OF ROLE #' . $roleId . '<pre>';
$users = $authorization->role()->getUsersThatHasPermission($roleId);
print_r($users);
echo '</pre>';

$roleId = 3;
echo 'IDs OF USERs THAT HAS PERMISSIONS OF ROLE #' . $roleId . '<pre>';
$users = $authorization->role()->getUsersThatHasPermission($roleId);
print_r($users);
echo '</pre>';

// Removing all users from a Role
//$roleId = 3;
//echo 'REMOVED ALL USER FROM ROLE #' . $roleId . '<pre>';
//var_dump($authorization->role()->removeUsersFromRole($roleId));
//echo '</pre>';