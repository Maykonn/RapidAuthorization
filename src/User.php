<?php

/**
 * User
 * É composta pela classe "User" do domínio da aplicação cliente.
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\ParameterType;
use \PDO;

class User extends Entity
{
    public function getRoles($userId)
    {
        if (User::instance($this->preferences, $this->db)->findById($userId)) {
            $this->id = (int) $userId;

            return $this->queryBuilder
                ->select('rol.*')
                ->from('rpd_role', 'rol')
                ->rightJoin('rol', 'rpd_user_has_role', 'usr', 'rol.id = usr.id_role')
                ->where('usr.id_user = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll();
        }

        return Array();
    }

    public function getTasks($userId)
    {
        if (User::instance($this->preferences, $this->db)->findById($userId)) {
            $this->id = (int) $userId;

            return $this->queryBuilder
                ->select('DISTINCT t.id, t.name, t.description')
                ->from('rpd_task', 't')
                ->leftJoin('t', 'rpd_role_has_task', 'rht', 't.id = rht.id_task')
                ->leftJoin('rht', 'rpd_user_has_role', 'uhr', 'rht.id_role = uhr.id_role')
                ->where('uhr.id_user = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll();
        }

        return Array();
    }

    public function getOperations($userId)
    {
        if (User::instance($this->preferences, $this->db)->findById($userId)) {
            $sql = "
                SELECT DISTINCT o.id, o.name, o.description
                FROM rpd_operation o
				LEFT JOIN rpd_task_has_operation tho ON o.id = tho.id_operation
                LEFT JOIN rpd_role_has_task rht ON tho.id_task = rht.id_task
                LEFT JOIN rpd_user_has_role uhr ON rht.id_role = uhr.id_role
                WHERE uhr.id_user = :idUser";

            $stmt = $this->db->prepare($sql);
            $this->id = (int) $userId;
            $stmt->bindParam(':idUser', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }

        return Array();
    }

    public function attachRole($roleId, $userId)
    {
        if ($this->isPossibleToAttachTheRole($roleId, $userId)) {
            $sql = "INSERT INTO rpd_user_has_role(id_user, id_role) VALUES (:idUser, :idRole)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idUser', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':idRole', $roleId, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

    private function isPossibleToAttachTheRole($roleId, $userId)
    {
        return (
            Role::instance($this->preferences, $this->db)->findById($roleId) &&
            ! User::instance($this->preferences, $this->db)->hasPermissionsOfTheRole($roleId, $userId)
        );
    }

    public function findById($userId)
    {
        return $this->queryBuilder
            ->select('*')
            ->from($this->preferencesList->userTable)
            ->where($this->preferencesList->userTablePK . " = ?")
            ->setParameter(0, $userId, ParameterType::INTEGER)
            ->execute()
            ->fetch();
    }

    public function findAll()
    {
        return $this->queryBuilder
            ->select($this->preferencesList->userTablePK)
            ->from($this->preferencesList->userTable)
            ->execute()
            ->fetchAll();
    }

    public function hasPermissionsOfTheRole($roleId, $userId)
    {
        if (
            Role::instance($this->preferences, $this->db)->findById($roleId) &&
            User::instance($this->preferences, $this->db)->findById($userId)
        ) {
            $this->id = (int) $userId;

            $stmt = $this->queryBuilder
                ->select('id')
                ->from('rpd_user_has_role')
                ->where('id_user = ?')
                ->andWhere('id_role = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->setParameter(1, $roleId, ParameterType::INTEGER)
                ->execute();

            return ($stmt->fetch() ? true : false);
        }

        return false;
    }

    public function hasAccessToTask($taskId, $userId)
    {
        if (
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            User::instance($this->preferences, $this->db)->findById($userId)
        ) {
            $rolesThatHasAccessToTask = Task::instance($this->preferences, $this->db)->getRolesThatHasAccess($taskId);
            foreach ($rolesThatHasAccessToTask as $role) {
                if ($this->hasPermissionsOfTheRole($role['id_role'], $userId)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasAccessToOperation($taskId, $operationId, $userId)
    {
        if (
            User::instance($this->preferences, $this->db)->hasAccessToTask($taskId, $userId) &&
            Task::instance($this->preferences, $this->db)->hasOperation($operationId, $taskId)
        ) {
            $tasksThatCanExecuteTheOperation = Operation::instance($this->preferences, $this->db)->getTasksThatCanExecute($operationId);
            foreach ($tasksThatCanExecuteTheOperation as $task) {
                if ($this->hasAccessToTask($task['id_task'], $userId)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function removeUserFromRole($userId, $roleId)
    {
        if (
            User::instance($this->preferences, $this->db)->findById($userId) &&
            Role::instance($this->preferences, $this->db)->findById($roleId)
        ) {
            $sql = "DELETE FROM rpd_user_has_role WHERE id_user = :userId AND id_role = :roleId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

}
