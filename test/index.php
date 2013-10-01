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
$roleId = $authorization->createRole('atendente');

//var_dump($roleId);
//$roleId = $authorization->updateRole('2', 'programador');
//var_dump($roleId);
?>