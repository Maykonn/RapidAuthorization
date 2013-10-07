<?php

/**
 * Configuring and starting RapidAuthorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
$sep = DIRECTORY_SEPARATOR;
require_once '..' . $sep . 'RapidAuthorization.php';

// All available params
$configuration = Array(
    'mysqlHost' => 'localhost',
    'mysqlPort' => 3306,
    'mysqlUser' => 'root',
    'mysqlPass' => '',
    'dbName' => 'rapid_authorization',
    'dbCharset' => 'latin1', // optional param, default is utf8
    'autoGenerateTables' => false, // optional param, default is true
    'userTable' => 'user_table', // optional param, default is user
    'userTablePK' => 'user_pk', // optional param, default is id
    'useRapidAuthorizationAutoload' => true // optional param, default is false
);

// Instantiation
$authorization = new Rapid\Authorization\RapidAuthorization($configuration);

// or instatiate with use
//use Rapid\Authorization\RapidAuthorization;
//$authorization = new RapidAuthorization($configuration);
?>