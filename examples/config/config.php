<?php

return Array(
	'mysqlHost' => 'localhost',
	'mysqlPort' => 3306,
	'mysqlUser' => 'root',
	'mysqlPass' => '123456',
	'dbName' => 'test_rbac',
	'dbCharset' => 'utf8',
	// optional param, default is utf8
	'userTable' => 'users',
	// optional param if is different of user, default is user
	'userTablePK' => 'id',
	// optional param if is different of id, default is id
	'autoGenerateTables' => true,
	// optional param, default is false
	//'useRapidAuthorizationAutoload' => true // optional param, default is false, set true when you're not using composer autoload
);