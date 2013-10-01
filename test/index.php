<?php

/**
 * Teste de RapidAuthorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 *
 * Caso sua aplicação possua seu próprio autoload você deve ignorar essa parte.
 * Caso contrário
 */
$sep = DIRECTORY_SEPARATOR;
require_once '..' . $sep . 'src' . $sep . 'index.php';
require_once 'client' . $sep . 'ClientUser.php';

use Rapid\Authorization\RapidAuthorization;

$authorizationConf = Array(
    'useRapidAuthorizationAutoload' => true,
    'userClassInstance' => new ClientUser(),
    'mysqlHost' => 'localhost',
    'mysqlUser' => '',
    'mysqlPass' => '',
    'dbName' => 'camerahot',
    'dbCharset' => 'utf8'
);

$authorization = new RapidAuthorization($authorizationConf);
?>