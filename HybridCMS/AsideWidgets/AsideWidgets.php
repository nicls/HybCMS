<?php

namespace HybridCMS\AsideWidgets;

/**
 * class AsideWidgets - This class holds all AsideWidgets for different Positions
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AsideWidgets {

    /**
     * attributes
     */
    private $arrAsideWidgets; //array to hold the AsideWidgets

    /**
     * __construct
     */
    public function __construct() {
        $this->arrAsideWidgets = array();
    }

    /**
     * addAsideWidget - add a new AsideWidget
     *
     * @param objAsideWidget:AsideWidget
     * @return void
     */
    public function addAsideWidget($objAsideWidget) {

        //check wether $objAsideWidget is instance of AsideWidget
        if (is_subclass_of($objAsideWidget, 'HybridCMS\AsideWidgets\AsideWidget')) {

            //add aside Widget to the array of aside Widgets
            array_push($this->arrAsideWidgets, $objAsideWidget);
        } else {
            throw new \Exception(
                    "Error Processing Request: addAsideWidget(),
                        objAsideWidget must be a subclass of AsideWidget.", 1);
        }
    }

    /**
     * getAsideWidgets - return all AsideWidgets for a given positionName
     *
     * @param $positionName:String
     * @return arrAsideWidgets
     */
    public function getAsideWidgets($positionName) {

        //ckeck if positionName is alphanmueric
        if (!ctype_alnum($positionName)) {
            throw new \Exception(
                    "Error Processing Request: getAsideWidgets(), only Alphanumeric-Characters are allowed.", 1);
        }

        $arrTmp = array();

        //get all Widgets that position is $positionName
        foreach ($this->arrAsideWidgets as $asideWidget) {
            if ($asideWidget->getPositionName() == $positionName) {
                array_push($arrTmp, $asideWidget);
            }
        }

        //sort Widgets
        $arrSorted = $this->quickSort($arrTmp);

        return $arrSorted;
    }

    /**
     * quickSort - sort AsideWidgets by attribute 'priority'
     *
     * @param arrAsideWidgets:AssideWidget[*]
     * @return arrAsideWidget:AsideWidget[*]
     */
    protected function quickSort($arrAsideWidgets) {

        //check if array is not empty
        if (count($arrAsideWidgets) <= 1)
            return $arrAsideWidgets;

        $key = $arrAsideWidgets[0]->getPriority();
        $arrKey = $arrAsideWidgets[0];
        
        $left_arr = array();
        $right_arr = array();

        for ($i = 1; $i < count($arrAsideWidgets); $i++) {
            //order by desc
            if ($arrAsideWidgets[$i]->getPriority() < $key)
                $left_arr[] = $arrAsideWidgets[$i];
            else
                $right_arr[] = $arrAsideWidgets[$i];
        }

        //Rekursion
        $left_arr = $this->quickSort($left_arr);
        $right_arr = $this->quickSort($right_arr);

        return array_merge($left_arr, array($arrKey), $right_arr);
    }

}

//end class
?>