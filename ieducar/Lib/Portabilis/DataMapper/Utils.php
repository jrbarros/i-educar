<?php
namespace iEducarLegacy\Lib\Portabilis\DataMapper;

use iEducarLegacy\Lib\CoreExt\CoreExtensionException;

/**
 * Class Utils
 * @package iEducarLegacy\Lib\Portabilis\DataMapper
 */
class Utils
{
    public static function getDataMapperFor($packageName, $modelName)
    {
        $dataMapperClassName = ucfirst($packageName) . '_Model_' . ucfirst($modelName) . 'DataMapper';
        $classPath = str_replace('_', '/', $dataMapperClassName) . '.php';

        include_once $classPath;

        if (!class_exists($dataMapperClassName)) {
            throw new CoreExtensionException("Class '$dataMapperClassName' not found in path $classPath.");
        }

        return new $dataMapperClassName();
    }
}
