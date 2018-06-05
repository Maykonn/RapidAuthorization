<?php

return Array(
    'dbDriver' => 'pdo_mysql',
    // put in an ENUM
    'dbHost' => 'localhost',
    'dbPort' => 3306,
    'dbUser' => 'root',
    'dbPassword' => '123456',
    'dbName' => 'test_rbac',
    'dbConnCharset' => 'utf8',
    // optional param, default is utf8
    'userTable' => 'users',
    // optional param if is different of user, default is user
    'userTablePK' => 'id',
    // optional param if is different of id, default is id
    'autoGenerateTables' => true,
    // optional param to set a default exception handler, use a callable
    'exceptionHandler' => function(Exception $e) {
        echo '<pre>';
        echo '<b>' . $e->getMessage() . '</b><br/><br/>';
        echo $e->getTraceAsString();
        echo '</pre>';
    }
    // optional param, default is false
    //'useRapidAuthorizationAutoload' => true // optional param, default is false, set true when you're not using composer autoload
);
