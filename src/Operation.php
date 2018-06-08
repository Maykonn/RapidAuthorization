<?php

/**
 * Operation
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\ParameterType;

class Operation extends Entity
{
    /**
     * Some operation can be executed by any user regardless of your role.
     * Verify if needs to check the authorization to perform the operation.
     */
    public $needs_authorization = true;

    /**
     * An Operation can be, e.g. Create Product or Edit Customer
     */
    public function create($businessName, $name = null, $description = null, $needsAuthorization = true)
    {
        $this->name = $name;
        $this->business_name = $businessName;
        $this->description = $description;

        if ($this->isValidNeedsAuthorizationValue($needsAuthorization)) {
            $this->needs_authorization = $needsAuthorization;

            return $this->save();
        }

        return false;
    }

    private function isValidNeedsAuthorizationValue($needsAuthorizationValue)
    {
        return is_bool($needsAuthorizationValue);
    }

    /**
     * Set '' to $description to set NULL on database
     */
    public function update($id, $businessName, $name = null, $description = null, $needsAuthorization = '1')
    {
        if ($this->populateById($id)) {
            $this->id = $id;
            $this->business_name = $businessName;

            if ($name !== null) {
                $this->name = $name;
            }

            if ($description !== null) {
                $this->description = $description;
            }

            if ($this->isValidNeedsAuthorizationValue($needsAuthorization)) {
                $this->needs_authorization = $needsAuthorization;

                return $this->save();
            }
        }

        return 0;
    }

    public function delete($id)
    {
        if ($this->findById($id)) {
            $this->id = (int) $id;

            $result = $this->queryBuilder
                ->delete('rpd_operation')
                ->where('id = ?')
                ->setParameter(0, $this->id, ParameterType::INTEGER)
                ->execute();

            if ($result) {
                return $this->id;
            }
        }

        return false;
    }

    /**
     * Populate object with values from record on database
     */
    private function populateById($operationId)
    {
        $operation = $this->findById($operationId);

        if ($operation) {
            $this->id = (int) $operation['id'];
            $this->name = $operation['name'];
            $this->business_name = $operation['business_name'];
            $this->description = $operation['description'];
            $this->needs_authorization = $operation['needs_authorization'];

            return true;
        }

        return false;
    }

    /**
     * Verify if needs have authorization to execute the $operationId
     */
    public function needsAuthorization($operationId)
    {
        $this->populateById($operationId);

        return $this->needs_authorization;
    }

    public function findById($operationId)
    {
        $operation = $this->queryBuilder
            ->select('id', 'name', 'business_name', 'description', 'needs_authorization')
            ->from('rpd_operation')
            ->where('id = ?')
            ->setParameter(0, $operationId, ParameterType::INTEGER)
            ->execute()
            ->fetch();

        $operation['needs_authorization'] = (bool) $operation['needs_authorization'];

        return $operation;
    }

    public function findByName($name)
    {
        return $this->queryBuilder
            ->select('id', 'name', 'business_name', 'description', 'needs_authorization')
            ->from('rpd_operation')
            ->where('name = ?')
            ->setParameter(0, $name, ParameterType::STRING)
            ->execute()
            ->fetch();
    }

    public function findByNotRequireAuthorization()
    {
        return $this->queryBuilder
            ->select('id', 'name', 'business_name', 'description', 'needs_authorization')
            ->from('rpd_operation')
            ->where('needs_authorization = 0')
            ->execute()
            ->fetchAll();
    }

    public function findByRequireAuthorization()
    {
        return $this->queryBuilder
            ->select('id', 'name', 'business_name', 'description', 'needs_authorization')
            ->from('rpd_operation')
            ->where('needs_authorization = 1')
            ->execute()
            ->fetchAll();
    }

    public function findAll()
    {
        return $this->queryBuilder
            ->select('id', 'name', 'business_name', 'description', 'needs_authorization')
            ->from('rpd_operation')
            ->execute()
            ->fetchAll();
    }

    private function save()
    {
        $sql = "
            INSERT INTO rpd_operation(
                id, name, business_name, description, needs_authorization
            ) VALUES (
                :id, :name, :businessName, :description, :needsAuthorization
            ) ON DUPLICATE KEY UPDATE name = :name, business_name = :businessName,  description = :description, needs_authorization = :needsAuthorization";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $this->id, ParameterType::INTEGER);
        $stmt->bindParam(':businessName', $this->business_name, ParameterType::STRING);

        $needsAuthorization = (int) $this->needs_authorization;
        $stmt->bindParam(':needsAuthorization', $needsAuthorization);

        $name = ($this->name ? $this->name : null);
        $stmt->bindParam(':name', $name);

        $description = ($this->description ? $this->description : null);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        if ( ! $this->id) {
            $this->id = $this->db->lastInsertId();
        }

        return $this->id = (int) $this->id;
    }

    public function getTasksThatCanExecute($operationId)
    {
        if (Operation::instance($this->preferences, $this->db)->findById($operationId)) {
            $this->id = (int) $operationId;

            return $this->queryBuilder
                ->select('id_task')
                ->from('rpd_task_has_operation')
                ->where('id_operation = ?')
                ->setParameter(0, $this->id)
                ->execute()
                ->fetch();
        }

        return false;
    }

    public function removeOperationFromTask($operationId, $taskId)
    {
        if (
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            Operation::instance($this->preferences, $this->db)->findById($operationId)
        ) {
            return $this->queryBuilder
                ->delete('rpd_task_has_operation')
                ->where('id_task = ?')
                ->andWhere('id_operation = ?')
                ->setParameter(0, $taskId, ParameterType::INTEGER)
                ->setParameter(1, $operationId, ParameterType::INTEGER)
                ->execute();
        }

        return false;
    }

}

