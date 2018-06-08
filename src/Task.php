<?php

/**
 * Task
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\ParameterType;

class Task extends Entity
{
    public function delete($id)
    {
        if ($this->findById($id)) {
            $this->id = (int) $id;

            $result = $this->queryBuilder
                ->resetQueryParts()
                ->delete('rpd_task')
                ->where('id = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute();

            if ($result) {
                return $this->id;
            }
        }

        return false;
    }

    public function attachOperation($operationId, $taskId)
    {
        if ($this->isPossibleToAttachTheOperation($operationId, $taskId)) {
            return $this->queryBuilder
                ->resetQueryParts()
                ->insert('rpd_task_has_operation')
                ->values(array('id_task' => '?', 'id_operation' => '?'))
                ->setParameter(0, $taskId, ParameterType::INTEGER)
                ->setParameter(1, $operationId, ParameterType::INTEGER)
                ->execute();
        }

        return false;
    }

    private function isPossibleToAttachTheOperation($operationId, $taskId)
    {
        return (
            Operation::instance($this->preferences, $this->db)->findById($operationId) &&
            ! Task::instance($this->preferences, $this->db)->hasOperation($operationId, $taskId)
        );
    }

    public function findById($taskId)
    {
        return $this->queryBuilder
            ->resetQueryParts()
            ->select('id', 'name', 'business_name', 'description')
            ->from('rpd_task')
            ->where('id = ?')
            ->setParameter(0, $taskId, ParameterType::INTEGER)
            ->execute()
            ->fetch();
    }

    public function findByName($name)
    {
        return $this->queryBuilder
            ->resetQueryParts()
            ->select('id', 'name', 'business_name', 'description')
            ->from('rpd_task')
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
            ->from('rpd_task')
            ->execute()
            ->fetchAll();
    }

    public function save()
    {
        $sql = "
            INSERT INTO rpd_task(
                id, name, business_name, description
            ) VALUES (
                :id, :name, :businessName, :description
            ) ON DUPLICATE KEY UPDATE name = :name, business_name = :businessName, description = :description";

        return $this->saveFromSQL($sql);
    }

    public function getRolesThatHasAccess($taskId)
    {
        if (Task::instance($this->preferences, $this->db)->findById($taskId)) {
            $this->id = (int) $taskId;

            return $this->queryBuilder
                ->resetQueryParts()
                ->select('id_role')
                ->from('rpd_role_has_task')
                ->where('id_task = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll();
        }

        return Array();
    }

    public function getOperations($taskId)
    {
        if (Task::instance($this->preferences, $this->db)->findById($taskId)) {
            $this->id = (int) $taskId;

            return $this->queryBuilder
                ->resetQueryParts()
                ->select('o.id', 'o.name', 'o.business_name', 'o.description')
                ->from('rpd_operation', 'o')
                ->innerJoin('o', 'rpd_task_has_operation', 'tho', 'o.id = tho.id_operation')
                ->where('tho.id_task = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute()
                ->fetchAll();
        }

        return Array();
    }

    public function hasOperation($operationId, $taskId)
    {
        if (
            Operation::instance($this->preferences, $this->db)->findById($operationId) &&
            Task::instance($this->preferences, $this->db)->findById($taskId)
        ) {
            $operation = Operation::instance($this->preferences, $this->db);
            $tasksThatCanExecuteTheOperation = $operation->getTasksThatCanExecute($operationId);
            foreach ($tasksThatCanExecuteTheOperation as $task) {
                if ($task['id_task'] == $taskId) {
                    return true;
                }
            }
        }

        return false;
    }

    public function removeTaskFromRole($taskId, $roleId)
    {
        if (
            Role::instance($this->preferences, $this->db)->findById($roleId) &&
            Task::instance($this->preferences, $this->db)->findById($taskId)
        ) {
            return $this->queryBuilder
                ->resetQueryParts()
                ->delete('rpd_role_has_task')
                ->where('id_role = ?')
                ->andWhere('id_task = ?')
                ->setParameter(0, $roleId, ParameterType::INTEGER)
                ->setParameter(1, $taskId, ParameterType::INTEGER)
                ->execute();
        }

        return false;
    }

}
