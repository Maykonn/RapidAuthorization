<?php

/**
 * Entity
 *
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

use \PDO;

class Entity
{

    /**
     * @var PDO
     */
    protected $db;

    protected function __construct(PDO $pdo)
    {
        $this->db = $pdo;
    }

}

?>
