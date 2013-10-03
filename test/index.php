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

// TODO: mover todos esses testes para um objeto de testes
//
//
// Exemplos de criar, editar e apagar um Role
//$roleId = $authorization->role()->create('Aux. Atendimento', 'descrição opcional');
//$roleId = $authorization->role()->update($roleId, 'Atendente', "(opcional), informe '' para limpar a descrição");
//$deleted = $authorization->role()->delete($roleId);
//
//
// Exemplo atributir Role a um User
//$authorization->user()->attachRole(4, 1);
//
//
// Exemplo de como obter a listagem de Roles de um User
//$roles = $authorization->user()->getRoles(1, PDO::FETCH_OBJ);
//$roles = $authorization->user()->getRoles(1);
//var_dump($roles);
//
//
// Exemplos de criar, editar e apagar uma Task
//$taskId = $authorization->task()->create('Gerenciar Clientes', 'descrição opcional');
//$authorization->task()->update($taskId, 'editado', '');
//$authorization->task()->delete($taskId);
?>