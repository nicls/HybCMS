<?php

namespace HybridCMS\Plugins\Plugin;

/**
 * class ControllerFactory
 *
 * @package Plugin
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
interface IControllerFactory {

    /**
     * Creates an Controller pending on submitted $type
     * @param String $type Name of the Controller-Class
     * @param mixed[] $arrParams Parameter passed to the controller
     * @return \HybridCMS\Plugins\*\Controller\class
     * @throws \Exception
     */
    public static function create($type, $arrParams);

}
