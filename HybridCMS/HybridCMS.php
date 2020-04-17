<?php

namespace HybridCMS;

/**
 * class HybridCMS - Facade for easy access to the most important funtions
 *
 * @package /
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class HybridCMS {

    /**
     * Object that holds alls PageSettings
     * @var PageSettings
     */
    private $objPageSettings;

    /**
     * Object that holds all PageConditions
     * @var PageConditions
     */
    private $objPageConditions;

    /**
     * Object that holds all JS & CSS Resources
     * @var Resources
     */
    private $objResources;

    /**
     * Oject that holds all AsideWidgets
     * @var type
     */
    private $objAsideWidgets;
    
    /**
     * Detects if client is a mobile
     * @var MobileDetect
     */
    private $objMobileDetect;
    
    /**
     * Strings 
     * @var Strings
     */
    private $objStrings;
    
    /**
     * Memcached Singleton Object
     */
    private $objMemcached;

    /**
     * __construct
     *
     * @throws \HybridCMS\Exception
     */
    public function __construct() {

        try {

            $this->objPageSettings = new \HybridCMS\Page\PageSettings\PageSettings();
            $this->objPageConditions = new \HybridCMS\Page\PageConditions\PageConditions();
            $this->objResources = new \HybridCMS\Page\Resources\Resources();
            $this->objAsideWidgets = new \HybridCMS\AsideWidgets\AsideWidgets();
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setupPage
     *
     * @param String $settingName
     * @param mixed[] $values
     * @throws \Exception
     */
    public function setupPage($settingName, $values = array()) 
    {
        //check if settingName consists of letters from a to z
        if (!ctype_alpha($settingName)) {
            throw new \Exception(
            "Error Processing Request: setupPage(),
                       settingName must be a String with 
                       charcters from a to z.", 1);
        }

        //convert Uppercases to lowercases
        $settingName = strtolower($settingName);

        //add object of Noindex
        if ($settingName === 'noindex') {
            $this->objPageSettings->addNoindex();
        } 

        //add object of NoindexNofollow
        else if ($settingName === 'noindexnofollow') {
            $this->objPageSettings->addNoindexNofollow();
        } 

        //add object of Title
        else if ($settingName === 'title' && isset($values['title'])) {
            $prepend = '';
            $maxLength = 65;
            if (isset($values['prepend']))
                $prepend = $values['prepend'];
            if (isset($values['maxLength']))
                $maxLength = $values['maxLength'];

            $this->objPageSettings->addTitle($values['title'], $prepend, $maxLength);
        }

        //add object of Description
        else if ($settingName === 'description' && isset($values['description'])) 
        {
            $this->objPageSettings->addDescription($values['description']);
        } 

        //add Object of Canonical
        else if ($settingName === 'canonical' && isset($values['canonical'])) 
        {
            $this->objPageSettings->addCanonical($values['canonical']);
        } 
        
        //add Object of AmpHtml
        else if ($settingName === 'amphtml' && isset($values['ampPage'])) 
        {
            $this->objPageSettings->addAmpHtml($values['ampPage']);
        }         

        //add an Object of PageRole
        else if ($settingName === 'pagerole' && isset($values['roleName'])) 
        {
            $this->objPageSettings->addPageRole($values['roleName']);
        } 

        //add an Object of Keywords
        else if ($settingName === 'keywords' 
                 && 
                 true === is_array ($values)
                 &&
                 false === empty($values)) 
        {
            $this->objPageSettings->addKeywords($values);
        }             

        //add an Object of HrefLang
        else if ($settingName === 'hreflang' 
                && 
                true === isset($values['url'])
                &&
                true === isset($values['lang']))
        {
            $this->objPageSettings->addHrefLang($values['url'], $values['lang']);
        }
        
        //add an Object of HrefLang
        else if ($settingName === 'prefetch' 
                 && 
                 true === isset($values['url']))
        {
            $this->objPageSettings->addPrefetch($values['url']);
        }        

        //nothing matched
        else 
        {
            throw new \Exception(
            "Error Processing Request: setupPage(),
                       settingName ". htmlspecialchars($settingName) 
                    ." is unknown.", 1);
        }
    }

    /**
     * getPageSetting
     *
     * @param String $settingName
     * @return String
     * @throws \Exception
     * @throws \HybridCMS\Exception
     */
    public function getPageSetting($settingName) {

        //check if settingName consists of letters from a to z
        if (!ctype_alpha($settingName)) {
            throw new \Exception(
            "Error Processing Request: getPageSetting(),
                       settingName must be a String with charcters from a to z.", 1);
        }

        //convert uppercases to lowercases
        $settingName = strtolower($settingName);

        try {
            /** get Noindex */
            if ($settingName == 'noindex') {
                if ($this->objPageSettings->getNoindex())
                    return $this->objPageSettings->getNoindex()->toString();
                else
                    return '';
            }

            else //get oindexNofollow
            if ($settingName == 'noindexnofollow') {
                if ($this->objPageSettings->getNoindexNofollow())
                    return $this->objPageSettings->getNoindexNofollow()->toString();
                else
                    return '';
            }

            else //get Title
            if ($settingName == 'title') {
                if ($this->objPageSettings->getTitle())
                    return $this->objPageSettings->getTitle()->toString();
                else
                    return '';
            }

            else //get description
            if ($settingName == 'description') {
                if ($this->objPageSettings->getDescription())
                    return $this->objPageSettings->getDescription()->toString();
                else
                    return '';
            }

            else //get canonical
            if ($settingName == 'canonical') {
                if ($this->objPageSettings->getCanonical())
                    return $this->objPageSettings->getCanonical()->toString();
                else
                    return '';
            }
            
            else //get amphtml
            if ($settingName == 'amphtml') {
                if ($this->objPageSettings->getAmpHtml())
                    return $this->objPageSettings->getAmpHtml()->toString();
                else
                    return '';
            }            

            else//get pageRole
            if ($settingName == 'pagerole') {

                if ($this->objPageSettings->getPageRole())
                    return $this->objPageSettings->getPageRole()->toString();
                else {
                    return '';
                }
            } 
            
            else//get keywords
            if ($settingName == 'keywords') {
                if ($this->objPageSettings->getKeywords())
                    return $this->objPageSettings->getKeywords()->toString();
                else
                    return '';
            }
            
            else//get hreflang
            if ($settingName == 'hreflang') {
                if ($this->objPageSettings->getHrefLang())
                    return $this->objPageSettings->getHrefLang()->toString();
                else
                    return '';
            } 
            
            else//get prefetch
            if ($settingName == 'prefetch') {
                if ($this->objPageSettings->getPrefetch())
                    return $this->objPageSettings->getPrefetch()->toString();
                else
                    return '';
            }             

            //nothing matched
            else {
                throw new \Exception(
                "Error Processing Request: getPageSetting(),
                           settingName is unknown.", 1);
            }
            
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * registerPageCondition - calls registerPageCondition from objPageConditions
     * @param String $conditionName
     * @param Boolean $value
     * @return void
     * @throws \HybridCMS\Exception
     */
    public function registerPageCondition($conditionName, $value) {

        try {

            //call registerPageCondition from $this->objPageConditions
            $this->objPageConditions->registerPageCondition($conditionName, $value);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * checkPageCondition - calls checkPageCondition from $this->objPageConditions
     *
     * @param String $conditionName
     * @return Boolean
     * @throws \HybridCMS\Exception
     */
    public function checkPageCondition($conditionName) {

        try {

            //call checkPageCondition from $this->objPageConditions
            return $this->objPageConditions->checkPageCondition($conditionName);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * registerJS - calls registerJSResource from $this->objResources
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     * @param String $position
     * @param Boolean $async
     * @return void
     */
    public function registerJS(
    $resourceName, $resourcePath, $priority = 1, $minify = true, $autoActivate = true, $position = 'footer', $async = false) {

        try {

            //call registerJSResource from $this->objResources
            $this->objResources->registerJSResource(
                    $resourceName, $resourcePath, $priority, $minify, $autoActivate, $position, $async);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * registerCSS - calls registerCSSResource from $this->objResources
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     * @return void
     * @throws \HybridCMS\Exception
     */
    public function registerCSS(
    $resourceName, $resourcePath, $priority = 1, $minify = true, $autoActivate = true) {

        try {

            //call registerCSSResource from $this->objResources
            $this->objResources->registerCSSResource(
                    $resourceName, $resourcePath, $priority, $minify, $autoActivate
            );
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * activateResources - calls activateResources from  $this->objResources
     *
     * @param String[] $arrResourceNames
     * @retrun void
     * @throws \HybridCMS\Exception
     */
    public function activateResources($arrResourceNames) {

        try {

            //call activateResources from  $this->objResources
            $this->objResources->activateResources($arrResourceNames);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * printActiveCSSResources - calls getActiveCSSResources from $this->objResources
     *
     * @return void
     * @throws \HybridCMS\Exception
     */
    public function printActiveCSSResources() {

        try {

            //call getActiveCSSResources from $this->objResources
            $arrObjActiveCSSResources = $this->objResources->getActiveCSSResources();

            foreach ($arrObjActiveCSSResources as $activeCSSResource) {
                echo "<link type='text/css' rel='stylesheet' href='" . htmlentities($activeCSSResource->getResourcePath()) . "' />";
            }
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * printActiveJSResources - calls getActiveJSResources from $this->objResources
     *
     * @param String $position - Allowed is head or footer
     * @erturn void
     * @throws \HybridCMS\Exception
     */
    public function printActiveJSResources($position) {

        try {

            //call getActiveJSResources from $this->objResources
            $arrActiveJSResources = $this->objResources->getActiveJSResources($position);

            foreach ($arrActiveJSResources as $activeJSResource) {
                $async = ($activeJSResource->getAsync() === true) ? 'async' : '';
                echo "<script " . $async . " src='" . htmlentities($activeJSResource->getResourcePath()) . "' ></script>";
            }
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * loadPlugin - loads CSS and JavaScript Ressources of a plugin
     *
     * @param Plugin $objPlugin
     * @throws \Exception
     */
    public function loadPlugin(&$objPlugin) {

        //check if $objPlugin is a subclass of Plugins
        if (!is_subclass_of($objPlugin, '\HybridCMS\Plugins\Plugin\Plugin')) {
            throw new \Exception(
            "Error Processing Request: loadPlugin(),
                       objPlugin must be a subClass of Plugins.", 1);
        }

        //load CSS-Resources
        $arrObjCSSResources = $objPlugin->getArrObjCSSResources();

        foreach ($arrObjCSSResources as &$objCSSResource) {

            //get Attributes
            $resourceName = $objCSSResource->getResourceName();
            $resourcePath = $objCSSResource->getResourcePath();
            $priority = $objCSSResource->getPriority();
            $minify = $objCSSResource->getMinify();
            $autoActivate = $objCSSResource->getAutoActivate();

            //register CSS
            $this->registerCSS(
                    $resourceName, $resourcePath, $priority, $minify, $autoActivate);
        }//end foreach
        //load JS-Resources
        $arrObjJSResources = $objPlugin->getArrObjJSResources();
        foreach ($arrObjJSResources as &$objJSResources) {

            //get Attributes
            $resourceName = $objJSResources->getResourceName();
            $resourcePath = $objJSResources->getResourcePath();
            $priority = $objJSResources->getPriority();
            $minify = $objJSResources->getMinify();
            $autoActivate = $objJSResources->getAutoActivate();
            $position = $objJSResources->getPosition();
            $async = $objJSResources->getAsync();

            //register JS
            $this->registerJS(
                    $resourceName, $resourcePath, $priority, $minify, $autoActivate, $position, $async);
        }//end foreach
    }

//end loadPlugin

    /**
     * addAsideWidget - call addAsideWidget from $this->objAsideWidgets to add another widget
     *
     * @param AsideWidget $objAsideWidget
     * @return void
     * @throws \HybridCMS\Exception
     */
    public function addAsideWidget(&$objAsideWidget) {

        try {

            //call addAsideWidget from $this->objAsideWidgets to add another widget
            $this->objAsideWidgets->addAsideWidget($objAsideWidget);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * getAsideWidgets - calls getAsideWidgets from $this->objAsideWidgets
     * @param String $positionName
     * @return AsideWidgets
     * @throws \HybridCMS\Exception
     */
    public function getAsideWidgets($positionName) {

        try {

            //call getAsideWidgets from $this->objAsideWidgets
            return $this->objAsideWidgets->getAsideWidgets($positionName);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * isCurrDoc - chacks if a document is the current
     * document that the user is visiting
     * 
     * returns css-classname 'active' if the document is the current one
     * returns css-classname 'parent' if the document is a child of the curernt path
     * returns css-classname 'notActive' if the document is not a child and not the current document
     *  
     * @param pathToDoc
     * @return String 
     */
    public function isCurrDoc($pathToFile) {

        //get the path to the submitted file
        $tmpPath = str_replace('/index.' . HYB_FILETYPE, '', $pathToFile);

        //check if current document is in a folder
        if (HYB_CURRELURL === $pathToFile) {

            return 'active';
        } else if (stripos(HYB_CURRPATH, $tmpPath) !== false) {

            return 'parent';
        } else {

            return 'notActive';
        }
    }

    /**
     * 
     * @param String $originalPathToImage
     * @param Integer $newPxWidth
     * @return String
     * @throws \HybridCMS\Exception
     */
    public function scaleImg($originalPathToImage, 
            $newPxWidth, $newImgQuality = 60) 
    {
        try 
        {
            $objImage = new \HybridCMS\Modules\Image(
                    substr(HYB_ROOT, 0, -1) . $originalPathToImage);
            
            $objImage->setNewImgQuality($newImgQuality);          
            return $objImage->scale($newPxWidth);
            
        } 
        catch (\Exception $e) 
        {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }
    }

    /**
     * 
     * @param String $originalPathToImage
     * @param Integer $newPxWidth
     * @return String
     * @throws \HybridCMS\Exception
     */
    public function cropImg($originalPathToImage, $newPxWidth, $newPxHeight) {


        try {

            $objImage = new \HybridCMS\Modules\Image(substr(HYB_ROOT, 0, -1) . $originalPathToImage);
            return $objImage->crop($newPxWidth, $newPxHeight);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }
    }

    /**
     * getArticleByHeadline
     * 
     * @param String $headline
     * @return Article
     * @throws \Exception
     */
    public function getArticleByHeadline($headline) {

        if (!is_string($headline)) {
            throw new \Exception(
            "Error Processing Request: getArticleByHeadline(), headline must be of type String.", 1);
        }

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get ArticleId of the curernt Article
            $objDBArticle = new \HybridCMS\Database\DBArticle();
            $objArticle = $objDBArticle->selectArticleByHeadline($db, $headline);

            //check if article of given url exists
            if (empty($objArticle)) {
                throw new \Exception(
                'Error Processing Request: getArticleByHeadline(),
                           article does not exist.', 1);
            }

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            return $objArticle;
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }
    }

    /**
     * getLatestNArticlesOfCategory
     * 
     * @param String catName
     * @param Int numberOfArticles
     * @return Article[]
     * @throws \Exception
     */
    public function getLatestNArticlesOfCategory($catName, $numberOfArticles) {

        if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $catName)) {
            throw new \Exception(
            "Error Processing Request: getLatestNArticlesOfCategory(), catname is not valid.", 1);
        }

        if (!is_int($numberOfArticles) || $numberOfArticles > 100) {
            throw new \Exception(
            "Error Processing Request: getLatestNArticlesOfCategory(), numberOfArticles is not valid.", 1);
        }

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get ArticleId of the curernt Article
            $objDBSection = new \HybridCMS\Database\DBSection();
            $arrObjArticles = $objDBSection->selectLatestNArticlesByCategory($db, $catName, $numberOfArticles);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            return $arrObjArticles;
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }
    }

    /**
     * fetchAuthor
     * @param String $username
     * @return \HybridCMS\Admin\Auth\User
     * @throws \Exception
     */
    public function fetchAuthor($username) {
        
        if (!preg_match('/^[a-zA-Z0-9_\-\.\+\s\-]+$/', $username) || strlen($username) > 45) {
            throw new \Exception(
            'Error Processing Request: fetchAuthor(), $username is not valid.', 1);
        }

        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get ArticleId of the curernt Article
            $objDBAuth = new \HybridCMS\Database\DBAuth();
            $objUser = $objDBAuth->selectAuthorByUsername($db, $username);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            return $objUser;
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }
    }
    
    /**
     * fetchPoll
     * @param Integer $pollId
     * @return Poll
     * @throws \Exception
     */
    public function fetchPoll($pollId) {
        
        if (!is_numeric($pollId) || $pollId < 0) {
            throw new \Exception(
            'Error Processing Request: fetchPoll(), $pollId is not valid.', 1);
        }
        
        try {

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::getFactory()->getConnection();

            //get ArticleId of the curernt Article
            $objDBPoll = new \HybridCMS\Plugins\Poll\Database\DBPoll();
            $objPoll = $objDBPoll->selectPollByPollId($db, $pollId);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            return $objPoll;
        } catch (\Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");
        }        
    }    
    
    /**
     * Checks if the client device is a mobile device
     * @return Bolean
     */
    public function clientIsMobile(){
        
        if(true === empty($this->objMobileDetect))
        {
            $this->objMobileDetect = new \HybridCMS\Modules\MobileDetect
                    \MobileDetect();
        }
        
        return (true === $this->objMobileDetect->isMobile())
                && 
               (false === $this->objMobileDetect->isTablet());
        
    }
    
    /**
     * Checks if the clients device is a Tablet
     * @return Boolean
     */
    public function clientIsTablet()
    {
        if(true === empty($this->objMobileDetect))
        {
            $this->objMobileDetect = new \HybridCMS\Modules\MobileDetect
                    \MobileDetect();
        }
        
        return $this->objMobileDetect->isTablet();
    }
    
    /**
     * Checks if the clients device is a desktop
     * @return String
     */
    public function clientIsDesktop()
    {
        return (false === $this->clientIsMobile()) 
                && 
               (false === $this->clientIsTablet());
    }
    
    /**
     * Check if the client has requested an amp page
     */
    public function isAmpRequested() {
        return isset($_GET['amp']);
    }
    
    /**
     * encode array to jsonld
     * @param string[] $arrJsonLD
     * @return string
     * @throws \Exception
     */
    public function arrayToJsonLD($arrJsonLD) {
        //check if article of given url exists
        if (false === is_array($arrJsonLD)) {
            throw new \Exception(
            'Error Processing Request: $arrJsonLD(),
                       $arrJsonLD does not exist.', 1);
        }
        
        $op = '';
        $op .= '<script type="application/ld+json">';
        $op .= json_encode($arrJsonLD, 
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $op .= '</script>';
        
        return $op;
    }
    
    /**
     * Returns device type as String
     * @return string
     */
    public function getDeviceType()
    {
        $deviceType = 'desktop';
        
        if($this->clientIsTablet())
        {
            $deviceType = 'tablet';
        }
        else if($this->clientIsMobile())
        {
            $deviceType = 'mobile';
        }
        
        return $deviceType;
    }
        
    /**
     * Checks BrowserName
     * @param String $browserName
     * @return boolean
     */
    public function isBrowser($browserName) {
        if(true === empty($this->objMobileDetect))
        {
            $this->objMobileDetect = new \HybridCMS\Modules\MobileDetect
                    \MobileDetect();
        }
        
        if($browserName === 'firefox') {
            return $this->objMobileDetect->isFirefox();
        }
        
        if($browserName === 'safari') {
            return $this->objMobileDetect->isSafari();
        }   
        
        if($browserName === 'ie') {
            return $this->objMobileDetect->isIE();
        }    
        
        if($browserName === 'opera') {
            return $this->objMobileDetect->isOpera();
        }               
        
        if($browserName === 'chrome') {
            return $this->objMobileDetect->isChrome();
        }
        
        return false;
    }
    
    /**
     * Returns the coresponding value of a given StringName
     * @param String $stringName
     * @param Boolean $escape - htmlspecialchars the string before returning it
     */
    public function getString($stringName, $escape = true) 
    {              
        if(true === empty($this->objStrings))
        {
            $xmlFilePath = HYB_ROOT 
                    . "/HybridCMS/Content/Strings/strings-" 
                    . HYB_LANG. ".xml";

            $this->objStrings = 
                    new \HybridCMS\Content\Strings\Strings($xmlFilePath);
        }
        
        $string = $this->objStrings->getString($stringName);
                
        if(true === $escape)
        {
            $string = htmlspecialchars($string);
        }
        
        return $string;
    }
    
    /**
     * Checks if a content String exists in the language specific xml-file
     * for content Strings.
     * @param type $string
     * @return boolean
     */
    public function hasString($stringName)
    {
        $string = $this->getString($stringName);
        
        if(true === empty($string))
        {
            return false;
        }
        else 
        {
            return true;
        }
    }
    
    /**
     * Wrapper for mamcached. Add or update an object.
     * @param mixed $mixed
     * @throws \Exception
     */
    public function updateMemcached($key, $mixed) 
    {
        if(true === empty($this->objMemcached)) {
            $this->objMemcached = new \Memcache();
            $cacheAvailable = $this->objMemcached->connect(
                    MEMCACHED_HOST, MEMCACHED_PORT);
        }
        
        if(false === $cacheAvailable) {
            throw new \Exception(
            'Error Processing Request: '
                    . 'updateMemcached(), Cache is not available.', 1);
        }
        
        $this->objMemcached->set($key, $mixed);        
    }
    
    /**
     * Wrapper for mamcached. Selects an object from the mamcached.
     * @param String $key
     * @throws \Exception
     */
    public function getMemcached($key) 
    {
        if(true === empty($this->objMemcached)) {          
            $this->objMemcached = new \Memcache();
            $cacheAvailable = $this->objMemcached->connect(
                    MEMCACHED_HOST, MEMCACHED_PORT);
        }
        
        if(false === $cacheAvailable) {
            throw new \Exception(
            'Error Processing Request: '
                    . 'getMemcached(), Cache is not available.', 1);
        }
        
        $mixed = $this->objMemcached->get($key);     
        if(true === empty($mixed)) {
            throw new \Exception(
            'Error Processing Request: '
                    . 'Reslt is empty: ' . $mixed, 1);
        }
        return $mixed;
    }   
    
    /**
     * Returns the host-url of an Google Drive file
     * @param String $id
     */
    public function getDriveUrl($fileId)
    {
        if(false === ctype_alnum($fileId)) {
            throw new \Exception(
            'Error Processing Request: '
                    . '$fileId is not valid: ' . $fileId, 1);
        }
        
        return "https://drive.google.com/uc?export=view&id=" . $fileId;
    }
    
    /**
     * Returns the host-url of an Google Drive file
     * @param String $id
     */
    public function getDropboxUrl($filename)
    {
        $pattern = "/[a-zA-Z0-9\-_]\.[a-zA-Z]{0,4}/";
        if(0 === preg_match($pattern, $filename)) 
        {
            throw new \Exception(
            'Error Processing Request: '
                    . '$filename is not valid: ' . $filename, 1);
        }
        
        return "https://dl.dropboxusercontent.com/u/3833534/cdn/" . $filename;
    }      
}
?>
