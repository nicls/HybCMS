<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class PageRole
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class PageRole implements \HybridCMS\Page\PageSettings\IPageSetting {

    /*
     * Attributes
     */
    private $arrRoleNames;


    public function __construct($roleName) {
        try {
            $this->addRoleName($roleName);
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setRoleName
     *
     * @param String $roleName
     * @throws \Exception
     * @return void
     */
    public function addRoleName($roleName) {

        //check if roleName is alphanumeric
        if(!ctype_alnum($roleName)) {
                throw new \Exception(
                    "Error Processing Request:
                        setRoleName(), roleName is not valid.", 1);
        }

        $this->arrRoleNames[] = $roleName;
    }
    
    /**
     * Checks if a given rolaName ist set
     * @param String $roleName
     * @return Boolean
     * @throws \Exception
     */
    public function hasPageRole($roleName) {
        //check if roleName is alphanumeric
        if(!ctype_alnum($roleName)) {
                throw new \Exception(
                    "Error Processing Request:
                        hasPageRole(), roleName is not valid.", 1);
        }
        
        return in_array($roleName, $this->arrRoleNames);
    }
    
    

    /**
     * toString
     *
     * @return String
     */
    public function toString() {
        return implode(', ', $this->arrRoleNames);
    }

}

?>