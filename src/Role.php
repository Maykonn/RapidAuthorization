<?php

/**
 * Role
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\ParameterType;

class Role extends Entity
{
    public function delete($id)
    {
        if ($this->findById($id)) {
            $this->id = (int) $id;

            return $this->queryBuilder
                ->resetQueryParts()
                ->delete('rpd_role')
                ->where('id = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute();
        }

        return false;
    }

    public function attachTask($taskId, $roleId)
    {
        if ($this->isPossibleToAttachTheTask($taskId, $roleId)) {

            return $this->queryBuilder
                ->resetQueryParts()
                ->insert('rpd_role_has_task')
                ->values(array('id_role' => '?', 'id_task' => '?'))
                ->setParameter(0, $roleId, ParameterType::INTEGER)
                ->setParameter(1, $taskId, ParameterType::INTEGER)
                ->execute();
        }

        return false;
    }

    private function isPossibleToAttachTheTask($taskId, $roleId)
    {
        $Role = Role::instance($this->preferences, $this->db);

        return (
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            $Role->findById($roleId) &&
            ! $Role->hasAccessToTask($taskId, $roleId)
        );
    }

    public function findById($roleId)
    {
        return $this->queryBuilder
            ->resetQueryParts()
            ->select('id', 'name', 'business_name', 'description')
            ->from('rpd_role')
            ->where('id = ?')
            ->setParameter(0, $roleId, ParameterType::INTEGER)
            ->execute()
            ->fetch();
    }

    public function findByName($name)
    {
        return $this->queryBuilder
            ->resetQueryParts()
            ->select('id', 'name', 'business_name', 'description')
            ->from('rpd_role')
            ->where('name = ?')
            ->setParameter(0, $name, ParameterType::STRING)
            ->execute()
            ->fetch();
    }

    public function findAll()
    {
        return $this->queryBuilder
            ->resetQueryParts()
            ->select('id', 'name', 'business_name', 'description')
            ->from('rpd_role')
            ->execute()
            ->fetchAll();
    }

    public function save()
    {
        $sql = "
            INSERT INTO rpd_role(
                id, name, business_name, description
            ) VALUES (
                :id, :name, :businessName, :description
            ) ON DUPLICATE KEY UPDATE name = :name, business_name = :businessName, description = :description";

        return $this->saveFromSQL($sql);
    }

    public function getTasks($roleId)
    {
        if (Role::instance($this->preferences, $this->db)->findById($roleId)) {
            $this->id = (int) $roleId;

            return $this->queryBuilder
                ->resetQueryParts()
                ->select('t.id', 't.name', 't.business_name', 't.description')
                ->from('rpd_task', 't')
                ->innerJoin('t', 'rpd_role_has_task', 'rht', 't.id = rht.id_task')
                ->where('rht.id_role = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll();
        }

        return Array();
    }

    public function hasAccessToTask($taskId, $roleId)
    {
        $this->id = (int) $roleId;
        $stmt = $this->queryBuilder
            ->resetQueryParts()
            ->select('id')
            ->from('rpd_role_has_task')
            ->where('id_role = ?')
            ->andWhere('id_task = ?')
            ->setParameter(0, $this->id, ParameterType::INTEGER)
            ->setParameter(1, $taskId, ParameterType::INTEGER)
            ->execute();

        return ($stmt->fetch() ? true : false);
    }

    public function getOperations($roleId)
    {
        if (Role::instance($this->preferences, $this->db)->findById($roleId)) {
            $this->id = (int) $roleId;

            return $this->queryBuilder
                ->resetQueryParts()
                ->select('o.id', 'o.name', 'o.business_name', 'o.description')
                ->from('rpd_operation', 'o')
                ->leftJoin('o', 'rpd_task_has_operation', 'tho', 'o.id = tho.id_operation')
                ->leftJoin('tho', 'rpd_role_has_task', 'rht', 'tho.id_task = rht.id_task')
                ->where('rht.id_role = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll();
        }

        return Array();
    }

    public function hasAccessToOperation($operationId, $roleId)
    {
        if (
            Operation::instance($this->preferences, $this->db)->findById($operationId) &&
            Role::instance($this->preferences, $this->db)->findById($roleId)
        ) {
            $tasksThatCanExecuteTheOperation = Operation::instance($this->preferences, $this->db)->getTasksThatCanExecute($operationId);
            foreach ($tasksThatCanExecuteTheOperation as $task) {
                if ($this->hasAccessToTask($task['id_task'], $roleId)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getUsersThatHasPermission($roleId)
    {
        if (Role::instance($this->preferences, $this->db)->findById($roleId)) {
            $this->id = (int) $roleId;

            return $this->queryBuilder
                ->resetQueryParts()
                ->select('id_user')
                ->from('rpd_user_has_role')
                ->where('id_role = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll(FetchMode::COLUMN);
        }

        return Array();
    }

    public function removeUsersFromRole($roleId)
    {
        if (Role::instance($this->preferences, $this->db)->findById($roleId)) {
            $this->id = (int) $roleId;

            return $this->queryBuilder
                ->resetQueryParts()
                ->delete('rpd_user_has_role')
                ->where('id_role = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute();
        }

        return false;
    }

}
