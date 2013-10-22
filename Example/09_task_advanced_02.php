<?php

/**
 * Working with Tasks - Advanced_02
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