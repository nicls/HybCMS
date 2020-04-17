<?php

namespace HybridCMS\Plugins\User\Model;

//inclues
require_once(HYB_ROOT . 'HybridCMS/Modules/PHPMailer/class.phpmailer.php');

/**
 * Description of UserContact
 *
 * @author cls
 */
class UserContact {

    /**
     * User to contact
     * @var User
     */
    private $objUser;

    /**
     * Content of the mail
     * @var String
     */
    private $mailContent;

    /**
     * __construct
     * @param User $objUser
     * @throws \Exception
     */
    public function __construct($objUser) {

        try {

            $this->setObjUser($objUser);
            
        } catch (\Exception $e) {

            throw $e;
        }
    }

    /**
     * Sends a confirmation email to the user that tryed to register
     */
    public function sendConfirmationEmailRegistration() {

        $objMailer = new \PHPMailer(); // defaults to using php "mail()"
        $objMailer->CharSet = 'UTF-8';

        $body = file_get_contents(HYB_ROOT 
                . 'HybridCMS/Plugins/User/View/Mails/confirmRegistration.html');
        
        $pattern = '/\\\/i';
        $body = preg_replace($pattern, '', $body);
        
        //personalize Email
        $body = $this->personalizeContent($body);
        
        $objMailer->AddReplyTo("noreply@" . HYB_HOST_NAME, HYB_HOST_NAME);
        $objMailer->SetFrom('noreply@' . HYB_HOST_NAME, HYB_HOST_NAME);

        $email = $this->objUser->getEmail();
        $objMailer->AddAddress($email);

        $objMailer->Subject = "Registrierung bestätigen";

        $objMailer->AltBody = "To view the message, please use an HTML "
                . "compatible email viewer!"; // optional, comment out and test

        $objMailer->MsgHTML($body);               

        if (false === $objMailer->Send()) 
        {
            throw new \Exception(
            'Error Processing Request:
                            sendConfirmationEmailRegistration(),                    
                            Mailer Error: ' . $objMailer->ErrorInfo, 1);
        }
    }
    
    /**
     * Sends a confirmation email to the user that tryed to register
     */
    public function sendConfirmationEmailUpdateEmail() {

        $objMailer = new \PHPMailer(); // defaults to using php "mail()"
        $objMailer->CharSet = 'UTF-8';

        $body = file_get_contents(HYB_ROOT . 'HybridCMS/Plugins/User/'
                . 'View/Mails/confirmUpdateEmail.html');
        
        $pattern = '/\\\/i';
        $body = preg_replace($pattern, '', $body);
        
        //personalize Email
        $body = $this->personalizeContent($body);
        
        $objMailer->AddReplyTo("noreply@" . HYB_HOST_NAME, HYB_HOST_NAME);
        $objMailer->SetFrom('noreply@' . HYB_HOST_NAME, HYB_HOST_NAME);

        $email = $this->objUser->getEmail();
        $objMailer->AddAddress($email);

        $objMailer->Subject = "Änderung Ihrer E-Mail bestätigen";

        $objMailer->AltBody = "To view the message, please use an HTML "
                . "compatible email viewer!"; // optional, comment out and test

        $objMailer->MsgHTML($body);               

        if (false === $objMailer->Send()) 
        {
            throw new \Exception(
            'Error Processing Request:
                            sendConfirmationEmailUpdateEmail(),                    
                            Mailer Error: ' . $objMailer->ErrorInfo, 1);
        }
    }    
    
    /**
     * Sends a confirmation email to the user to reset his password
     */
    public function sendConfirmationEmailPasswordReset() {

        $objMailer = new \PHPMailer(); // defaults to using php "mail()"
        $objMailer->CharSet = 'UTF-8';

        $body = file_get_contents(HYB_ROOT . 'HybridCMS/Plugins/User/'
                . 'View/Mails/confirmPasswordReset.html');
        
        $pattern = '/\\\/i';
        $body = preg_replace($pattern, '', $body);
        
        //personalize Email
        $body = $this->personalizeContent($body);
        
        $objMailer->AddReplyTo("noreply@" . HYB_HOST_NAME, HYB_HOST_NAME);
        $objMailer->SetFrom('noreply@' . HYB_HOST_NAME, HYB_HOST_NAME);

        $email = $this->objUser->getEmail();
        $objMailer->AddAddress($email);

        $objMailer->Subject = "Zurücksetzung des Kennworts";

        $objMailer->AltBody = "To view the message, please use an HTML "
                . "compatible email viewer!"; // optional, comment out and test

        $objMailer->MsgHTML($body);                       

        if (false === $objMailer->Send()) 
        {
            throw new \Exception(
            'Error Processing Request:
                            sendConfirmationEmailRegistration(),                    
                            Mailer Error: ' . $objMailer->ErrorInfo, 1);
        }
    }
    
    
    /**
     * personalize Content
     * @param String $body
     * @return String Personalised Content
     */
    private function personalizeContent($body) {
        
        //add E-Mail
        $email = $this->objUser->getEmail();
        if(false === empty($email))
        {
            $pattern = '/\(HYB_EMAIL\)/';
            $body = preg_replace($pattern, $email, $body);
        }        
        
        //add Username
        $username = $this->objUser->getUsername();
        if(false === empty($username))
        {
            $pattern = '/\(HYB_USERNAME\)/';
            $body = preg_replace($pattern, $username, $body);
        }
        
        //add firstname
        $firstname = $this->objUser->getFirstname();
        if(false === empty($firstname))
        {
            $pattern = '/\(HYB_FIRSTNAME\)/';
            $body = preg_replace($pattern, $firstname, $body);
        }
        
        //add lastname
        $lastname = $this->objUser->getLastname();
        if(false === empty($lastname))
        {
            $pattern = '/\(HYB_LASTNAME\)/';
            $body = preg_replace($pattern, $lastname, $body);
        }
        
        //add gender
        $gender = $this->objUser->getGender();
        if(false === empty($gender)) 
        {
            if('w' === $gender) {
                $gender = 'Frau';
            } else {
                $gender = 'Herr';
            }
            
            $pattern = '/\(HYB_GENDER\)/';
            $body = preg_replace($pattern, $gender, $body);
        }
        
        //add verificationToken
        $verificationToken = $this->objUser->getVerificationToken();
        if(false === empty($verificationToken)) 
        {
            $pattern = '/\(HYB_VERIFICATIONTOKEN\)/';
            $body = preg_replace($pattern, $verificationToken, $body);
        }        
        
        return $body;
    }

    /**
     * getObjUser
     * @return \HybridCMS\Plugins\User\Model\User
     */
    public function getObjUser() {
        return $this->objUser;
    }

    /**
     * setObjUser
     * @param \HybridCMS\Plugins\User\Model\User $objUser
     * @throws \Exception
     */
    public function setObjUser($objUser) {

        //check if $objUser is valid
        if (false === ($objUser instanceof \HybridCMS\Plugins\User\Model\User)) {
            throw new \Exception(
            'Error Processing Request:
                        setObjUser(),                    
                        $objUser is not a instance of User.', 1);
        }

        $this->objUser = $objUser;
    }

    /**
     * setMailContent
     * @param String $mailContent
     */
    public function setMailContent($mailContent) {

        //check if $mailContent is a string
        if (false === is_string($mailContent)) {
            throw new \Exception(
            'Error Processing Request:
                        setMailContent(),                    
                        $mailContent is not a string.', 1);
        }

        $this->mailContent = $mailContent;
    }

}
