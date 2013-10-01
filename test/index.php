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
require_once 'client' . $sep . 'ClientUser.php';

use Rapid\Authorization\RapidAuthorization;

$authorizationConf = Array(
    'useRapidAuthorizationAutoload' => true,
    'userClassInstance' => new ClientUser(),
    'mysqlPort' => 3306,
    'mysqlHost' => 'localhost',
    'mysqlUser' => '',
    'mysqlPass' => '',
    'dbName' => 'rapid_authorization',
    'dbCharset' => 'utf8'
);

$authorization = new RapidAuthorization($authorizationConf);
?>