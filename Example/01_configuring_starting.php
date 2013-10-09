<?php

/**
 * Configuring and starting
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */
$sep = DIRECTORY_SEPARATOR;
require_once '..' . $sep . 'RapidAuthorization.php';

use RapidAuthorization\RapidAuthorization;

// All available params
$configuration = Array(
    'mysqlHost' => 'localhost',
    'mysqlPort' => 3306,
    'mysqlUser' => 'root',
    'mysqlPass' => '',
    'dbName' => 'rapid_authorization',
    'dbCharset' => 'utf8', // optional param, default is utf8
    'userTable' => 'user_table', // optional param if is different of user, default is user
    'userTablePK' => 'user_pk', // optional param if is different of id, default is id
    'autoGenerateTables' => true, // optional param, default is false
    'useRapidAuthorizationAutoload' => true // optional param, default is false
);

$authorization = new RapidAuthorization($configuration);

echo '
    OK!!!<br/>
    NOW YOU MUST POPULATE THE ' . $configuration['userTable'] . ' TABLE WITH THE PK 1 AND 2,
    FOR THE EXAMPLES WORK.';
?>