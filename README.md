RapidAuthorization
==================

RapidAuthorization é um framework para controle de acesso, escrito em PHP, baseado em *Role-based Access Control (RBAC).

Veja sobre RBAC na Wikipédia visitando: https://en.wikipedia.org/wiki/Role-based_access_control


É possível utilizar o RapidAuthorization em qualquer sistema escrito em PHP. Com ele é rápido e fácil
escrever o controle de acesso da sua aplicação. Você precisa apenas integrar à aplicação, veja os exemplos
em /Example.

Se o domínio/responsabilidade/etc da sua aplicação não é controle de acesso, então ela não deve saber como fazer isso.
Essa responsabilidade deve ser dada a outro sistema, um serviço, no caso o RapidAuthorization, para que ele
se preocupe com o controle de acesso, não a sua aplicação.

O objetivo é desacoplar, o máximo possível, o controle de acesso do domínio das aplicações que o utilizarem.
Sua aplicação já possui responsabilidades suficientes, deixe o controle de acesso com o RapidAuthorization.


RBAC
==================

Entidades: User, Role, Task e Operation

User é um usuário da aplicação cliente.
Possui permissão de nenhum, um ou muitos Roles.

Role é um papel/cargo ocupado por um User na aplicação cliente.
Pode ter acesso a nenhuma, uma, ou muitas Tasks.

Task é uma tarefa realizada pela aplicação cliente, Gerenciar Clientes é um exemplo.
Pode ter acesso a nenhuma, uma ou muitas Operations.

Operations são operações/ações realizadas pelas Tasks da aplicação cliente, Cadastrar Cliente é um exemplo.


Observações
==================

Para instalar, basta colocar o conteúdo baixado na pasta lib/RapidAuthorization por exemplo, da sua aplicação
e então dar uma olhada nos exemplos da pasta /Example que você vai saber o que fazer.

Alguns programadores gostam de considerar uma Task como um Controller,
por exemplo ClienteController, e uma Operation como uma ação do Controller, por exemplo
actionCreate ou createAction.

É utilizado uma simples comunicação com o banco de dados utilizando PDO. Você não deve utilizar as
classes existentes em /Datase em sua aplicação. Também fornecemos um simples autoload que funciona
bem com o RapidAuthorization, ainda estamos testando com outros autoloads. Por enquanto utilize
o fornecido.

É suportado apenas por versões do PHP que permitam namespaces, autoload e PDO.
Consideramos uma prática inaceitável, feia e sem motivos para tal, utilizar versões antigas
do PHP em novas aplicações.


Banco de dados
==================

Nas configurações você informa os dados de conexão do banco de dados e então, por padrão, o Rapid
Authorization cria a estrutura de, caso não existam, sete tabelas e seus relacionamentos:

user, rpd_user_has_role, rpd_role, rpd_role_has_task, rpd_task, rpd_task_has_operation e rpd_operation

O termo rpd_ no início dos nomes de tabelas é uma tentativa de organizá-las e separá-las das tabelas
da aplicação cliente. Apenas a tabela *user não começa com rpd_ pois não é de responsabilidade do
RapidAuthorization, mas da aplicação cliente.


ATENÇÃO: Você pode informar o nome da tabela de Users e de sua chave primária, nas configurações, caso
não informe, será criada um tabela com o nome user.


*A tabela de Users são todos os usuários da aplicação cliente que deseja-se manter controle de acesso.


Funcionalidades
==================

Configuração:

    1) Permite ligar ou desligar a geração automática de tabelas (só cria as tabelas caso não existam).
    2) Permite utilizar qualquer nome para a tabela de Users e para sua chave primária.
    3) Permite gerar as tabelas com qualquer encoding (utf8, latin1, etc).
    4) Permite configurar se usará o próprio autoload ou o fornecido pela aplicação cliente.

User:

    1) Listar um User (findById).
    2) Listar todos os Users (findAll).
    3) Anexar um Role a um User (attachRole).
    4) Listar todos os Roles anexados a um User (getRoles).
    5) Listar todas as Tasks que um User tem acesso (getTasks).
    6) Listar todas as Operations que um User tem acesso (getOperations).
    7) Verificar se User tem permissões de um Role (hasPermissionsOfTheRole).
    8) Verificar se User possui acesso a determinada Task (hasAccessToTask).
    9) Verificar se User possui acesso a determinada Operation (hasAccessToOperation).
    10) Remover User de um Role (removeUserFromRole).

Role:

    1) Criar um Role (create).
    2) Editar um Role (update).
    3) Apagar um Role (delete).
    4) Listar um Role (findById, findByName).
    5) Listar todos os Roles (findAll).
    6) Anexar uma Task a um Role (attachTask).
    7) Listar todas as Tasks que um Role tem acesso (getTasks).
    8) Listar todas as Operations que um Role tem acesso (getOperations).
    9) Verificar se possui acesso a determinada Task (hasAccessToTask).
    10) Verificar se possui acesso a determinada Operation (hasAccessToOperation).
    11) Listar todos os Users que possuem permissão a um Role (getUsersThatHasPermission).
    12) Remover todos os Users de um Role (removeUsersFromRole).

Task:

    1) Criar uma Task (create).
    2) Editar uma Task (update).
    3) Apagar uma Task (delete).
    4) Listar uma Task (findById, findByName).
    5) Listar todas as Tasks (findAll).
    6) Anexar uma Operation a uma Task (attachOperation).
    7) Listar todas as Operations anexadas a uma Task (getOperations).
    8) Verificar se possui determinada Operation (hasOperation).
    9) Listar todos os Roles que possuem acesso a uma Task (getRolesThatHasAccess).
    10) Remover Task de um Role (removeTaskFromRole).

Operation:

    1) Criar uma Operation (create).
    2) Editar uma Operation (update).
    3) Apagar uma Operation (delete).
    4) Listar uma Operation (findById, findByName).
    5) Listar todas as Operations (findAll).
    6) Listar todas as Tasks que possuem uma Operation (getTasksThatCanExecute).
    7) Listar Operations que requerem Autorização (findByRequireAuthorization).
    8) *Listar Operations que não requerem Autorização (findByNotRequireAuthorization).

* Não requerer Autorização significa que todos os Users possuem acesso em qualquer ponto do tempo a uma Operation