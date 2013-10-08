<?php

/**
 * Working with Operations - Basic
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
$sep = DIRECTORY_SEPARATOR;
require_once '..' . $sep . 'RapidAuthorization.php';

use Rapid\Authorization\RapidAuthorization;

$configuration = Array(
    'mysqlHost' => 'localhost',
    'mysqlPort' => 3306,
    'mysqlUser' => 'root',
    'mysqlPass' => '',
    'dbName' => 'rapid_authorization',
    'userTable' => 'user_table',
    'userTablePK' => 'user_pk',
    'useRapidAuthorizationAutoload' => true
);


// Create
$authorization = new RapidAuthorization($configuration);
echo 'CREATED OPERATIONs: #';
echo $authorization->operation()->create('Add New Product', 'Optional description of Operation') . ' - ';
echo $idToDelete = $authorization->operation()->create('View Sales Report', 'Will be deleted') . ' - ';
echo $idToUpdate = $authorization->operation()->create('Open New Order', 'Will be updated to NULL') . '<br>';


// Update - if set NULL in description param, the description value will not change
echo 'UPDATED OPERATION: #';
echo $idFromUpdateOperation = $authorization->operation()->update($idToUpdate, 'Add New Order', '') . '<br>';


// Delete
echo 'DELETED OPERATION: #';
echo $idFromDeletedOperation = $authorization->operation()->delete($idToDelete) . '<br>';


// List By ID
echo 'LISTING OPERATION : #' . $idFromUpdateOperation . '<pre>';
$listByID = $authorization->operation()->findById($idFromUpdateOperation);
print_r($listByID);
echo '</pre><br>';


// List All
echo 'LISTING ALL OPERATIONs : <pre>';
$allOperations = $authorization->operation()->findAll();
print_r($allOperations);
echo '</pre><br>';
?>
