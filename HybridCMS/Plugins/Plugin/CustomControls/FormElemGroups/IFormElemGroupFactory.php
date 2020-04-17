<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups;

/**
 * class Factory to create FormElemGroupes
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
interface IFormElemGroupFactory {
    
    /**
     * FormElemGroupFactory creates an FormElemGroup based on the 
     * FormElemGroupContent
     * 
     * @param HybridCMS\Plugins\Plugin\CustomControls\
     *  FormElemGroupContent\Content $objFormElemGroupContent
     * @param HybridCMS\Plugins\Plugin\CustomControls\
     *  FormStateObserver $objFormStateObserver
     */
    public static function create(&$objFormElemGroupContent, 
            &$objFormStateObserver);
}
