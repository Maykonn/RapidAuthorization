<?php

/**
 * Task
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\ParameterType;
use \PDO;
use \Exception;

class Task extends Entity
{
    public function delete($id)
    {
        if ($this->findById($id)) {
            $sql = "DELETE FROM rpd_task WHERE id = :id";

            $this->id = $id;
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

    public function attachOperation($operationId, $taskId)
    {
        if ($this->isPossibleToAttachTheOperation($operationId, $taskId)) {
            $sql = "INSERT INTO rpd_task_has_operation(id_task, id_operation) VALUES (:idTask, :idOperation)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':idTask', $taskId, PDO::PARAM_INT);
            $stmt->bindParam(':idOperation', $operationId, PDO::PARAM_INT);

            return $stmt->execute();
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
            $sql = "SELECT id_role FROM rpd_role_has_task WHERE id_task = :idTask";
            $stmt = $this->db->prepare($sql);
            $this->id = (int) $taskId;
            $stmt->bindParam(':idTask', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }

        return Array();
    }

    public function getOperations($taskId)
    {
        if (Task::instance($this->preferences, $this->db)->findById($taskId)) {
            $sql = "
                SELECT o.id, o.name, o.business_name, o.description
                FROM rpd_operation o INNER JOIN rpd_task_has_operation tho ON o.id = tho.id_operation
                WHERE tho.id_task = :idTask";

            $stmt = $this->db->prepare($sql);
            $this->id = (int) $taskId;
            $stmt->bindParam(':idTask', $this->id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $sql = "DELETE FROM rpd_role_has_task WHERE id_role = :roleId AND id_task = :taskId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
            $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

}
