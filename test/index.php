<?php

/**
 * Teste de RapidAuthorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 *
 * Caso sua aplicação possua seu próprio autoload você deve ignorar essa parte.
 * Caso contrário
 */
$sep = DIRECTORY_SEPARATOR;
require_once '..' . $sep . 'index.php';

use Rapid\Authorization\RapidAuthorization;

$authorizationConf = Array(
    'useRapidAuthorizationAutoload' => true,
    'mysqlPort' => 3306,
    'mysqlHost' => 'localhost',
    'mysqlUser' => 'root',
    'mysqlPass' => '',
    'dbName' => 'rapid_authorization',
    //'dbCharset' => 'utf8'
    //'autoGenerateTables' => true
);

$authorization = new RapidAuthorization($authorizationConf);

// Exemplos de criar, editar e apagar roles
//$roleId = $authorization->createRole('admin');
//$roleId = $authorization->updateRole($roleId, 'nome do role');
//$deleted = $authorization->deleteRole(6);
$roleId = 2;
$userId = 1; // maykonn
$authorization->attachRoleInUser($roleId, $userId);
?>