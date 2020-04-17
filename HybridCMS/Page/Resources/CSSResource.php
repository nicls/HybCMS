<?php

namespace HybridCMS\Page\Resources;

/**
 * class CSSResources
 *
 * @package Page\Resource
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class CSSResource extends Resource {

    /**
     * __construct
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     */
    public function __construct(
            $resourceName,
            $resourcePath,
            $priority = 1,
            $minify = true,
            $autoActivate = true) {

        try {

            //call constructor of parent
            parent::__construct(
                    $resourceName,
                    $resourcePath,
                    $priority,
                    $minify,
                    $autoActivate
            );

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

}
