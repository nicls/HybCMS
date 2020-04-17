<?php

namespace HybridCMS\Database;

/**
 * class DatabaseFactory - provides a MySqli Database Connection
 *
 * @package Database
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class DatabaseFactory {

    private static $factoryInstance;
    private $dbConnection;

    /**
     * __construct - Opens a utf-8 DB-Connection and stores
     * the connection-Object in $this->dbConnection
     *
     * @return void
     */
    private function __construct() {

        //open new Database Connection (connection-data is provided by /Helper/settings.php)
        $this->dbConnection = new \mysqli(DBMAIN_HOST, DBMAIN_NAME, DBMAIN_PASS, DBMAIN_DB);

        //set Charset to utf-8
        $this->dbConnection->set_charset("utf8");

        //handle Errors
        if (mysqli_connect_errno($this->dbConnection) != 0) {
            throw new \Exception('Database Connection Error: ' .
                    mysqli_connect_errno($this->connection) . ' : ' .
                    mysqli_connect_error($this->connection));

            exit;
        }
    }

    /**
     * connect - opens a new DB-Connection and return the factoryInstance
     *
     * @return DatabaseFactory
     */
    public static function getFactory() {

        if (null === self::$factoryInstance || !self::$factoryInstance->getConnection()) {
            self::$factoryInstance = new self;
        }
        return self::$factoryInstance;
    }

    /**
     * getConnection - return the mysqli-connection to the database
     *
     * @return database Connection-Object
     */
    public function getConnection() {
        return $this->dbConnection;
    }

    /**
     * closeDBConnection - closes the DB-Connection
     *
     * @return boolean
     */
    public function closeConnection() {

        if ($this->dbConnection) {
            //determine thread id
            $thread_id = $this->dbConnection->thread_id;

            //free Sockets
            $this->dbConnection->kill($thread_id);

            //close Connection
            $this->dbConnection->close();

            //delete factory instance
            self::$factoryInstance = null;
        }

        return true;
    }

}

//end class
?>