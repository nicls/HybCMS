<?php

namespace HybridCMS\Plugins\Comptable\Database;

/**
 * class DBComptable
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DBComptable {

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {
        
    }

    /**
     * insertComptable
     * 
     * @param mysqli $db
     * @param string $comptableName
     * @return boolean
     */
    public function insertComptable($db, $comptableName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_comptable (
		comptable_name) VALUES (?)';


            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $comptableName);

            $success = $stmt->execute();

            //close Resources
            $stmt->close();

            return $success;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * insertTable
     * 
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return boolean
     */
    public function insertTable($db, &$objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing insertTable:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) < 1) {
                throw new \Exception(
                "Error Processing insertTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if $arrObjTables has an instance of Table assigned
            if (!($objTable instanceof \HybridCMS\Plugins\Comptable\Table)) {
                throw new \Exception(
                "Error Processing insertTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            //get values
            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $isFavorit = (int) $objTable->getIsFavorit();
            $tableNote = $objTable->getNote();
            $url = $objTable->getUrl();
            $imgUrl = $objTable->getImgUrl();
            $created = time();

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_comptable_tables (
                table_name,
		comptable_name,
                is_favorit,
                note,
                url,
                imgUrl,
                created) VALUES (?,?,?,?,?,?,?)';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('ssisssi', $tableName, $comptableName, $isFavorit, $tableNote, $url, $imgUrl, $created
            );

            $success = $stmt->execute();

            //close Resources
            $stmt->close();

            return $success;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * insertDataset
     * 
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return boolean
     */
    public function insertDataset($db, &$objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) < 1) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if $arrObjTables has an instance of Table assigned
            if (!($objTable instanceof \HybridCMS\Plugins\Comptable\Table)) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $arrObjDatasets = $objTable->getArrObjDatasets();

            //check if $arrObjDatasets is not empty
            if (count($arrObjDatasets) < 1) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Dataset assigned.", 1);
            }

            $objDataset = array_shift($arrObjDatasets);

            //check if $objDataset has an instance of Dataset assigned
            if (!($objDataset instanceof \HybridCMS\Plugins\Comptable\Dataset)) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            //get values
            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $key = $objDataset->getKey();
            $value = $objDataset->getValue();
            $private = (int) $objDataset->getPrivate();
            $note = $objDataset->getNote();
            $created = time();
            $lastChanged = NULL;

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_comptable_datasets (
                hyb_comptable_datasets.key,
                table_name,
                comptable_name,
                value,
                note,
                private,
                created,
                lastChanged
                ) VALUES (?,?,?,?,?,?,?,?)';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('sssssiii', $key, $tableName, $comptableName, $value, $note, $private, $created, $lastChanged
            );

            $success = $stmt->execute();

            //close Resources
            $stmt->close();

            return $success;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * bulkInsertDatasets
     * 
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return boolean
     */
    public function bulkInsertDatasets($db, &$objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) < 1) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //get Datasets
            $arrObjDatasets = $objTable->getArrObjDatasets();

            //check if $arrObjDatasets is not empty
            if (count($arrObjDatasets) < 1) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Dataset assigned.", 1);
            }


            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'INSERT INTO hyb_comptable_datasets (
                hyb_comptable_datasets.key,
                table_name,
                comptable_name,
                value,
                note,
                private,
                created,
                lastChanged
                ) VALUES (?,?,?,?,?,?,?,?)';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }


            $stmt->bind_param('sssssiii', 
                    $key, 
                    $tableName, 
                    $comptableName, 
                    $value, 
                    $note, 
                    $private, 
                    $created, 
                    $lastChanged
            );

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
                
            foreach ($arrObjDatasets as $objDataset) {

                //get values
                $key = $objDataset->getKey();
                $value = $objDataset->getValue();
                $private = (int) $objDataset->getPrivate();
                $note = $objDataset->getNote();
                $created = time();
                $lastChanged = NULL;

                $success = $stmt->execute();
                
                if($success === false) {
                    break;
                }
            }

            //close Resources
            $stmt->close();

            return $success;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * selectAllComptables
     * @param mysqli $db
     * @return Comptable[]
     * @throws Exception
     */
    public function selectAllComptables($db) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'SELECT comptable_name FROM hyb_comptable';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->execute();
            $stmt->bind_result($comptableName);

            //array to hold the Articles fot this category
            $arrObjComptables = array();

            //fetch the articles
            while ($stmt->fetch()) {

                //add each Article to $arrArticles
                array_push($arrObjComptables, new \HybridCMS\Plugins\Comptable\Comptable($comptableName)
                );
            }

            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrObjComptables;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end selectAllComptables

    /**
     * selectComptableByComptableName
     * @param mysqli $db
     * @return Comptable
     * @throws Exception
     */
    public function selectComptableByComptableName($db, $comptableName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            //create new Comptable
            $objComptable = new \HybridCMS\Plugins\Comptable\Comptable($comptableName);

            //get Tables
            $arrObjTables = $this->selectArrObjTablesByComptableName($db, $comptableName);

            foreach ($arrObjTables as &$objTable) {
                $objComptable->addTable($objTable);
            }

            //close Resources
            $stmt->close();

            //return all Comptables
            return $objComptable;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end selectComptableByComptableName    

    /**
     * selectComptableOrderedByLastChanged
     * @param mysqli $db
     * @return String[]
     * @throws Exception
     */
    public function selectChangesOrderedByLastChangedByComptableName($db, $comptableName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $arrDatasets = array();

            $sql = 'SELECT 
                comptable_name,
                table_name, 
                hyb_comptable_datasets.key, 
                value, 
                lastChanged
                    FROM hyb_comptable_datasets
                        WHERE comptable_name = ?
                        AND lastChanged > 0
                            ORDER BY lastChanged DESC LIMIT 12';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $comptableName);
            $stmt->execute();
            $stmt->bind_result(
                    $comptableName, $tableName, $key, $value, $lastChanged
            );


            //fetch the articles
            while ($stmt->fetch()) {

                $arrDatasets[] = array(
                    'tableName' => $tableName,
                    'key' => $key,
                    'value' => $value,
                    'lastChanged' => $lastChanged
                );
            }//end while
            //close Resources
            $stmt->close();

            //return all Datasets
            return $arrDatasets;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

//end selectComptableOrderedByLastChanged   

    /**
     * selectArrObjTablesOrderedByCreatedByComptableName
     * @param mysqli $db
     * @return Table[]
     * @throws Exception
     */
    public function selectArrObjTablesOrderedByCreatedByComptableName($db, $comptableName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $arrObjTables = array();

            $sql = 'SELECT 
                comptable_name,
                table_name, 
                is_active,
                is_favorit, 
                note, 
                url, 
                imgUrl,
                created
                FROM hyb_comptable_tables
                WHERE comptable_name = ? 
                AND created > 0
                AND is_active = 1
                ORDER BY created DESC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $comptableName);
            $stmt->execute();
            $stmt->bind_result(
                    $comptableName, 
                    $tableName, 
                    $isActive,
                    $isFavorit, 
                    $tableNote, 
                    $url, 
                    $imgUrl, 
                    $tableCreated
            );


            //fetch the articles
            while ($stmt->fetch()) {

                //create new Table if table does not exist
                if (!isset($arrObjTables[$tableName])) {

                    $arrObjTables[$tableName] = new \HybridCMS\Plugins\Comptable\Table($tableName);

                    $isFavorit = (bool) $isFavorit;
                    $isActive = (bool) $isActive;
                    $arrObjTables[$tableName]->setIsActive($isActive);
                    $arrObjTables[$tableName]->setIsFavorit($isFavorit);
                    $arrObjTables[$tableName]->setCreated($tableCreated);

                    if (!empty($tableNote)) {
                        $arrObjTables[$tableName]->setNote($tableNote);
                    }

                    if (!empty($url)) {
                        $arrObjTables[$tableName]->setUrl($url);
                    }

                    if (!empty($imgUrl)) {
                        $arrObjTables[$tableName]->setImgUrl($imgUrl);
                    }
                }
            }//end while
            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrObjTables;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    /**
     * selectComptableByComptableName
     * @param mysqli $db
     * @return Table[]
     * @throws Exception
     */
    public function selectArrObjActiveTablesByComptableName($db, $comptableName) 
    {         
        //statement-Object
        $stmt = null;
        
        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $arrObjTables = array();

            $sql = 'SELECT 
                hyb_comptable_datasets.comptable_name,
                hyb_comptable_datasets.table_name, 
                hyb_comptable_datasets.key, 
                hyb_comptable_datasets.value, 
                hyb_comptable_datasets.note AS dataset_note, 
                hyb_comptable_datasets.private, 
                hyb_comptable_datasets.created,
                hyb_comptable_datasets.lastChanged,                
                hyb_comptable_tables.is_active,      
                hyb_comptable_tables.is_favorit, 
                hyb_comptable_tables.note AS table_note, 
                hyb_comptable_tables.url, 
                hyb_comptable_tables.imgUrl,
                hyb_comptable_tables.created
                FROM hyb_comptable_tables
                LEFT JOIN hyb_comptable_datasets USING (table_name) 
                WHERE hyb_comptable_datasets.comptable_name = ?
                AND hyb_comptable_tables.is_active = 1
                ORDER BY table_name, hyb_comptable_datasets.key ASC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $comptableName);
            $stmt->execute();
            $stmt->bind_result(
                    $comptableName, 
                    $tableName, 
                    $key, 
                    $value, 
                    $datasetNote, 
                    $private, 
                    $datasetCreated, 
                    $lastChanged, 
                    $isActive,
                    $isFavorit, 
                    $tableNote, 
                    $url, 
                    $imgUrl, 
                    $tableCreated
            );            

            //fetch the articles
            while ($stmt->fetch()) 
            {            
                //create new Table if table does not exist
                if (false === isset($arrObjTables[$tableName])) 
                {
                    $arrObjTables[$tableName] = 
                            new \HybridCMS\Plugins\Comptable\Table($tableName);

                    $isActive = (bool) $isActive;
                    $isFavorit = (bool) $isFavorit;
                    $arrObjTables[$tableName]->setIsActive($isActive);
                    $arrObjTables[$tableName]->setIsFavorit($isFavorit);
                    $arrObjTables[$tableName]->setCreated($tableCreated);

                    if (!empty($tableNote)) 
                    {
                        $arrObjTables[$tableName]->setNote($tableNote);
                    }

                    if (!empty($url)) 
                    {
                        $arrObjTables[$tableName]->setUrl($url);
                    }

                    if (!empty($imgUrl)) 
                    {
                        $arrObjTables[$tableName]->setImgUrl($imgUrl);
                    }
                }                                                  

                if (false === empty($key) 
                    && 
                    false === empty($value) 
                    && 
                    true === ($private == 0 || $private == 1)) 
                {
                                    
                    //handle Dataset
                    $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                            $key, $value);

                    $private = (bool) $private;
                    $objDataset->setPrivate($private);
                    $objDataset->setCreated($datasetCreated);

                    if (false === empty($datasetNote)) 
                    {
                        $objDataset->setNote($datasetNote);
                    }

                    if (false === empty($lastChanged)) 
                    {
                        $objDataset->setLastChanged($lastChanged);
                    }

                    //finaly add objDataset
                    $arrObjTables[$tableName]->addDataset($objDataset);
                }
            }//end while
            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrObjTables;
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    

    /**
     * selectComptableByComptableName
     * @param mysqli $db
     * @return Table[]
     * @throws Exception
     */
    public function selectArrObjTablesByComptableName($db, $comptableName) 
    {
        //statement-Object
        $stmt = null;
        
        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $arrObjTables = array();

            $sql = 'SELECT 
                hyb_comptable_datasets.comptable_name,
                hyb_comptable_datasets.table_name, 
                hyb_comptable_datasets.key, 
                hyb_comptable_datasets.value, 
                hyb_comptable_datasets.note AS dataset_note, 
                hyb_comptable_datasets.private, 
                hyb_comptable_datasets.created AS datasetCreated,
                hyb_comptable_datasets.lastChanged,                
                hyb_comptable_tables.is_active,      
                hyb_comptable_tables.is_favorit, 
                hyb_comptable_tables.note AS table_note, 
                hyb_comptable_tables.url, 
                hyb_comptable_tables.imgUrl,
                hyb_comptable_tables.created AS tableCreated
                FROM hyb_comptable_tables
                LEFT JOIN hyb_comptable_datasets USING (table_name) 
                WHERE hyb_comptable_datasets.comptable_name = ?
                ORDER BY hyb_comptable_datasets.table_name, hyb_comptable_datasets.key ASC';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) 
            {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('s', $comptableName);
            $stmt->execute();
            $stmt->bind_result(
                    $comptableName, 
                    $tableName, 
                    $key, 
                    $value, 
                    $datasetNote, 
                    $private, 
                    $datasetCreated, 
                    $lastChanged, 
                    $isActive,
                    $isFavorit, 
                    $tableNote, 
                    $url, 
                    $imgUrl, 
                    $tableCreated
            );            

            //fetch the articles
            while ($stmt->fetch()) 
            {
                //create new Table if table does not exist
                if (false === isset($arrObjTables[$tableName])) 
                {
                    $arrObjTables[$tableName] = 
                            new \HybridCMS\Plugins\Comptable\Table($tableName);

                    $isActive = (bool) $isActive;
                    $isFavorit = (bool) $isFavorit;
                    $arrObjTables[$tableName]->setIsActive($isActive);
                    $arrObjTables[$tableName]->setIsFavorit($isFavorit);
                    $arrObjTables[$tableName]->setCreated($tableCreated);

                    if (!empty($tableNote)) 
                    {
                        $arrObjTables[$tableName]->setNote($tableNote);
                    }

                    if (!empty($url)) 
                    {
                        $arrObjTables[$tableName]->setUrl($url);
                    }

                    if (!empty($imgUrl)) 
                    {
                        $arrObjTables[$tableName]->setImgUrl($imgUrl);
                    }
                }

                if (false === empty($key) 
                    && 
                    false === empty($value) 
                    && 
                    true === ($private == 0 || $private == 1)) 
                {
                    //handle Dataset
                    $objDataset = new \HybridCMS\Plugins\Comptable\Dataset(
                            $key, $value);

                    $private = (bool) $private;
                    $objDataset->setPrivate($private);
                    $objDataset->setCreated($datasetCreated);

                    if (false === empty($datasetNote)) 
                    {
                        $objDataset->setNote($datasetNote);
                    }

                    if (false === empty($lastChanged)) 
                    {
                        $objDataset->setLastChanged($lastChanged);
                    }

                    //finaly add objDataset
                    $arrObjTables[$tableName]->addDataset($objDataset);
                }
            }//end while
            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrObjTables;
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    

    /**
     * selectTablesOrderedBy
     * @param mysqli $db
     * @param String $comptableName
     * @param String $orderBy
     * @param String $direction
     * @return String[]
     * @throws \Exception
     */
    public function selectTablesOrderedBy(
            $db, $comptableName, $orderBy, $direction, $useAbs = false) 
    {
        //statement-Object
        $stmt = null;
        
        $value = "value ";
        if(true === $useAbs) {
            $value = "ABS(value) ";
        }

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $arrTableNames = array();

            $dir = 'ASC';
            if (strtolower($direction) === 'desc') 
            {
                $dir = 'DESC';
            }

            $sql = 'SELECT hyb_comptable_tables.table_name
                    FROM hyb_comptable_datasets
                    JOIN hyb_comptable_tables
                    USING ( table_name ) 
                    RIGHT JOIN hyb_comptable 
                        ON (hyb_comptable.comptable_name = 
                            hyb_comptable_tables.comptable_name) 
                    WHERE hyb_comptable_datasets.key = ?
                    AND hyb_comptable.comptable_name =  ?
                    AND hyb_comptable_tables.is_active = 1
                    ORDER BY ' . $value . $dir;


            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception(
                "Statement is not valid: " . htmlspecialchars($sql));
            }

            $stmt->bind_param('ss', $orderBy, $comptableName);
            $stmt->execute();
            $stmt->bind_result($tableName);


            //fetch the articles
            while ($stmt->fetch()) {

                //create new Table if table does not exist
                if (!in_array($tableName, $arrTableNames)) {
                    $arrTableNames[] = $tableName;
                }
            }//end while
            //close Resources
            $stmt->close();

            //return all Comptables
            return $arrTableNames;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * deleteTable
     *
     * @param mysqli $db
     * @param String $comptableName
     * @return Integer - affected Rows
     * @throws \Exception
     */
    public function deleteComptable($db, $comptableName) {

        //statement-Object
        $stmt = null;

        try {

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'DELETE FROM hyb_comptable WHERE comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('s', $comptableName);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * deleteTable
     *
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     * @throws \Exception
     */
    public function deleteTable($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing deleteTable:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) < 1) {
                throw new \Exception(
                "Error Processing deleteTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if $arrObjTables has an instance of Table assigned
            if (!($objTable instanceof \HybridCMS\Plugins\Comptable\Table)) {
                throw new \Exception(
                "Error Processing deleteTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();

            $sql = 'DELETE FROM hyb_comptable_tables WHERE comptable_name = ? AND table_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('ss', $comptableName, $tableName);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * deleteDataset
     *
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     * @throws \Exception
     */
    public function deleteDataset($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) < 1) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);
            $arrObjDatasets = $objTable->getArrObjDatasets();

            //check if $arrObjDatasets is not empty
            if (count($arrObjDatasets) < 1) {
                throw new \Exception(
                "Error Processing insertDataset:                 
                    Paramter objComptable do not have an instance of Dataset assigned.", 1);
            }

            $objDataset = array_shift($arrObjDatasets);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $key = $objDataset->getKey();

            $sql = 'DELETE FROM hyb_comptable_datasets '
                    . 'WHERE comptable_name = ? '
                    . 'AND table_name = ? '
                    . 'AND hyb_comptable_datasets.key = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $stmt->bind_param('sss', $comptableName, $tableName, $key);
            $stmt->execute();

            $affectedRows = $db->affected_rows;

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    
    /**
     * updateIsActiveOnTable
     *
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateIsActiveOnTable($db, $objComptable) 
    {
        //statement-Object
        $stmt = null;

        try 
        {
            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\
                    Comptable\Comptable)) 
            {
                throw new \Exception(
                "Error Processing updateIsFavoritOnTable:                 
                    Paramter objComptable must be an 
                    instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty and distinct
            if (count($arrObjTables) !== 1) 
            {
                throw new \Exception(
                "Error Processing updateIsFavoritOnTable:                 
                    Paramter objComptable do not have 
                    an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if DB-Connection is established
            if (!$db) 
            {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_tables SET is_active = ? '
                    . 'WHERE table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) 
            {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $isActive = (int) $objTable->getIsActive();

            $stmt->bind_param('iss', $isActive, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    

    /**
     * updateIsFavoritOnTable
     *
     * @param mysqli $db
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateIsFavoritOnTable($db, $objComptable) 
    {
        //statement-Object
        $stmt = null;

        try 
        {
            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\
                    Comptable\Comptable)) 
            {
                throw new \Exception(
                "Error Processing updateIsFavoritOnTable:                 
                    Paramter objComptable must be an 
                    instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty and distinct
            if (count($arrObjTables) !== 1) 
            {
                throw new \Exception(
                "Error Processing updateIsFavoritOnTable:                 
                    Paramter objComptable do not have 
                    an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if DB-Connection is established
            if (!$db) 
            {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_tables SET is_favorit = ? '
                    . 'WHERE table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) 
            {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $isFavorit = (int) $objTable->getIsFavorit();

            $stmt->bind_param('iss', $isFavorit, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } 
        catch (\Exception $e) 
        {
            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateUrlOnTable
     *
     * @param mysqli $objArticle
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateUrlOnTable($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing updateUrlOnTable:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty and distinct
            if (count($arrObjTables) !== 1) {
                throw new \Exception(
                "Error Processing updateUrlOnTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_tables SET url = ? '
                    . 'WHERE table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $url = $objTable->getUrl();

            $stmt->bind_param('sss', $url, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateImgUrlOnTable
     *
     * @param mysqli $objArticle
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateImgUrlOnTable($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing updateImgUrlOnTable:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty and distinct
            if (count($arrObjTables) !== 1) {
                throw new \Exception(
                "Error Processing updateImgUrlOnTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_tables SET imgUrl = ? '
                    . 'WHERE table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $imgUrl = $objTable->getImgUrl();

            $stmt->bind_param('sss', $imgUrl, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateNoteOnTable
     *
     * @param mysqli $objArticle
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateNoteOnTable($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing updateNoteOnTable:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty and distinct
            if (count($arrObjTables) !== 1) {
                throw new \Exception(
                "Error Processing updateNoteOnTable:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_tables SET note = ? '
                    . 'WHERE table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $note = $objTable->getNote();

            $stmt->bind_param('sss', $note, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateNoteOnDataset
     *
     * @param mysqli $objArticle
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateNoteOnDataset($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing updateNoteOnDataset:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) !== 1) {
                throw new \Exception(
                "Error Processing updateNoteOnDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);
            $arrObjDatasets = $objTable->getArrObjDatasets();

            //check if $arrObjDatasets is not empty
            if (count($arrObjDatasets) !== 1) {
                throw new \Exception(
                "Error Processing updateNoteOnDataset:                 
                    Paramter objComptable do not have an instance of Dataset assigned.", 1);
            }

            $objDataset = array_shift($arrObjDatasets);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_datasets SET note = ? '
                    . 'WHERE hyb_comptable_datasets.key = ? '
                    . 'AND table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $key = $objDataset->getKey();
            $note = $objDataset->getNote();

            $stmt->bind_param('ssss', $note, $key, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updateValueOnDataset
     *
     * @param mysqli $objArticle
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updateValueOnDataset($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing updateValueOnDataset:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) !== 1) {
                throw new \Exception(
                "Error Processing updateValueOnDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);
            $arrObjDatasets = $objTable->getArrObjDatasets();

            //check if $arrObjDatasets is not empty
            if (count($arrObjDatasets) !== 1) {
                throw new \Exception(
                "Error Processing updateValueOnDataset:                 
                    Paramter objComptable do not have an instance of Dataset assigned.", 1);
            }

            $objDataset = array_shift($arrObjDatasets);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_datasets SET value = ?, lastChanged = ? '
                    . 'WHERE hyb_comptable_datasets.key = ? '
                    . 'AND table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $key = $objDataset->getKey();
            $value = $objDataset->getValue();
            $lastChanged = time();

            $stmt->bind_param('sisss', $value, $lastChanged, $key, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * updatePrivateOnDataset
     *
     * @param mysqli $objArticle
     * @param Comptable $objComptable
     * @return Integer - affected Rows
     */
    public function updatePrivateOnDataset($db, $objComptable) {

        //statement-Object
        $stmt = null;

        try {

            //check if $objComptable is an instance of Comptable
            if (!($objComptable instanceof \HybridCMS\Plugins\Comptable\Comptable)) {
                throw new \Exception(
                "Error Processing updatePrivateOnDataset:                 
                    Paramter objComptable must be an instance of Comptable.", 1);
            }

            $arrObjTables = $objComptable->getArrObjTables();

            //check if $arrObjTables is not empty
            if (count($arrObjTables) < 1) {
                throw new \Exception(
                "Error Processing updatePrivateOnDataset:                 
                    Paramter objComptable do not have an instance of Table assigned.", 1);
            }

            $objTable = array_shift($arrObjTables);
            $arrObjDatasets = $objTable->getArrObjDatasets();

            //check if $arrObjDatasets is not empty
            if (count($arrObjDatasets) < 1) {
                throw new \Exception(
                "Error Processing updatePrivateOnDataset:                 
                    Paramter objComptable do not have an instance of Dataset assigned.", 1);
            }

            $objDataset = array_shift($arrObjDatasets);

            //check if DB-Connection is established
            if (!$db) {
                throw new \Exception("DB-Connection is not established.");
            }

            $sql = 'UPDATE hyb_comptable_datasets SET private = ? '
                    . 'WHERE hyb_comptable_datasets.key = ? '
                    . 'AND table_name = ? '
                    . 'AND comptable_name = ?';

            //check if statement is valid
            if (!($stmt = $db->prepare($sql))) {
                throw new \Exception("Statement is not valid.");
            }

            $comptableName = $objComptable->getComptableName();
            $tableName = $objTable->getTableName();
            $key = $objDataset->getKey();
            $private = (int) $objDataset->getPrivate();

            $stmt->bind_param('isss', $private, $key, $tableName, $comptableName);

            $stmt->execute();

            //get number of updated Rows
            $affectedRows = $db->affected_rows;

            //close Resources
            $stmt->close();

            return $affectedRows;
        } catch (\Exception $e) {

            //close statement
            if ($stmt)
                $stmt->close();

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

}

?>
