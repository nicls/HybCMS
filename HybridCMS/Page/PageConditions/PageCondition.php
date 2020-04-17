<?php

namespace HybridCMS\Page\PageConditions;

/**
 * class PageCondition
 *
 * @package Page\PageConditions
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class PageCondition {

    /*
     * Attributes
     */
    private $conditionName; //name of the condition
    private $value; //Boolean, indicates weather this condition is active or not

    /**
     * __construct
     *
     * @param String $conditionName
     * @param String $value
     * @return void
     */
    public function __construct($conditionName, $value) {
        try {

            $this->setConditionName($conditionName);
            $this->setValue($value);

        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError( $e->__toString() . "\n" );

            throw $e;
        }
    }

    /**
     * setConditionName
     *
     * @param String $conditionName
     * @throws \Exception
     * @return void
     */
    public function setConditionName($conditionName) {

        //check if $conditionName is alphanumeric
        if(!ctype_alnum($conditionName)) {
                throw new \Exception(
                    "Error Processing Request:
                        setConditionName(), conditionName must be alphanumeric.", 1);
        }

        $this->conditionName = $conditionName;
    }

    /**
     * setValue
     *
     * @param String $value
     * @throws \Exception
     * @return void
     */
    public function setValue($value) {

        //ckeck if value is alphanumeric
        if(!is_bool($value)) {
                throw new \Exception(
                    "Error Processing Request:
                        setConditionName(), value must be of type Boolean.", 1);
        }

        $this->value = $value;
    }

    /*
     * getter
     */
    public function getConditionName() { return $this->conditionName; }
    public function getValue() { return $this->value; }

}

?>