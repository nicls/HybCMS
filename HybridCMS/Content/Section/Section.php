<?php

namespace HybridCMS\Content\Section;

/**
 * class Section - This class represents a Section with a set of Articles
 *
 * @package Content\Section
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class Section {

    /*
     * Attributes
     */
    protected $arrArticles;

    /**
     * __construct
     *
     * @param Article[] $arrArticles
     */
    public function __construct($arrArticles) {

        try {

            //set arrArticles
            $this->setArrArticles($arrArticles);

        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * orderBy - order Articles by a given Attribute
     *
     * @param String attributeName
     * @return void
     */
    public function orderBy($attributeName, $order = 'asc') {
        
        if(empty($this->arrArticles)) return;                

        //ckeck if attributeName is alphanumeric
        if(!ctype_alnum($attributeName)) {
            throw new \Exception(
                    "Error Processing Request:
                        orderBy(), attributeName must be alphanumeric.", 1);
        }

        //build name of getterFunction
        $getterFunctionName = "get" . ucfirst($attributeName);

        if (method_exists($this->arrArticles[0], $getterFunctionName)
                && is_int($this->arrArticles[0]->$getterFunctionName())) {
            if($order == 'asc') {
                $this->arrArticles = $this->quickSort($this->arrArticles, $getterFunctionName);
            } else {
                $this->arrArticles = $this->quickSortDesc($this->arrArticles, $getterFunctionName);
            }
        } else
            throw new \Exception(
                    "Error Processing Request:
                        orderBy(), " . $attributeName . " is not a valid Attribute.", 1);
    }
    
    
    /**
     * shuffleArticles
     */
    public function shuffleArticles()
    {
        if(true === is_array($this->arrArticles))
        {
            shuffle($this->arrArticles);
        }
    }   
    

    /**
     * quickSort
     *
     * @param Article[] arrArticles
     * @param String getterFunction
     * @return Article[]
     */
    protected function quickSort($arrArticles, $getterFunction) {

        //check if there is something to sort
        if (count($arrArticles) <= 1)
            return $arrArticles;

        $key = $arrArticles[0]->$getterFunction();
        $arrKey = $arrArticles[0];
        
        $left_arr = array();
        $right_arr = array();        

        for ($i = 1; $i < count($arrArticles); $i++) {
            //order by asc
            if ($arrArticles[$i]->$getterFunction() < $key)
                $left_arr[] = $arrArticles[$i];
            else
                $right_arr[] = $arrArticles[$i];
        }

        //Rekursion
        $left_arr = $this->quickSort($left_arr, $getterFunction);
        $right_arr = $this->quickSort($right_arr, $getterFunction);

        return array_merge($left_arr, array($arrKey), $right_arr);
    }
    
    /**
     * quickSortDesc - sort descending
     *
     * @param Article[] arrArticles
     * @param String getterFunction
     * @return Article[]
     */
    protected function quickSortDesc($arrArticles, $getterFunction) {

        //check if there is something to sort
        if (count($arrArticles) <= 1)
            return $arrArticles;

        $key = $arrArticles[0]->$getterFunction();
        $arrKey = $arrArticles[0];
        
        $left_arr = array();
        $right_arr = array();        

        for ($i = 1; $i < count($arrArticles); $i++) {
            //order by asc
            if ($arrArticles[$i]->$getterFunction() >= $key)
                $left_arr[] = $arrArticles[$i];
            else
                $right_arr[] = $arrArticles[$i];
        }

        //Rekursion
        $left_arr = $this->quickSort($left_arr, $getterFunction);
        $right_arr = $this->quickSort($right_arr, $getterFunction);

        return array_merge($left_arr, array($arrKey), $right_arr);
    }

    /**
     * setArrArticles
     *
     * @param Article[] $arrArticles
     * @throws \Exception
     * @return void
     */
    public function setArrArticles($arrArticles) {

        //ckeck if $arrArticles is an array
        if(!is_array($arrArticles)) {
            throw new \Exception(
                    "Error Processing Request:
                        setArrArticles(), arrArticles must be an array.", 1);
        }

        //check if array is of type Article[]
        foreach ($arrArticles as &$objArticle) {
            if(!($objArticle instanceof \HybridCMS\Content\Article\Article)) {
                throw new \Exception(
                    "Error Processing Request:
                        setArrArticles(), arrArticles must be of type Article[].", 1);
            }
        }

        //assign $arrArticles
        $this->arrArticles = $arrArticles;
    }

    /**
     * getArrArticles
     * @return array Articles
     */
    public function getArrArticles() { return $this->arrArticles; }

}

?>