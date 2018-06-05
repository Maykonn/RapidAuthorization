<?php
/**
 * IEntity
 *
 * @author Maykonn Welington Candido<maykonn@outlook.com>
 */

namespace RapidAuthorization;

use Doctrine\DBAL\Driver\Connection;

interface EntityInterface
{
    public static function instance(ClientPreferences $preferences, Connection $pdo);
}
