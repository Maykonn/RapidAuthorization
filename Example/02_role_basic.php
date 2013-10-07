<?php

/**
 * Working with Roles - Basic
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
    'useRapidAuthorizationAutoload' => true
);


// Create
$authorization = new RapidAuthorization($configuration);
echo 'CREATED ROLEs: #';
echo $authorization->role()->create('Administrator', 'Optional description of Role') . ' - ';
echo $idToDelete = $authorization->role()->create('Tester', 'Will be deleted') . ' - ';
echo $idToUpdate = $authorization->role()->create('Seller', 'Will be updated to NULL') . '<br>';


// Update - if set NULL in description param, the description value will not change
echo 'UPDATED ROLE: #';
echo $idFromUpdateRole = $authorization->role()->update($idToUpdate, 'Senior Seller', '') . '<br>';


// Delete
echo 'DELETED ROLE: #';
echo $idFromDeletedRole = $authorization->role()->delete($idToDelete) . '<br>';


// List By ID
echo 'LISTING ROLE : #' . $idFromUpdateRole . '<pre>';
$listByID = $authorization->role()->findById($idFromUpdateRole);
print_r($listByID);
echo '</pre><br>';


// List All
echo 'LISTING ALL ROLEs : <pre>';
$allRoles = $authorization->role()->findAll();
print_r($allRoles);
echo '</pre><br>';
?>
