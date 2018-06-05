<?php

/**
 * Working with Operations - Advanced
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
$configuration = require_once __DIR__ . '/config/config.php';
$authorization = new \RapidAuthorization\RapidAuthorization($configuration);

// Creating Operation that don't need Authorization
echo "Creating Operation that don't need Authorization: #";
echo $operationThatDontNeedAuthorization = $authorization->operation()->create('Login', 'login',
    "Don't needs Authorization", false);
echo '<br/><br/>';


// Verify if Operation needs Autorization to be executed
$operationId = 1;
echo "OPERATION #" . $operationId . " needs Authorization? ";
var_dump($authorization->operation()->needsAuthorization($operationId)) . '<br/>';

$operationId = $operationThatDontNeedAuthorization;
echo "OPERATION #" . $operationId . " needs Authorization? ";
var_dump($authorization->operation()->needsAuthorization($operationId)) . '<br/>';

// List all Tasks that can execute an Operation
$operationId = 1;
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' . $operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 2; // in default examples not exist
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' . $operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 3;
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' . $operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 4;
echo 'ALL TASKs THAT CAN EXECUTE OPERATION #' . $operationId . '<pre>';
$tasksThaCanExecute = $authorization->operation()->getTasksThatCanExecute($operationId);
print_r($tasksThaCanExecute);
echo '</pre>';

$operationId = 4;
echo 'DETACH OPERATION #' . $operationId . ' FROM FIRST TASK THAT CAN ACCESS THE OPERATION: ';
var_dump($authorization->operation()->removeOperationFromTask($operationId,
    $tasksThaCanExecute[0]['id_task']));


// Require/Not Require Authorization
echo 'ALL OPERATIONS THAT REQUIRE AUTHORIZATION VERIFICATION:<pre>';
$operations = $authorization->operation()->findByRequireAuthorization();
print_r($operations);
echo '</pre>';

echo 'ALL OPERATIONS THAT NOT REQUIRE AUTHORIZATION VERIFICATION:<pre>';
$operations = $authorization->operation()->findByNotRequireAuthorization();
print_r($operations);
echo '</pre>';
