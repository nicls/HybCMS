<?php

namespace HybridCMS\Plugins\Comptable;

/**
 * class Dataset
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Dataset {
    
    /**
     * Key
     * @var String
     */
    private $key;
    
    /**
     * Value correspondig to a key
     * @var String
     */
    private $value;
    
    /**
     * A note belonging to this Dataset
     * @var String
     */
    private $note;
    
    /**
     * Indicates if this Dataset is private (for external editors)
     * @var Boolean
     */
    private $private;
    
    /**
     * Indicates the time the dataset was created
     * @var Integer 
     */
    private $created;
    
    
    /**
     * Indicates the time the dataset was changed last
     * @var Integer 
     */
    private $lastChanged;
    
    /**
     * __construct
     * @param String $key
     * @param String $value
     * @throws Exception
     */
    public function __construct($key, $value) {
        try {           
            
            $this->setKey($key);
            $this->setValue($value);
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * setKey
     * @param String $key
     * @throws \Exception
     */
    public function setKey($key) {    
        
        //check if $key is an String width 45 charekters length in maximum
        if(!is_string($key) 
                || !preg_match('/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/', $key) 
                ||  strlen($key) > 45) {
                
            throw new \Exception(
                "Error Processing Request: setKey(),
                    'key must be an String width 45 charekters length in maximum.'", 1);
        }
        
        $this->key = $key;
    }

    /**
     * setValue
     * @param String $value
     * @throws \Exception
     */
    public function setValue($value) 
    {                    
        //check if $value is an String width 255 charekters length in maximum
        if(!is_string($value) 
                || !preg_match('/^[a-zA-Z0-9öäüÖÄÜß\"°€%&\.,\-\:_\+\s\/\(\)]+$/', $value) 
                ||  strlen($value) > 255) {
                
            throw new \Exception(
                "Error Processing Request: setValue(),
                    'value must be an String width 255 charekters length in maximum.'", 1);
        }
        
        $this->value = $value;
    }

    /**
     * setNote
     * @param String $note
     * @throws \Exception
     */
    public function setNote($note) {
        
        //check if $note is an String width 255 charekters length in maximum
        if(!is_string($note) ||  strlen($note) > 255) {
                
            throw new \Exception(
                "Error Processing Request: setNote(),
                    'note must be an String width 255 charekters length in maximum.'", 1);
        }
        
        $this->note = $note;
    }

    /**
     * setPrivate
     * @param Boolean $private
     * @throws \Exception
     */
    public function setPrivate($private) {
        
        //check if $private is an boolean
        if(!is_bool($private)) {
            throw new \Exception(
                "Error Processing Request: setPrivate(),
                    'private must be a Boolean.'", 1);
        }
        
        $this->private = $private;
    }
    
    /**
     * setCreated
     * @param Integer $created
     * @throws \Exception
     */
    public function setCreated($created) {
        
        //check if $imgUrl is valid
        if (!is_numeric($created) || $created < 1386457427) {
            throw new \Exception(
            "Error Processing Request: setCreated(),
                        created is not valid.", 1);
        }
        
        $this->created = $created;
    }
    
    /**
     * setLastChanged
     * @param Integer $lastChanged
     * @throws \Exception
     */
    public function setLastChanged($lastChanged) {
        
        //check if $imgUrl is valid
        if (!is_numeric($lastChanged) || $lastChanged < 1386457427) {
            throw new \Exception(
            "Error Processing Request: setLastChanged(),
                        lastChanged is not valid.", 1);
        }
        
        $this->lastChanged = $lastChanged;
    }
    
    public function getLastChanged() {
        return $this->lastChanged;
    }

    public function getCreated() {
        return $this->created;
    }
    
    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }

    public function getNote() {
        return $this->note;
    }

    public function getPrivate() {
        return $this->private;
    }
}

?>