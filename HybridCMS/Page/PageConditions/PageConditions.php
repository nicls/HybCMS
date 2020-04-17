<?php

namespace HybridCMS\Page\PageConditions;

/**
 * class PageConditions
 *
 * @package Page\PageConditions
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class PageConditions {

    /*
     * Attributes
     */
    private $arrPageConditions;

    /**
     * __construct
     *
     */
    public function __construct() {
        $this->arrObjConditions = array();
    }

    /**
     * registerPageCondition
     *
     * @param String $conditionName
     * @param String $value
     * @return void
     */
    public function registerPageCondition($conditionName, $value) {

        //check if $conditionName is alphanumeric
        if (!ctype_alnum($conditionName)) {
                throw new \Exception(
                    "Error Processing Request:
                        registerPageCondition(), conditionName must be alphanumeric.", 1);
        }

        if(!is_bool($value)) {
                throw new \Exception(
                    "Error Processing Request:
                        registerPageCondition(), value must be of type Boolean.", 1);
        }

        //create new PageCondition
        $objPageCondition = new \HybridCMS\Page\PageConditions\PageCondition($conditionName, $value);

        //add PageCondition to arrPageConditions
        arrayPush($this->arrPageConditions, array($conditionName, $objPageCondition));
    }

    /**
     * checkCondition - checks if a condition is registered or not
     *
     * @param String $conditionName
     * @return boolean
     */
    public function checkPageCondition($conditionName) {

        //check if $conditionName is alphanumeric
        if (!ctype_alnum($conditionName)) {
                throw new \Exception(
                    "Error Processing Request:
                        registerPageCondition(), conditionName must be alphanumeric.", 1);
        }

        //check if condition is registered
        if (isset($this->arrPageConditions[$conditionName])) {
            return $this->arrPageConditions[$conditionName]->getValue();
        }

        //condition is not active
        else return false;
    }

}

?>
