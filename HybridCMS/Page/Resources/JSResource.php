<?php

namespace HybridCMS\Page\Resources;

/**
 * class JSResource
 *
 * @package Page\Resource
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class JSResource extends Resource {

    /**
     *
     * @var String
     */
    private $position;

    /**
     *
     * @var Boolean
     */
    private $async;

    /**
     * __construct
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     * @param String $position Allowed is head and footer
     * @param Boolean $async
     */
    public function __construct(
            $resourceName,
            $resourcePath,
            $priority = 1,
            $minify = true,
            $autoActivate = true,
            $position = 'footer',
            $async = false) {

        try {

            //call constructor of parent
            parent::__construct(
                    $resourceName, $resourcePath, $priority, $minify, $autoActivate
            );

            $this->setPosition($position);
            $this->setAsync($async);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setAsync
     *
     * @param type $async
     * @throws \Exception
     * @return void
     */
    public function setAsync($async) {
        if(!is_bool($async)) {
            throw new \Exception(
                    "Error Processing Request:
                        setAsync(), async must be a boolean.", 1);
        }

        //assign async
        $this->async = $async;
    }

    /**
     * setPosition
     *
     * @param String $position
     * @throws \Exception
     * @return void
     */
    public function setPosition($position) {

        //check if position is head or footer
        if(!in_array($position, array('head', 'footer'))) {
                throw new \Exception(
                    "Error Processing Request:
                        setPosition(), position must be head or footer.", 1);
        }

        //assign position
        $this->position = $position;
    }

    /*
     * Getter
     */
    public function getPosition() { return $this->position; }
    public function getAsync() { return $this->async; }

}

?>