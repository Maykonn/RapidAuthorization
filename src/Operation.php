<?php

/**
 * Operation
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \Exception;

class Operation extends Entity
{
    /**
     * Verify if needs to check the authorization, see Operation::populateById()
     * @var '1' or '0'
     */
    public $needs_authorization = '1';

    /**
     * An Operation can be, e.g. Create Product or Edit Customer
     */
    public function create($businessName, $name = null, $description = null, $needsAuthorization = '1')
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
        if (
            $needsAuthorizationValue == '1' || $needsAuthorizationValue == '0' ||
            $needsAuthorizationValue === true || $needsAuthorizationValue === false
        ) {
            return true;
        }

        throw new Exception(
            'Bad value to $needsAuthorization param. Expected: \'1\' or \'0\', given ' .
            $needsAuthorizationValue
        );
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
            $sql = "DELETE FROM rpd_operation WHERE id = :id";

            $this->id = $id;
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
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
     * Verify if needs to verify Autorization to execute Operation
     */
    public function needsAuthorization($operationId)
    {
        if ($this->populateById($operationId)) {
            switch ($this->needs_authorization) {
                case 1:
                case '1':
                case true:
                    return true;
                    break;
                case 0:
                case '0':
                case false:
                    return false;
                    break;
            }
        }

        return true;
    }

    public function findById($operationId)
    {
        $sql = "SELECT id, name, business_name, description, needs_authorization FROM rpd_operation WHERE id = :operationId";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':operationId', $operationId, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $operation = $stmt->fetch();

        if ($operation) {
            return $operation;
        }

        throw new Exception('Record #' . $operationId . ' not found on `operation` table');
    }

    public function findByName($name)
    {
        $sql = "SELECT id, name, business_name, description, needs_authorization FROM rpd_operation WHERE name = :name";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $operation = $stmt->fetch();

        if ($operation) {
            return $operation;
        }

        throw new Exception('Record with name: ' . $name . ' not found on `operation` table');
    }

    public function findByNotRequireAuthorization()
    {
        $sql = "SELECT id, name, business_name, description, needs_authorization FROM rpd_operation WHERE needs_authorization = '0'";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByRequireAuthorization()
    {
        $sql = "SELECT id, name, business_name, description, needs_authorization FROM rpd_operation WHERE needs_authorization = '1'";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $sql = "SELECT id, name, business_name, description, needs_authorization FROM rpd_operation";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':businessName', $this->business_name, PDO::PARAM_STR);

        switch ($this->needs_authorization) {
            case 1:
            case true:
                $needsAuthorization = '1';
                break;
            case 0:
            case false:
                $needsAuthorization = '0';
                break;
            default :
                $needsAuthorization = $this->needs_authorization;
                break;
        }

        $stmt->bindParam(':needsAuthorization', $needsAuthorization, PDO::PARAM_STR);

        $name = ($this->name ? $this->name : null);
        $stmt->bindParam(':name', $name);

        $description = ($this->description ? $this->description : null);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        if ( ! $this->id) {
            $this->id = (int) $this->db->lastInsertId();
        }

        $this->id = (int) $this->id;

        return $this->id;
    }

    public function getTasksThatCanExecute($operationId)
    {
        if (Operation::instance($this->preferences, $this->db)->findById($operationId)) {
            $sql = "SELECT id_task FROM rpd_task_has_operation WHERE id_operation = :idOperation";
            $stmt = $this->db->prepare($sql);
            $this->id = (int) $operationId;
            $stmt->bindParam(':idOperation', $this->id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            return $stmt->fetchAll();
        }

        return false;
    }

    public function removeOperationFromTask($operationId, $taskId)
    {
        if (
            Task::instance($this->preferences, $this->db)->findById($taskId) &&
            Operation::instance($this->preferences, $this->db)->findById($operationId)
        ) {
            $sql = "DELETE FROM rpd_task_has_operation WHERE id_task = :taskId AND id_operation = :operationId";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':taskId', $taskId, PDO::PARAM_INT);
            $stmt->bindParam(':operationId', $operationId, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

}

