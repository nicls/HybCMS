<?php

namespace HybridCMS\Plugins\Comments\Model;

//inclues
require_once(HYB_ROOT . 'HybridCMS/Modules/PHPMailer/class.phpmailer.php');

/**
 * Sends messages to the admin if a new comments is waiting for approval.
 *
 * @author cls
 */
class AdminContact {

    /**
     * Admin to contact
     * @var \HybridCMS\Admin\Auth\User
     */
    private $arrObjUser;
    
    /**
     * Comment that is newly submitted.
     * @var Comment
     */
    private $objComment;

    /**
     * Content of the mail
     * @var String
     */
    private $mailContent;

    /**
     * __construct
     * @param HybridCMS\Admin\Auth\User[] $objUser
     * @throws \Exception
     */
    public function __construct($arrObjUser, $objComment) {

        try {

            $this->setArrObjUser($arrObjUser);
            $this->setObjComment($objComment);
            
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /**
     * Sends a email to the admin that a new comment is waiting for approval.
     */
    public function sendEmailNewComment() 
    {                
        foreach($this->arrObjUser as $objUser)
        {    
            $objMailer = new \PHPMailer(); // defaults to using php "mail()"
            $objMailer->CharSet = 'UTF-8';

            $body = file_get_contents(HYB_ROOT 
                    . 'HybridCMS/Plugins/Comments/View/Mails/newComment.html');

            $pattern = '/\\\/i';
            $body = preg_replace($pattern, '', $body);
        
            //personalize Email
            $bodyPers = $this->personalizeContent($body, $objUser);

            $objMailer->AddReplyTo("noreply@" . HYB_HOST_NAME, HYB_HOST_NAME);
            $objMailer->SetFrom('noreply@' . HYB_HOST_NAME, HYB_HOST_NAME);

            $email = $objUser->getEmail();
            $objMailer->AddAddress($email);

            $objMailer->Subject = "Neuer Kommentar auf " . HYB_HOST_NAME;

            $objMailer->AltBody = "To view the message, please use an HTML "
                    . "compatible email viewer!"; // optional, comment out and test

            $objMailer->MsgHTML($bodyPers);  
                        
            if (false === $objMailer->Send()) 
            {
                throw new \Exception(
                'Error Processing Request:
                                sendEmailNewComment(),                    
                                Mailer Error: ' . $objMailer->ErrorInfo, 1);
            }
        }
    }    
    
    /**
     * personalize Content
     * @param String $body
     * @param $objUser \HybridCMS\Admin\Auth\User Current Admin User
     * @return String Personalised Content
     */
    private function personalizeContent($body, &$objUser) {
        
        assert(true === \HybridCMS\Util\VarCheck::issetAndNotEmpty(
                $this->objComment));
        
        //add E-Mail
        $email = $objUser->getEmail();
        if(false === empty($email))
        {
            $pattern = '/\(HYB_EMAIL\)/';
            $body = preg_replace($pattern, $email, $body);
        }        
        
        //add Username
        $username = $objUser->getUsername();
        if(false === empty($username))
        {
            $pattern = '/\(HYB_USERNAME\)/';
            $body = preg_replace($pattern, $username, $body);
        } 
        
        $comment = $this->objComment->getCommentFormatted();
        if(false === empty($comment))
        {
            $pattern = '/\(HYB_COMMENT\)/';
            $body = preg_replace($pattern, $comment, $body); 
        }
        
        $url = $this->objComment->getUrl();
        if(false === empty($url))
        {
            $pattern = '/\(HYB_URL\)/';
            $body = preg_replace($pattern, $url, $body); 
        }
        
        $userIp = htmlspecialchars($_SERVER['REMOTE_ADDR']);
        if(false === empty($userIp))
        {
            $pattern = '/\(HYB_USERIP\)/';
            $body = preg_replace($pattern, $userIp, $body); 
        }
        
        $objUserComment = $this->objComment->getObjUser();
        assert(false === empty($objUserComment));
        $commentator = $objUserComment->getUsername();        
        if(false === empty($commentator))
        {
            $pattern = '/\(HYB_COMMENTATOR\)/';
            $body = preg_replace($pattern, $commentator, $body); 
        }
        
        return $body;
    }

    /**
     * getArrObjUser
     * @return \HybridCMS\Auth\User[]
     */
    public function getArrObjUser() 
    {
        return $this->arrObjUser;
    }

    /**
     * setObjUser
     * @param \HybridCMS\Admin\Auth\User[] $arrObjUser
     * @throws \Exception
     */
    public function setArrObjUser($arrObjUser) 
    {
        foreach($arrObjUser as &$objUser)
        {
            //check if $objUser is valid
            if (false === ($objUser instanceof \HybridCMS\Admin\Auth\User)) 
            {
                throw new \Exception(
                'Error Processing Request:
                            setObjUser(),                    
                            $objUser is not a instance of Auth\User.', 1);
            }
        }

        $this->arrObjUser = $arrObjUser;
    }

    /**
     * setMailContent
     * @param String $mailContent
     */
    public function setMailContent($mailContent) 
    {

        //check if $mailContent is a string
        if (false === is_string($mailContent)) 
        {
            throw new \Exception(
            'Error Processing Request:
                        setMailContent(),                    
                        $mailContent is not a string.', 1);
        }

        $this->mailContent = $mailContent;
    }
    
    /**
     * setObjComment
     * @param \HybridCMS\Plugins\Comments\Model\Comment $objComment
     * @throws \InvalidArgumentException
     */
    private function setObjComment($objComment)
    {
        if(false === $objComment instanceof 
                \HybridCMS\Plugins\Comments\Model\Comment)
        {
                throw new \InvalidArgumentException(
                'Error Processing Request:
                                sendEmailNewComment(),                    
                                objComment is not valid.', 1);
        }
        
        $this->objComment = $objComment;        
    }

}
