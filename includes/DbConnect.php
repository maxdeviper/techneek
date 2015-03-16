<?php

/**
 * Handling database connection
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbConnect {

    private $conn;

    function __construct() {        
    }

    /**
     * Establishing database connection
     * @return database connection handler
     */
    function connect() {
        include_once dirname(__FILE__) . '/Config.php';

        // Connecting to mysql database
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Check for database connection error
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        // returing connection resource
        return $this->conn;
    }
    function createUserTable() {

        global $mysqli;

        //MySQL statement to create table

        $sql_create = "CREATE TABLE IF NOT EXISTS `user` (

	`id` INT(10) NOT NULL AUTO_INCREMENT,



	`email` VARCHAR(255) NOT NULL,

	`password` VARCHAR(255) NOT NULL,

        `time_created` TIMESTAMP NOT NULL,

	PRIMARY KEY (`id`))ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";

        //	you can use this to create the table in php but take note if used subsequently

        if (!$this->conn->query($sql_create)) {

            $create_error = "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;

            throw new Exception($create_error, TABLE_CREATE_ERROR);

        }

    }
}

?>
