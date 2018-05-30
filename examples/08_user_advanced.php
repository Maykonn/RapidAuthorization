<?php

/**
 * Working with Users - Advanced
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
$configuration = require_once __DIR__ . '/config/config.php';
$authorization = new \RapidAuthorization\RapidAuthorization($configuration);

// List all Tasks that an User has access
$userId = 1;
echo 'LISTING ALL TASKS FROM USER #' . $userId . '<pre>';
$userTasks = $authorization->user()->getTasks($userId);
print_r($userTasks);
echo '</pre>';

$userId = 2;
echo 'LISTING ALL TASKS FROM USER #' . $userId . '<pre>';
$userTasks = $authorization->user()->getTasks($userId);
print_r($userTasks);
echo '</pre>';


// List all Operations that an User has access
$userId = 1;
echo 'LISTING ALL OPERATIONS FROM USER #' . $userId . '<pre>';
$userOperations = $authorization->user()->getOperations($userId);
print_r($userOperations);
echo '</pre>';

$userId = 2;
echo 'LISTING ALL OPERATIONS FROM USER #' . $userId . '<pre>';
$userOperations = $authorization->user()->getOperations($userId);
print_r($userOperations);
echo '</pre>';


// Verify if an User has a Role Permission
$roleId = 1;
$userId = 1;
echo 'USER #' . $userId . ' HAS ROLE #' . $roleId;
var_dump($authorization->user()->hasPermissionsOfTheRole($roleId, $userId)) . '<br>';

$roleId = 3;
$userId = 1;
echo 'USER #' . $userId . ' HAS ROLE #' . $roleId;
var_dump($authorization->user()->hasPermissionsOfTheRole($roleId, $userId)) . '<br>';

$roleId = 1;
$userId = 2;
echo 'USER #' . $userId . ' HAS ROLE #' . $roleId;
var_dump($authorization->user()->hasPermissionsOfTheRole($roleId, $userId)) . '<br>';

$roleId = 3;
$userId = 2;
echo 'USER #' . $userId . ' HAS ROLE #' . $roleId;
var_dump($authorization->user()->hasPermissionsOfTheRole($roleId, $userId)) . '<br>';


// Verify if an User has Access to a Task
$taskId = 1;
$userId = 1;
echo 'USER #' . $userId . ' HAS TASK: #' . $taskId;
var_dump($authorization->user()->hasAccessToTask($taskId, $userId)) . '<br>';

$taskId = 1;
$userId = 2;
echo 'USER #' . $userId . ' HAS TASK: #' . $taskId;
var_dump($authorization->user()->hasAccessToTask($taskId, $userId)) . '<br>';

$taskId = 3;
$userId = 2;
echo 'USER #' . $userId . ' HAS TASK: #' . $taskId;
var_dump($authorization->user()->hasAccessToTask($taskId, $userId)) . '<br>';


// Verify if an User has Access to a Operation
$taskId = 1;
$operationId = 1;
$userId = 1;
echo 'USER #' . $userId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->user()->hasAccessToOperation($taskId, $operationId, $userId)) . '<br>';

$taskId = 1;
$operationId = 3;
$userId = 2;
echo 'USER #' . $userId . ' HAS OPERATION: #' . $operationId;
var_dump($authorization->user()->hasAccessToOperation($taskId, $operationId, $userId)) . '<br>';
?>