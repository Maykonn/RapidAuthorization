<?php

/**
 * Working with Roles - Basic
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';
$configuration = require_once __DIR__ . '/config/config.php';
$authorization = new \RapidAuthorization\RapidAuthorization($configuration);

// Create
echo 'CREATED ROLEs: #';
echo $authorization->role()->create('Administrator', null, 'Optional description of Role') . ' - ';
echo $authorization->role()->create('Tester', 'TESTER', 'QA Tester') . ' - ';
echo $idToDelete = $authorization->role()->create('Testers', 'tester', 'Will be deleted');
echo ' - ';
echo $idToUpdate = $authorization->role()->create('Seller', null, 'Will be updated to NULL');
echo '<br>';


// Update - if set NULL in description param, the description value will not change
echo 'UPDATED ROLE: #';
echo $idFromUpdateRole = $authorization->role()->update($idToUpdate, 'Senior Sellers', 'Senior Seller', '') . '<br>';


// Delete
echo 'DELETED ROLE: #';
echo $idFromDeletedRole = $authorization->role()->delete($idToDelete);
echo '<br>';


// List By ID
echo 'LISTING ROLE : #' . $idFromUpdateRole . '<pre>';
$listByID = $authorization->role()->findById($idFromUpdateRole);
print_r($listByID);
echo '</pre><br>';


// List By Name
echo 'LISTING ROLE WITH NAME: Senior Seller<pre>';
$listByName = $authorization->role()->findByName('Senior Seller');
print_r($listByName);
echo '</pre><br>';


// List All
echo 'LISTING ALL ROLEs : <pre>';
$allRoles = $authorization->role()->findAll();
print_r($allRoles);
echo '</pre><br>';
