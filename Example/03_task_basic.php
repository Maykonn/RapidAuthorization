<?php

/**
 * Working with Tasks - Basic
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
    'useRapidAuthorizationAutoload' => true
);

$authorization = new RapidAuthorization($configuration);

// Create
echo 'CREATED TASKs: #';
echo $authorization->task()->create('Manage Orders', 'ManageOrders', 'Optional description of Task') . ' - ';
echo $idToDelete = $authorization->task()->create('Manage Suppliers', 'ManageSuppliers', 'Will be deleted') . ' - ';
echo $idToUpdate = $authorization->task()->create('Manage Products', 'ManageProducts', 'Will be updated to NULL') . '<br>';


// Update - if set NULL in description param, the description value will not change
echo 'UPDATED TASK: #';
echo $idFromUpdateTask = $authorization->task()->update($idToUpdate, 'Manage Products', 'MANAGE_PRODUCTS', '') . '<br>';


// Delete
echo 'DELETED TASK: #';
echo $idFromDeletedTask = $authorization->task()->delete($idToDelete) . '<br>';


// List By ID
echo 'LISTING TASK : #' . $idFromUpdateTask . '<pre>';
$listByID = $authorization->task()->findById($idFromUpdateTask);
print_r($listByID);
echo '</pre><br>';


// List By Name
echo 'LISTING TASK WITH NAME: Manage Orders<pre>';
$listByName = $authorization->task()->findByName('ManageOrders');
print_r($listByName);
echo '</pre><br>';


// List All
echo 'LISTING ALL TASKs : <pre>';
$allTasks = $authorization->task()->findAll();
print_r($allTasks);
echo '</pre><br>';
?>
