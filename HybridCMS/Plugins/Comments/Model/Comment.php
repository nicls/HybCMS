<?php

namespace HybridCMS\Plugins\Comments\Model;

/**
 * class Comment
 *
 * @package Comment
 * @version 2.0
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Comment {

    /**
     * primaryKey of this comment in database
     * @var Integer
     */
    private $commentId;

    /**
     * url of the comment. e.g. http://hybcms.de/article.html#anchor
     * @var String
     */
    private $url;

    /**
     * comment-text
     * @var String
     */
    private $comment;

    /**
     * unix-timestamp when comment was saved in Database
     * @var Integer
     */
    private $timeCreated;

    /**
     * indicates if this comment is published on still in review
     * @var Boolean
     */
    private $published;
    
   /**
     * User representing the commentator
     * @var String
     */
    private $objUser; 
    
    /**
     * Replacer to format a comment prior to returning the comment by using 
     * getCommentFormatted()
     * @var Replacer[]
     */
    private $arrObjTextReplacer = array();

    /**
     * __construct
     *
     * @param String $commentatorName
     * @param String $email
     * @param String $comment
     */
    public function __construct($comment, $objUser) {

        //assign Attributes
        $this->setObjUser($objUser);
        $this->setComment($comment);
        
        //assign Replacer for the comment String
        $this->arrObjTextReplacer[] = new \HybridCMS\Modules\
                TextProcessing\Replacer\NewLine2Break();
    }

    /**
     * setCommentId
     *
     * @param Integer $commentId
     * @throws \Exception
     */
    public function setCommentId($commentId) {
                        
        //validate commentId
        if(!is_numeric($commentId) || $commentId < 1) {
            throw new \Exception(
                "Error Processing Request: setCommentId(),
                    'commentId must be an Integer greater than 0.'", 1);
        }

        $this->commentId = $commentId;
    }
    
    /**
     * setUrl
     * @param String $url
     */
    public function setUrl($url)
    {
        //check if a valid url is given
        if(
            true === empty($url)
            ||  
            false === is_string($url)
            ||
            false === \HybridCMS\Modules\Url\Url::isValidUrl($url)
          )
        {
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), no valid url given.', 1);
        }
        
        $objUrl = new \HybridCMS\Modules\Url\Url($url);
        assert(false === empty($objUrl));

        //check if url is internal
        if(false === $objUrl->urlIsInternal())
        {               
            throw new \InvalidArgumentException(
                'Error Processing Request:
                    setUrl(), url is not internal.', 1);  
        }
        
        $this->url = $objUrl->getUrl();
    }

    /**
     * setCommentatorName
     *
     * @param String $commentatorName
     * @throws \Exception
     */
    public function setObjUser($objUser) {

        //check if $commentatorName is an String width 45 charekters length in maximum
        if(false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) {
            throw new \Exception(
                'Error Processing Request: setObjUser(),
                    $objUser must be of type \HybridCMS\Plugins\User\Model
                    \User', 1);
        }

        $this->objUser = $objUser;
    }

    /**
     * setComment
     *
     * @param String $comment
     * @throws \Exception
     */
    public function setComment($comment) {

        //check if comemnt is an String width 10000 charekters 
        //length in maximum
        if(!is_string($comment) || strlen($comment) >= 20000) {
            throw new \Exception(
                "Error Processing Request: setComment(),
                    'comment must be an String width 20000 
                    charekters length in maximum.'", 1);
        }

        $this->comment = $comment;
    }

    /**
     * setTimeCreated
     *
     * @param Integer $timeCreated
     * @throws \Exception
     */
    public function setTimeCreated($timeCreated) {

        //check if time created is an Integer with 10 digits
        if(!is_numeric($timeCreated) || $timeCreated < 1361472873) {
            throw new \Exception(
                "Error Processing Request: setTimeCreated(),
                    'timeCreated must be a 10 digit Integer.'", 1);
        }

        $this->timeCreated = $timeCreated;
    }

    /**
     * setPublished
     *
     * @param Boolean $published
     * @throws \Exception
     */
    public function setPublished($published) {

        //check if published is an boolean
        if(!is_bool($published)) {
            throw new \Exception(
                "Error Processing Request: setPublished(),
                    'published must be a Boolean.'", 1);
        }

        $this->published = $published;
    }

    /**
     * Getter
     */
    public function getCommentId() { return $this->commentId; }
    public function getObjUser() { return $this->objUser; }
    public function getComment() { return $this->comment; }
    public function getTimeCreated() { return $this->timeCreated; }
    public function getPublished() { return $this->published; }
    public function getUrl() { return $this->url; }
    
    /**
     * Return a formatted user comment.
     * @return String
     */
    public function getCommentFormatted()
    {
        $cmnt = htmlentities($this->comment);
        
        foreach($this->arrObjTextReplacer as $objReplacer)
        {
            $cmnt = $objReplacer->replace($cmnt);
        }
        
        return $cmnt;
    }

}
?>