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

// Exemplos de criar, editar e apagar um Role
//$roleId = $authorization->roleCreate('admin', 'descrição opicional');
//$roleId = $authorization->roleUpdate(1, 'atendente', "descrição opicional, informe '' para limpar a descrição");
//$deleted = $authorization->roleDelete(6);
//
//
// Exemplo atributir Role a um User
//$authorization->userAttachRole(2, 1);
//
//
// Exemplo de como obter a listagem de Roles de um User
//$roles = $authorization->userGetRoles(1, PDO::FETCH_OBJ);
//$roles = $authorization->userGetRoles(1);
//var_dump($roles);
?>