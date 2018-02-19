<?php
error_reporting(E_ALL ^ E_DEPRECATED);
class DB_Functions {

    private $db;

    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
	$this->db->connect();
    }

    // destructor
    function __destruct() {

    }

    /**
     * Store user details
     */

    public function storeUser($name, $email, $password, $verification) {
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt
        $isActive = "No";
        $result = mysqli_query($this->db->con,"INSERT INTO users(user_name, user_email, user_password, salt, verification, is_active) VALUES('$name', '$email', '$encrypted_password', '$salt', '$verification', '$isActive')") or die(mysqli_error($this->db));

        $msg = "To activate account please click on following link: " .  '' .

            "https://sportbuddy.000webhostapp.com/verify.php?email=$email&verification=$verification";
        mail("$email", "Verification Email", $msg);
        // check for result
        if ($result) {
            // gettig the details
            $uid = mysqli_insert_id($this->db->con); // last inserted id
            $result = mysqli_query($this->db->con,"SELECT * FROM users WHERE user_id = $uid");
            // return details
            return mysqli_fetch_array($result);
        } else {
            return false;
        }
    }
    public function checkVerification($email,$verification){
        $result = mysqli_query($this->db->con,"SELECT user_email, verification FROM users WHERE user_email='" . $email . "' AND verification='" . $verification . "'") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_query($this->db->con,"UPDATE users SET is_active='$verification' WHERE user_email='$email'") or die(mysqli_connect_errno());
            return $result;
        } else {
            return false;
        }
    }
    public function storeUserProfilePictureData($email,$profilePicture){
        $result = mysqli_query($this->db->con,"UPDATE users SET user_profilePicture='$profilePicture' WHERE user_email='$email'") or die(mysqli_connect_errno());
        $result = mysqli_query($this->db->con,"SELECT * FROM users WHERE user_email = '$email'") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function removeUserFromCurrentEvent($eventId,$userId){
        $result = mysqli_query($this->db->con,"DELETE from currentEvent WHERE event_id='$eventId' AND user_id='$userId'") or die(mysqli_connect_errno());
        $result = mysqli_query($this->db->con,"SELECT COUNT(event_id) AS countPeopleJoined FROM currentEvent WHERE event_id='$eventId';") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function storeEventData($email,$event_name,$event_description,$event_numberOfPeople,$event_address,$event_picture,$event_date,$event_time){
        $result = mysqli_query($this->db->con,"INSERT INTO events(user_email, event_name, event_description, event_numberOfPeople, event_address, event_picture, event_peopleJoined, event_date, event_time) VALUES('$email', '$event_name', '$event_description', '$event_numberOfPeople', '$event_address', '$event_picture', '1', '$event_date', '$event_time')") or die(mysqli_connect_errno());
        $result = mysqli_query($this->db->con,"SELECT * FROM events WHERE user_email = '$email'") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function getUserProfileInformation($email) {
        $result = mysqli_query($this->db->con,"SELECT * FROM users WHERE user_email = '$email'") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function getCurrentEventInformationAll($eventId) {
        $result = mysqli_query($this->db->con,"SELECT * FROM events WHERE event_id = '$eventId'") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function retreiveEventNameAndPicture($eventId) {
        $result = mysqli_query($this->db->con,"SELECT * FROM events WHERE event_id = '$eventId'") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function getTotalId($email) {
        $result = mysqli_query($this->db->con,"SELECT MAX(event_id) AS event_id FROM events") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function getTotalIdOfUser($idOfUser) {
        $result = mysqli_query($this->db->con,"SELECT COUNT(event_id) AS event_id FROM currentEvent WHERE user_id = '$idOfUser'") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function checkNumberOfEventsForUser($userId) {
        $result = mysqli_query($this->db->con,"SELECT COUNT(event_id) AS event_id FROM currentEvent WHERE user_id = '$userId'") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function CheckNumberOfEventsForUserToday($userId,$actualDate) {
        $result = mysqli_query($this->db->con,"SELECT count(id) AS event_idd FROM events t1, currentEvent t2 WHERE t1.event_date = '$actualDate' AND t2.user_id = '$userId' AND t1.event_id = t2.event_id") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function retreiveEventsIdForCurrentUser($idOfUser, $eventNum) {
        $result = mysqli_query($this->db->con,"SELECT event_id FROM currentEvent WHERE user_id='$idOfUser' ORDER BY event_id LIMIT $eventNum,1") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function getPeopleJoined($eventId) {
        $result = mysqli_query($this->db->con,"SELECT COUNT(event_id) AS countPeopleJoined FROM currentEvent WHERE event_id='$eventId';") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function addCurrentUserToEvent($eventId,$userId){
        $result = mysqli_query($this->db->con,"INSERT INTO currentEvent(event_id, user_id) VALUES('$eventId', '$userId')") or die(mysqli_connect_errno());
        $result = mysqli_query($this->db->con,"SELECT * FROM currentEvent") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function storeEmailIntoDb($helpText, $userID, $userEmail){
        $responded = "No";
        $result = mysqli_query($this->db->con,"INSERT INTO userEmails(user_id, user_email, user_message, responded) VALUES('$userID', '$userEmail', '$helpText', '$responded')") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function addPeopleJoinedToEvents($eventId,$peopleJoined){
        $result = mysqli_query($this->db->con,"UPDATE events SET event_peopleJoined='$peopleJoined' WHERE event_id='$eventId'") or die(mysqli_connect_errno());
        $result = mysqli_query($this->db->con,"SELECT * FROM events") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function retreiveNotificationSettings($userId){
        $result = mysqli_query($this->db->con,"SELECT * FROM users  WHERE user_id='$userId'") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    public function updateNotificationSettings($user,$sendNotifications,$sendNotificationsOfCurrentDay,$sendNotificationsOfAllJoined){
        $result = mysqli_query($this->db->con,"UPDATE users SET user_sendNot='$sendNotifications', user_sendNotOfCurrentDay='$sendNotificationsOfCurrentDay', user_setNotOfAllJoined='$sendNotificationsOfAllJoined' WHERE user_id='$user'") or die(mysqli_connect_errno());
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }
    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
        $result = mysqli_query($this->db->con,"SELECT * FROM users WHERE user_email = '$email'") or die(mysqli_connect_errno());
        // check for result
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysqli_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['user_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password
            if ($encrypted_password == $hash) {
                return $result;
            }
        } else {
            return false;
        }
    }

    /**
     * Check user is existed or not
     */
    public function isUserExisted($email) {
        $result = mysqli_query($this->db->con,"SELECT user_email from users WHERE user_email = '$email'");
        $no_of_rows = mysqli_num_rows($result);
        if ($no_of_rows > 0) {
            // user exist
            return true;
        } else {
            // user not exist
            return false;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {

        $hash = base64_encode(sha1($password . $salt, true) . $salt);

        return $hash;
    }

}

?>
