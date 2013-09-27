<?php

/**
 * Authorization
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace Rapid\Authorization;

require_once 'ClientPreferences.php';

class Authorization
{

    /**
     * @var ClientPreferences
     */
    private $preferences;

    /**
     * Get client preferences handler
     * @return ArrayObject
     */
    public function getPreferences()
    {
        return $this->preferences->getPreferencesList();
    }

    public function __construct($preferences = Array())
    {
        $this->preferences = ClientPreferences::instance($preferences);
    }

}

?>
