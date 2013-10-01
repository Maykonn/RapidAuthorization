<?php

/**
 * Authorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

// Necessárias antes do Autoload
require_once 'ClientPreferences.php';
require_once 'AvaiblePreferences.php';
require_once 'Autoload.php';

class RapidAuthorization
{

    /**
     * @var User
     */
    private $user;

    /**
     * @var ClientPreferences
     */
    private $preferences;

    /**
     * Get client preferences handler
     * @return ArrayObject
     */
    /* public function getPreferences()
      {
      return $this->preferences->getPreferencesList();
      } */

    public function __construct($preferences = Array())
    {
        $this->preferences = ClientPreferences::instance($preferences);
        $this->user = User::instance($this->preferences->userClassInstance);
    }

}

?>
