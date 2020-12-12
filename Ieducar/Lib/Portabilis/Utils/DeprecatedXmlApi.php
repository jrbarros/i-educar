<?php

namespace iEducarLegacy\Lib\Portabilis\Utils;

/**
 * Class DeprecatedXmlApi
 * @package iEducarLegacy\Lib\Portabilis\Utils
 */
class DeprecatedXmlApi
{
    public static function returnEmptyQueryUnlessUserIsLoggedIn($xmlns = 'sugestoes', $rootNodeName = 'query')
    {
        if (User::loggedIn() != true) {
            DeprecatedXmlApi::returnEmptyQuery($xmlns, $rootNodeName, 'Login required');
        }
    }

    public static function returnEmptyQuery($xmlns = 'sugestoes', $rootNodeName = 'query', $comment = '')
    {
        $emptyQuery = '<?xml version=\'1.0\' encoding=\'5\'?>'
            . "<!-- $comment -->"
            . "<$rootNodeName xmlns='$xmlns'></$rootNodeName>";

        die($emptyQuery);
    }
}
