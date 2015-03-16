<?php
/** 
 * this is the scrip that does all the neccessary logic to register a user;
 * and store the users name and email in a session;
 * the first step was to start a session;
 * second to retrieve posted data;
 * third create the table which you should do already not in production; 
 * fourth insert retrieved values from user;
 * fifth query for the inserted value using the id from the inserted data;
 * sixth store the data to the session and redirect user;
 * use these as a template for deciding on your own logic;
 * needed improvement implement checks on the client and server side for incomplete or inappropriate
 * values provided
 * 
 */
session_start();
//constants to identify error using code in exception
define('TABLE_CREATE_ERROR', 201);
define('INSERT_ERROR', 202);
define('QUERY_ERROR', 203);
define('CLIENT_ERROR', 101);
$error = "";
if (isset($_POST["submit"])) {
//    $name = $_POST["name"];
    $email = $_POST["email"];
    $password1 = $_POST["password"];
    $password2 = $_POST["password2"];
    
    //code to establish a mysqli not mysql connection fill in the necessary data you
    //can think of a better way to seperate this functionality
    $mysqli = new mysqli("localhost", "root", "", "test");

//check connection for error
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    try {
        createUserTable();
        $insertId = insertUser($email, $password1, $password2);
        $result=userQuery($insertId);
        $mysqli->close();
        if(isset($result["email"])){
       $_SESSION["id"]=$insertId;
        $_SESSION["email"]=$result["email"];
        redirect_to("booking.php");
        }   
        
    } catch (Exception $e) {
        $error=$e->getMessage();
    }
}




function userQuery($id) {
    global $mysqli;
    if ($result = $mysqli->query("SELECT `email` FROM `user` WHERE id=" . $id)) {
        $row = $result->fetch_assoc();
        //use the $row array variable to fetch the data
        return $row;
    } else {
        throw new Exception("query failed", QUERY_ERROR);
    }
}

function createUserTable() {
    global $mysqli;
    //MySQL statement to create table
    $sql_create = "CREATE TABLE IF NOT EXISTS `user` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL,
	`email` VARCHAR(255) NOT NULL,
	`password` VARCHAR(255) NOT NULL,
        `time_created` TIMESTAMP NOT NULL,
	PRIMARY KEY (`id`))ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    //	you can use this to create the table in php but take not if used subsequently
    if (!$mysqli->query($sql_create)) {
        $create_error = "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
        throw new Exception($create_error, TABLE_CREATE_ERROR);
    }
}

//this is to query the database for the inserted booking data
function insertUser($email, $password1, $password2) {
    global $mysqli;
    if(empty($email)||empty($password1)||empty($password2)){
        throw new Exception("Incomplete values",CLIENT_ERROR);
    }
    If ($password1 != $password2) {
        $error = "missmatch password";
        throw new Exception("missmatch password", CLIENT_ERROR);
    } else {
        $password = $_POST["password"];
        $password = md5($password);
    }
    //MySQL statement to insert a row
    $sql_insert = "INSERT INTO `user`(`id`,`email`,`password`) VALUES(NULL,?,?)";
    if ($stmt = $mysqli->prepare($sql_insert)) {

        //bind variable to prevent mysql injection
        $stmt->bind_param("ss", $email, $password);
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            
            //throw exception to indicate error and stop the rest of the function excecution
            //passing the error message
            throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error, INSERT_ERROR);
        } else {
            //goog be deleted this is just to test or show success
            echo "\n insert completed";
        }
        //good pratice to free up resources
        $stmt->close();

        //retrieve the  inserted data id for later retrieval of data
        $inserted_user_id = $mysqli->insert_id;
        return $inserted_user_id;
    } else {
        //decide on how u want to treat insert error next time encapsulate in an exception
        echo
        "\n error inserting data \n" . $mysqli->error;
    }
}
//use this to redirect user to the next page based on the register process
function redirect_to($location) {
    header("location:{$location}");
    exit;
}
?>
	
