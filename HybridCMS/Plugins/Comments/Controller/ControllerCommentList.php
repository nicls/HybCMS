<?php

namespace HybridCMS\Plugins\Comments\Controller;

/**
 * class ControllerCommentList handles all requests to list comments for a
 * specific url.
 *
 * @package Plugins\Comments\Controller
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerCommentList extends 
    \HybridCMS\Plugins\Comments\Controller\ControllerComments 
{   
    /**
     * __construct
     * @param mixed[] $arrParams
     */    
    public function __construct($arrParams) 
    {               
        //call constructor of parent class
        parent::__construct($arrParams);     
        
        //check if a valid url is passed through
        if(false === isset($arrParams['hyb_comments_url']))
        {
            throw new \InvalidArgumentException(
                "Error Processing Request: __construct(),
                    'url is not given.'", 1);
        }
        
        //the comments_url with a fragment is used to differ between differnet 
        //comment forms on the same website
        $this->setObjUrl($arrParams['hyb_comments_url']);  
        
        //add objUrl to params
        $this->arrParams['objUrl'] = $this->objUrl;        
                
        //handle Request sent by the client
        $this->handleRequest();           
               
    }    

    /**
     * handle Requests sent by the user
     */
    protected function handleRequest()
    {     
        if (false) 
        {        
            //do something
        }
        else //default action 
        {   
            $this->handleActionListComments();
        }        
    }
    
    /**
     * prints the list of comments for a given url.
     */
    private function handleActionListComments()
    {
        assert(true === isset($this->objUrl));
        assert(false === empty($this->objUrl));
        
        //attach each InputContent submitted by 
        //the client to an FormElementGroup
        //to print the form later in the view
        $this->createFormElementGroups();  
        
        //add attribute name to the submit button depending on the url fragment
        if(true === isset($this->arrParams['objButtonListComments']))
        {
            $this->arrParams['objButtonListComments']->
                    setFieldName(htmlentities($this->objUrl->getUrl()));
        }   
        
        try 
        {
            //get Comments from DB
            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory
                    ::getFactory()->getConnection();

            $objDBComments = new \HybridCMS\Plugins\Comments\Database
                    \DBComments();

            //get all comments for the current url
            $arrObjComments = $objDBComments->selectPublishedCommentsByUrl($db, 
                    $this->objUrl->getUrl());
            
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            //pass comments to the view
            $this->arrParams['arrObjComments'] = $arrObjComments;

            //set view
            $this->objView = new \HybridCMS\Plugins\Comments\View
                    \ViewCommentList($this->arrParams); 
        
        }
        catch(\Exception $e)
        {
            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory
            ::getFactory()->closeConnection();
            
            throw $e;
        }        
        
        /**
         * JS
         * 
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         * 6. $position
         * 7. $async
         */
        $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                        'comments',
                        '/HybridCMS/Plugins/Comments/js/f.js',
                        15,
                        true,
                        true,
                        'footer',
                        true
        );
        $this->addObjJSResource($objJSResource1);             

        /**
         * CSS
         *
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         */
        $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                        'comments',
                        '/HybridCMS/Plugins/Comments/css/f.css',
                        15,
                        true,
                        true
                );
        $this->addObjCSSResource($objCSSResource);        
    }

}
