<?php

/**
 * Working with Tasks - Advanced_02
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
$configuration = require_once __DIR__ . '/config/config.php';
$authorization = new \RapidAuthorization\RapidAuthorization($configuration);

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