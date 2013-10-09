<?php

/**
 * Working with Operations - Advanced
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

// List all Tasks that can execute an Operation
$operationId = 1;
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' .$operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 2; // in default examples not exist
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' .$operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 3;
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' .$operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 4;
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' .$operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';