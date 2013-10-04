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
// Exemplo listar todos Roles
//$roles = $authorization->role()->findAll();
//
// Exemplo listar todas as Tasks que um Role tem acesso
//$tasks = $authorization->role()->getTasks($roleId);
//
// Exemplo listar todas as Operations que um Role tem acesso
//$operations = $authorization->role()->getOperations(1);
//
// Exemplo verificar se um Role possui acesso a uma Task
//$hasAccess = $authorization->role()->hasAccessToTask(5, 1);
//
// Exemplo verificar se um Role possui acesso a uma Operation
//$hasAccess = $authorization->role()->hasAccessToOperation($operationId, $roleId);
//
//
// Exemplo atributir Role a um User
//$authorization->user()->attachRole(4, 1);
//
// Exemplo listar todos Users
//$users = $authorization->user()->findAll();
//
// Exemplo verificar se um User possui permissões de um Role
//$hasPermission = $authorization->user()->hasPermissionsOfTheRole($roleId, $userId);
//
// Exemplo verificar se um User possui acesso a uma Task
//$hasAccess = $authorization->user()->hasAccessToTask($taskId, $userId);
//
// Exemplo verificar se um User possui acesso a uma Operation
//$hasAccess = $authorization->user()->hasAccessToOperation($operationId, $userId);
//
// Exemplo de como obter a listagem de Roles de um User
//$roles = $authorization->user()->getRoles(1, PDO::FETCH_OBJ);
//$roles = $authorization->user()->getRoles(1);
//
//
// Exemplos de criar, editar e apagar uma Task
//$taskId = $authorization->task()->create('Gerenciar Atendimento', 'descrição opcional');
//$authorization->task()->update($taskId, 'editado', '');
//$authorization->task()->delete($taskId);
//
// Exemplo atribuir Task a um Role
//$authorization->role()->attachTask($taskId, $roleId);
//
// Exemplo listar todas Tasks
//$tasks = $authorization->task()->findAll();
//
//
// Exemplos de criar, editar e apagar uma Operation
//$operationId = $authorization->operation()->create('Iniciar Atendimento', 'descrição opcional');
//$operationId = $authorization->operation()->update($operationId, 'Iniciar Atendimento', "(opcional), informe '' para limpar a descrição");
//$deleted = $authorization->operation()->delete($operationId);
//
//
// Exemplo atribuir Operation a uma Task
//$authorization->task()->attachOperation($operationId, $taskId);
?>