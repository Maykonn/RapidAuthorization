<?php

/**
 * Working with Operations - Basic
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
require_once dirname(__DIR__) . '/vendor/autoload.php';
$configuration = require_once __DIR__ . '/config/config.php';
$authorization = new \RapidAuthorization\RapidAuthorization($configuration);

// Create
echo 'CREATED OPERATIONs: #';
echo $authorization->operation()->create('Add New Product', 'AddNewProduct', 'Optional description of Operation') . ' - ';
echo $idToDelete = $authorization->operation()->create('View Sales Report', 'ViewSalesReport', 'Will be deleted') . ' - ';
echo $idToUpdate = $authorization->operation()->create('Open New Order', 'OpenNewOrder', 'Will be updated to NULL') . ' - ';
echo $authorization->operation()->create('Close Order') . '<br>';


// Update - if set NULL in description param, the description value will not change
echo 'UPDATED OPERATION: #';
echo $idFromUpdateOperation = $authorization->operation()->update($idToUpdate, 'Add New Order', 'AddOrder', '') . '<br>';


// Delete
echo 'DELETED OPERATION: #';
echo $idFromDeletedOperation = $authorization->operation()->delete($idToDelete) . '<br>';


// List By ID
echo 'LISTING OPERATION : #' . $idFromUpdateOperation . '<pre>';
$listByID = $authorization->operation()->findById($idFromUpdateOperation);
print_r($listByID);
echo '</pre><br>';


// List By Name
echo 'LISTING OPERATION WITH NAME: Add New Product<pre>';
$listByName = $authorization->operation()->findByName('AddNewProduct');
print_r($listByName);
echo '</pre><br>';


// List All
echo 'LISTING ALL OPERATIONs : <pre>';
$allOperations = $authorization->operation()->findAll();
print_r($allOperations);
echo '</pre><br>';
?>
