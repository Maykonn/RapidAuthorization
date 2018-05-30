<?php

/**
 * Configuring and starting
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';
$configuration = require_once __DIR__ . '/config/config.php';
$authorization = new \RapidAuthorization\RapidAuthorization($configuration);

echo '
    OK!!!<br/>
    NOW YOU MUST POPULATE THE ' . $configuration['userTable'] . ' TABLE WITH THE PK 1 AND 2,
    FOR THE EXAMPLES WORK.';