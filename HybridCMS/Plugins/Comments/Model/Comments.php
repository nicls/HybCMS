<?php

namespace HybridCMS\Plugins\Comments\Model;

/**
 * class Comments
 *
 * @package Comments
 * @version 2.0
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Comments
{
    /**
     * arraylist of comments
     * @var Comment[]
     */
    private $arrObjComments;

    /**
     * url of the Comments url
     * @var String
     */
    private $url;

    /**
     * __construct
     *
     * @param String $url
     * @throws \HybridCMS\Plugins\Comments\Exception
     */
    public function __construct($url) {

        try {

            //call parent constructor
            parent::__construct();

            //set url
            $this->setUrl($url);

            //open Database-Connection
            $db = \HybridCMS\Database\DatabaseFactory::
                    getFactory()->getConnection();

            //fetch comments by URL from Database
            $objDBComments = new \HybridCMS\Database\DBComments();
            $this->arrObjComments = $objDBComments->
                    selectPublishedCommentsByUrl($db, $this->url);

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

        } catch (Exception $e) {

            //close Database-Connection
            \HybridCMS\Database\DatabaseFactory::
                    getFactory()->closeConnection();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                    \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    /**
     * setUrl
     *
     * @param String $url
     * @return void
     */
    private function setUrl($url) {

        //check if url is valid
        if (!\HybridCMS\Helper\Helper::isValidURL($url)) {
            throw new \Exception(
                    "Error Processing Request: setUrl(),
                       url is not valid.", 1);
        }

        //check if url is from the current host
        if (!stripos($url, HYB_PROTOCOL . HYB_HOST_NAME) === 0) {
            throw new \Exception(
                    "Error Processing Request: setUrl(),
                       url must be from the current host.", 1);
        }

        $this->url = $url;
    }

    /**
     * setArrObjComments
     * @param Comment[] $arrObjComments
     * @throws \Exception
     */
    public function setArrObjComments($arrObjComments) {

        //check if parameter is an Array
        if(!is_array($arrObjComments)) {
            throw new \Exception(
                "Error Processing Request: setArrObjComments(),
                    'arrObjComments must be an Array.", 1);
        }

        //check if param is of type Comment[]
        foreach ($arrObjComments as &$objComment) {
            if(!($objComment instanceof \HybridCMS\Plugins\Comments\Comment)) {
                throw new \Exception(
                    "Error Processing Request: setArrObjComments(),
                        'arrObjComments must be of type Comment[].", 1);
            }
        }

        $this->arrObjComments = $arrObjComments;
    }

    /**
     * printCommentForm
     *
     * @param void
     * @return void
     */
    public function printCommentForm() {

        $form = '<form id="hybCommentForm" action="#" method="POST">';

        //action
        $form .= '<input type="hidden" name="hybCommentAction" value="comments" />';

        //url
        $form .= '<input type="hidden" name="hybCommentUrl" value="' . htmlentities($this->url) . '" />';

        //Commentator Name
        $form .= '<div id="msgHybCommentatorName" class="hybCommentMsg text-warning"></div>';
        $form .= '<input class="form-control add-bottom-20" type="text" name="hybCommentName" placeholder="Name" required="required" />';

        //Email
        $form .= '<div id="msgHybEmail" class="hybCommentMsg text-warning"></div>';
        $form .= '<input class="form-control add-bottom-20" type="text" name="hybCommentEmail" placeholder="Email" required="required" />';

        //Website
        $form .= '<div id="msgHybWebsite" class="hybCommentMsg text-warning"></div>';
        $form .= '<input class="form-control add-bottom-20" type="text" name="hybCommentWebsite" placeholder="Webseite" />';

        //Comment
        $form .= '<div id="msgHybComment" class="hybCommentMsg text-warning"></div>';
        $form .= '<textarea class="form-control add-bottom-20" name="hybComment" placeholder="Kommentar eingeben ..." required="required"></textarea>';

        //general Messagebox
        $form .= '<div id="msgGeneral" class="hybCommentMsg text-warning"></div>';

        //submit-button
        $form .= '<button class="btn btn-success float_right" type="submit">Kommentar speichern</button>';

        $form .= '</form>';

        echo $form;
    }

    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {

        $string = '';

        //concatinate Comments
        foreach ($this->arrObjComments as &$objComment) {
            $string .= $objComment->toString();
        }

        return $string;
    }
}
?>