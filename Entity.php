<?php

/**
 * Entity
 *
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use \PDO;
use \ArrayObject;

class Entity
{
    /**
     * @var ClientPreferences
     */
    protected $preferences;

    /**
     * @var ArrayObject
     */
    protected $preferencesList;

    /**
     * @var PDO
     */
    protected $db;

    protected function __construct(ClientPreferences $preferences, PDO $pdo)
    {
        $this->preferences = $preferences;
        $this->preferencesList = $this->preferences->getPreferencesList();
        $this->db = $pdo;
    }

}

?>
