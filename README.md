Rapid Authorization
==================

Rapid Authorization é um sistema de autorização, escrito em PHP, baseado em *Roled-based Access Control (RBAC).

Veja sobre RBAC na Wikipédia visitando: https://en.wikipedia.org/wiki/Role-based_access_control


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

Alguns programadores gostam de considerar uma Task como um Controller,
por exemplo ClienteController, e uma Operation como uma ação do Controller, por exemplo
actionCreate ou createAction.


Banco de dados
==================

Nas configurações você informa os dados de conexão do banco de dados e então, por padrão, o Rapid
Authorization cria a estrutura de, caso não existam, sete tabelas e seus relacionamentos:

user, rpd_user_has_role, rpd_role, rpd_role_has_task, rpd_task, rpd_task_has_operation e rpd_operation

O termo rpd_ no início dos nomes de tabelas é uma tentativa de organizá-las e separá-las das tabelas
da aplicação cliente. Apenas a tabela *user não começa com rpd_ pois não é de responsabilidade do
Rapid Authorization, mas da aplicação cliente.


ATENÇÃO: Você pode informar o nome da tabela de Users e de sua chave primária, nas configurações, caso
não informe, será criada um tabela com o nome user.


*A tabela de Users são todos os usuários da aplicação cliente que deseja-se manter controle de acesso.


Funcionalidades
==================

Configuração:

    1) Permite ligar ou desligar a geração automática de tabelas (só cria as tabelas caso não existam).
    2) DEV: Permite utilizar qualquer nome para a tabela de Users e para sua chave primária.
    3) Permite configurar se usará o próprio autoload ou fornecido pela aplicação cliente.

User:

    1) Listar um User (findById).
    2) Listar todos os Users (findAll).
    3) Anexar um Role a um User (attachRole).
    4) Listar todos os Roles anexados a um User (getRoles).
    5) Verificar se User tem permissões de um Role (hasPermissionsOfTheRole).
    6) Verificar se User possui acesso a determinada Task (hasAccessToTask).
    7) Verificar se User possui acesso a determinada Operation (hasAccessToOperation).

Role:

    1) Criar um Role (create).
    2) Editar um Role (update).
    3) Apagar um Role (delete).
    4) Listar um Role (findById).
    5) Listar todos os Roles (findAll).
    6) Anexar uma Task a um Role (attachTask).
    7) Listar todas as Tasks anexadas a um Role (getTasks).
    8) Verificar se possui acesso a determinada Task (hasAccessToTask).
    9) DEV: Verificar se possui acesso a determinada Operation (hasAccessToOperation).

Task:

    1) Criar uma Task (create).
    2) Editar uma Task (update).
    3) Apagar uma Task (delete).
    4) Listar uma Task (findById).
    5) DEV: Listar todas as Tasks (findAll).
    6) Anexar uma Operation a uma Task (attachOperation).
    7) DEV: Listar todas as Operations anexadas a uma Task (getOperations).
    8) DEV: Verificar se possui determinada Operation (hasOperation).
    9) Listar todos os Roles que possuem acesso a uma Task (getRolesThatHasAccess).

Operation:

    1) Criar uma Operation (create).
    2) Editar uma Operation (update).
    3) Apagar uma Operation (delete).
    4) Listar uma Operation (findById).
    5) DEV: Listar todas as Operations (findAll).
    6) Listar todas as Tasks que possuem uma Operation (getTasksThatCanExecute).