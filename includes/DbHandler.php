<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbHandler
{

    private $conn;
    public $error;
    public $user_id;


    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /* ------------- `users` table method ------------------ */

    /**
     * Creating new user
     * @param String $email User login email id
     * @param String $password User login password
     * @return array|int|string
     * @internal param String $name User full name
     */
    public function createUser($email, $password)
    {
        require_once 'PassHash.php';

        // First check if user already existed in db
        if (!$this->isUserExists($email)) {
            //var_dump($this->isUserExists($email));
            // Generating password hash
            $password = PassHash::hash($password);

            // Generating API key
//            $api_key = $this->generateApiKey();
            // insert query
            $stmt = $this->conn->prepare("INSERT INTO user(email, password) values(?, ? )");
//                var_dump($this->conn->error);
            $stmt->bind_param("ss", $email, $password);


            $result = $stmt->execute();
            $response = "";
            //var_dump($stmt->errno);
            $stmt->close();
            // Check for successful insertion
            if ($result) {
                // User successfully inserted
                $response = USER_CREATED_SUCCESSFULLY;
            } else {
                // Failed to create user
                $this->error = $stmt->error;
                $response = USER_CREATE_FAILED;
            }
        } else {
            // User with same email already existed in the db
            $response = USER_ALREADY_EXISTED;
        }


        return $response;
    }

    public function getInsertId()
    {
        return $this->conn->insert_id;
    }

    /**
     * Checking user login
     * @param String $email User login email id
     * @param String $password User login password
     * @return boolean User login status success/fail
     */
    public function checkLogin($email, $password)
    {
        // fetching user by email
        require_once 'PassHash.php';
        global $user_id;
        $stmt = $this->conn->prepare("SELECT id,password FROM user WHERE email = ?");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $stmt->bind_result($id, $password_hash);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Found user with the email
            // Now verify the password

            $stmt->fetch();

            $stmt->close();

            if (PassHash::check_password($password_hash, $password)) {
                // User password is correct
                $user_id = $id;
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();

            // user not existed with the email
            return -1;
        }
    }

    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email)
    {
        if ($stmt = $this->conn->prepare("SELECT id from user WHERE email = ?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $num_rows = $stmt->num_rows;
            $stmt->close();
            return $num_rows > 0;
        } else {
            return 0;
        }
    }

    function register($email, $password1, $password2)
    {

        if (empty($email) || empty($password1) || empty($password2)) {

            throw new Exception("Incomplete values", CLIENT_ERROR);
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

        if ($stmt = $this->conn->prepare($sql_insert)) {


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

    /**
     * Fetching user by email
     * @param String $email User email id
     * @throws Exception Throws exception when result is empty or null
     * @return null|String|false false when problem with query statement, null when query execution fails, String on success
     */
    public function getUserByEmail($email)
    {
        $time='';
        $stmt = $this->conn->prepare("SELECT time_created FROM user WHERE email = ?");
        if ($stmt) {
            $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                // $user = $stmt->get_result()->fetch_assoc();
                $stmt->bind_result($time);
                $stmt->fetch();
                $stmt->close();
                if(empty($time)||$time===null){
                    throw new SqlException("Email invalid",SqlException::$CLIENT_ERROR);
                }
                return $time;
            }
             throw new SqlException("Problem with query execution",SqlException::$QUERY_ERROR);
        }
         throw new SqlException("Problem with query",SqlException::$QUERY_ERROR);
    }
//    /**
//     * Fetching user id by api key
//     * @param String $api_key user api key
//     */
//    public function getUserId($api_key) {
//        $user_id=null;
//        $stmt = $this->conn->prepare("SELECT id FROM user WHERE api_key = ?");
//        $stmt->bind_param("s", $api_key);
//        if ($stmt->execute()) {
//            $stmt->bind_result($user_id);
//            $stmt->fetch();
//            // TODO
//            // $user_id = $stmt->get_result()->fetch_assoc();
//            $stmt->close();
//            return $user_id;
//        } else {
//            return NULL;
//        }
//    }
    public function booking($dep_date, $pickup_loc, $drop_off_loc, $ret_date, $ret_drop_off_loc, $ret_pickup_loc, $returntrip, $user_id
    )
    {
        $sql_insert = "INSERT INTO `booking`(`dep_date`,`pickup_loc`,`drop_off_loc`,`ret_date`,`ret_pickup_loc`,`ret_drop_off_loc`,`returntrip`,`user_id`,`timeofbooking`) VALUES(?,	?,?,?,?,?,?,?,Now())";
        if ($stmt = $this->conn->prepare($sql_insert)) {


            //bind variable to prevent mysql injection

            $stmt->bind_param("ssssssss", $dep_date, $pickup_loc, $drop_off_loc, $ret_date, $ret_pickup_loc, $ret_drop_off_loc, $returntrip, $user_id);

            if (!$stmt->execute()) {

                throw new Exception("Unable process your query, try again after sometime" . $stmt->error . " " . $user_id);
            }

            //good pratice to free up resources

            $stmt->close();


            //retrieve the  inserted data id for later retrieval of data

            $inserted_booking_id = $this->conn->insert_id;
        } else {

            //decide on how u want o treat insert error next time encapsulate in an exception

            throw new SqlException(
                "\n error inserting data \n" . $this->conn->error);
        }

//this is to query the database for the inserted booking data

        if ($result = $this->conn->query("SELECT * FROM `booking` WHERE id=" . $inserted_booking_id)) {

            $row = $result->fetch_assoc();

            //use the $row array variable to fetch the data
            return $row;
        } else {
            throw new SqlException('unable to retrieve information');
        }
    }
    public function resetPassword($email,$password){
       $pass=PassHash::hash($password);
        $sql="UPDATE users SET password=? WHERE email=? LIMIT 1";
        $stmt=$this->conn->prepare($sql);
        if(!$stmt){
            throw new SqlException("Unable to reset passowrd",SqlException::$QUERY_ERROR);
        }
        $stmt->bind_param('ss',$pass,$email);
        $stmt->execute();
        if($stmt->affected_row>0){
            $stmt->close;
            return true;
        }
        return false;

    }

}

class SqlException extends Exception{
    public static $TABLE_CREATE_ERROR= 201;
    public static $INSERT_ERROR =202;
    public static $QUERY_ERROR= 203;
    public static $CLIENT_ERROR= 101;
}
