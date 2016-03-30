<?php
/**
 * Created by PhpStorm.
 * User: yoole
 * Date: 2/25/2016
 * Time: 1:39 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class User
{
	/**
	*Function: Verify
	*
	*Decription:
	*
	*Input: usrname, passwd
	*Output:
	*Comment:
	*/
    public static function verify($usrname, $passwd)
    {
        /*  Takes 2 string variables as input and returns an associated array of the form:
         *  array( usrID => $usrID, usrlvl => $usrlvl ); where $usrID is a unique identifier
         *  and $usrlvl is an integer for which 1 - admin; 2 - judge; 3 - grader; 4 - contestant;
         *  If an error occurs (either incorrect username or password) the following values will be returned:
         *  -1 - not found; -2 - bad username/password
         */
        $conn = DatabaseConnection::get_connection();
        // Query for username and select password hash from database
		$sql= "SELECT usr_PK, passhash, creds FROM usr WHERE usrname=:usrname";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":usrname", $usrname);
        $status = $stmt->execute();
		if($status){
			$stmt->bindColumn('usr_PK', $usr_PK);
			$stmt->bindColumn('passhash', $passhash);
			$stmt->bindColumn('creds', $creds);
			$stmt->fetch(PDO::FETCH_BOUND);
			// Check hashed password against stored password
			if (password_verify($passwd, $passhash)) {
				return array("usrID" => "$usr_PK", "usrlvl" => "$creds");
			} else {
				return array("usrID" => "$usr_PK", "usrlvl" => -2);
			}
		} else {
			return array("usrID" => null, "usrlvl" => -1);
		}
    }

    public static function create_user($usrinfo)
    {
        /*  This function takes an associated array that must contain the following fields:
         *  usr, fname, lname, aff, email, phone, street1, street2, city, state, zip, and cred
         */
        $conn = DatabaseConnection::get_connection();
		$date = date("Y-m-d H:i:s");
        $passhash = password_hash($usrinfo['passwd'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO usr (usrname, fname, lname, joindate, aff_FK, email, phone, street1, street2, city, state, zip, passhash, creds) VALUES (:usrname, :fname, :lname, :joindate, :aff_FK, :email, :phone, :street1, :street2, :city, :state, :zip, :passhash, :creds)";
        $stmt = $conn->prepare($sql);
		$stmt->bindParam(':usrname', $usrinfo['usr']);
        $stmt->bindParam(':fname', $usrinfo['fname']);
		$stmt->bindParam(':lname', $usrinfo['lname']);
		$stmt->bindParam(':joindate', $date);
		$stmt->bindParam(':aff_FK', $usrinfo['aff']);
		$stmt->bindParam(':email', $usrinfo['email']);
		$stmt->bindParam(':phone', $usrinfo['phone']);
		$stmt->bindParam(':street1', $usrinfo['street1']);
		$stmt->bindParam(':street2', $usrinfo['street2']);
		$stmt->bindParam(':city', $usrinfo['city']);
		$stmt->bindParam(':state', $usrinfo['state']);
		$stmt->bindParam(':zip', $usrinfo['zip']);
		$stmt->bindParam(':passhash', $passhash);
		$stmt->bindParam(':creds', $usrinfo['creds']);
		$status = $stmt->execute();
        if($status){
			$usr_PK = $conn->lastInsertId();
            return $usr_PK;
        } else {
			return false;
		}
    }

    public static function admin_modify_user($usrinfo)
    {
        /*  This function takes an associated array that must contain the following fields:
         *  usr, fname, lname, aff, email, phone, street1, street2, city, state, zip, and cred
         *  The user's username may not be changed. The password much be changed in a separate operation.
         */
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE usr SET fname=:fname, lname=:lname, aff_FK=:aff_FK, email=:email, phone=:phone, street1=:street1, street2=:street2, city=:city, state=:state, zip=:zip, creds=:creds WHERE usrname=:usrname";
        $stmt = $conn->prepare($sql);
		$stmt->bindParam(':fname', $usrinfo['fname']);
		$stmt->bindParam(':lname', $usrinfo['lname']);
		$stmt->bindParam(':aff_FK', $usrinfo['aff']);
		$stmt->bindParam(':email', $usrinfo['email']);
		$stmt->bindParam(':phone', $usrinfo['phone']);
		$stmt->bindParam(':street1', $usrinfo['street1']);
		$stmt->bindParam(':street2', $usrinfo['street2']);
		$stmt->bindParam(':city', $usrinfo['city']);
		$stmt->bindParam(':state', $usrinfo['state']);
		$stmt->bindParam(':zip', $usrinfo['zip']);
		$stmt->bindParam(':creds', $usrinfo['creds']);
		$stmt->bindParam(':usrname', $usrinfo['usr']);
		$status = $stmt->execute();
        if($status) {
            return true;
		} else {
			return false;
		}
    }

    public static function get_all_users()
    {
        // This function returns an array of associated arrays containing the user ID as uid, the username as 'usr',
        // the first name as 'fname', the last name as 'lname', and their email as 'email'.
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT usr_PK, usrname, fname, lname, email FROM usr";
        $stmt = $conn->prepare($sql);
        $status = $stmt->execute();
		if($status){
			$stmt->bindColumn('usr_PK', $usr_PK);
			$stmt->bindColumn('usrname', $usrname);
			$stmt->bindColumn('fname', $fname);
			$stmt->bindColumn('lname', $lname);
			$stmt->bindColumn('email', $email);

			$usrs = array();

            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($usrs, array('uid' => $usr_PK, 'usr' => $usrname, 'fname' => $fname, 'lname' => $lname, 'email' => $email));
            }

			return $usrs;
		} else {
			return false;
		}
    }

    public static function set_user_password($usr_PK, $passwd)
    {
        // This function accepts a user id and a password and updates the user's password in the database accordingly
        $conn = DatabaseConnection::get_connection();
        $passhash = password_hash($passwd, PASSWORD_DEFAULT);
        $sql = "UPDATE usr SET passhash=:passhash WHERE usr_PK=:usr_PK";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':usr_PK', $usr_PK);
		$stmt->bindParam(':passhash', $passhash);
        $status = $stmt->execute();
        if ($status) {
			return true;
        } else {
			return false;
		}
    }

    public static function get_user($usr_PK)
    {
        // This function returns an associated array with all user information except password. This may be used to
        // populate forms with information (written specifically with modify_user() in mind).
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM usr WHERE usr_PK=:usr_PK";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam('usr_PK', $usr_PK);
        $status = $stmt->execute();
		if($status){
			$stmt->bindColumn('usrname', $usrname);
			$stmt->bindColumn('fname', $fname);
			$stmt->bindColumn('lname', $lname);
			$stmt->bindColumn('joindate', $joindate);
			$stmt->bindColumn('aff_FK', $aff_FK);
			$stmt->bindColumn('email', $email);
			$stmt->bindColumn('phone', $phone);
			$stmt->bindColumn('street1', $street1);
			$stmt->bindColumn('street2', $street2);
			$stmt->bindColumn('city', $city);
			$stmt->bindColumn('state', $state);
			$stmt->bindColumn('zip', $zip);
			$stmt->bindColumn('creds', $creds);
			$stmt->fetch(PDO::FETCH_BOUND);
			$usr = array('uid' => $usr_PK, 'usr' => $usrname, 'fname' => $fname, 'lname' => $lname, 'joindate' => $joindate, 'aff' => $aff_FK,
            'email' => $email, 'phone' => $phone, 'street1' => $street1, 'street2' => $street2, 'city' => $city, 'state' => $state, 'zip' => $zip, 'creds' => $creds);
			return $usr;
		} else {
			return false;
		}
    }

	public static function get_user_PK($usrname)
	{
		// This function returns an associated array with all user information except password. This may be used to
		// populate forms with information (written specifically with modify_user() in mind).
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT usr_PK FROM usr WHERE usrname=:usrname";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam('usrname', $usrname);
		$status = $stmt->execute();
		if($status){
			$stmt->bindColumn('usr_PK', $usr_PK);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $usr_PK;
		} else {
			return false;
		}
	}

	public static function get_user_by_creds($creds){
	/*	Ulenn Terry Chern - 20 March 2016 - 6:55PM
	 *	This function takes an integer input corresponding to a user level and returns an array of
	 *  associated arrays containing users with that credential level and their information.
	 */
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM usr WHERE creds=:creds";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':creds', $creds);
		$status = $stmt->execute();
		if($status){
			$stmt->bindColumn('usr_PK', $usr_PK);
			$stmt->bindColumn('usrname', $usrname);
			$stmt->bindColumn('fname', $fname);
			$stmt->bindColumn('lname', $lname);
			$stmt->bindColumn('joindate', $joindate);
			$stmt->bindColumn('aff_FK', $aff_FK);
			$stmt->bindColumn('email', $email);
			$stmt->bindColumn('phone', $phone);
			$stmt->bindColumn('street1', $street1);
			$stmt->bindColumn('street2', $street2);
			$stmt->bindColumn('city', $city);
			$stmt->bindColumn('state', $state);
			$stmt->bindColumn('zip', $zip);

			$usrs = array();

			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($usrs, array('uid' => $usr_PK, 'usr' => $usrname, 'fname' => $fname, 'lname' => $lname, 'joindate' => $joindate, 'aff' => $aff_FK,
				'email' => $email, 'phone' => $phone, 'street1' => $street1, 'street2' => $street2, 'city' => $city, 'state' => $state, 'zip' => $zip, 'creds' => $creds));
			}
			return $usrs;
		} else {
			return false;
		}
	}

	public static function get_users_by_aff($aff_FK){
		/*	Ulenn Terry Chern - 22 March 2016 - 5:52PM
         *	This function takes an integer input corresponding to a user's affiliation and returns an array of
         *  associated arrays containing users with that affiliation and their information.
         */
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM usr WHERE aff_FK=:aff_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':aff_FK', $aff_FK);
			try{
				$stmt->execute();
				$stmt->bindColumn('usr_PK', $usr_PK);
				$stmt->bindColumn('usrname', $usrname);
				$stmt->bindColumn('fname', $fname);
				$stmt->bindColumn('lname', $lname);
				$stmt->bindColumn('joindate', $joindate);
				$stmt->bindColumn('email', $email);
				$stmt->bindColumn('phone', $phone);
				$stmt->bindColumn('street1', $street1);
				$stmt->bindColumn('street2', $street2);
				$stmt->bindColumn('city', $city);
				$stmt->bindColumn('state', $state);
				$stmt->bindColumn('zip', $zip);
				$stmt->bindColumn('creds', $creds);

				$usrs = array();

				while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
					array_push($usrs, array('uid' => $usr_PK, 'usr' => $usrname, 'fname' => $fname, 'lname' => $lname, 'joindate' => $joindate, 'aff' => $aff_FK,
						'email' => $email, 'phone' => $phone, 'street1' => $street1, 'street2' => $street2, 'city' => $city, 'state' => $state, 'zip' => $zip, 'creds' => $creds));
				}
				return $usrs;
			} catch (PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

	public static function get_users_by_aff_creds($aff_FK, $creds){
		/*	Ulenn Terry Chern - 22 March 2016 - 5:52PM
         *	This function takes an integer input corresponding to a user's affiliation and returns an array of
         *  associated arrays containing users with that affiliation and their information.
         */
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT * FROM usr WHERE aff_FK=:aff_FK AND creds>=:creds";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':aff_FK', $aff_FK);
			$stmt->bindParam(':creds', $creds);
			try{
				$stmt->execute();
				$stmt->bindColumn('usr_PK', $usr_PK);
				$stmt->bindColumn('usrname', $usrname);
				$stmt->bindColumn('fname', $fname);
				$stmt->bindColumn('lname', $lname);
				$stmt->bindColumn('joindate', $joindate);
				$stmt->bindColumn('email', $email);
				$stmt->bindColumn('phone', $phone);
				$stmt->bindColumn('street1', $street1);
				$stmt->bindColumn('street2', $street2);
				$stmt->bindColumn('city', $city);
				$stmt->bindColumn('state', $state);
				$stmt->bindColumn('zip', $zip);
				$stmt->bindColumn('creds', $creds);

				$usrs = array();

				while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
					array_push($usrs, array('uid' => $usr_PK, 'usr' => $usrname, 'fname' => $fname, 'lname' => $lname, 'joindate' => $joindate, 'aff' => $aff_FK,
						'email' => $email, 'phone' => $phone, 'street1' => $street1, 'street2' => $street2, 'city' => $city, 'state' => $state, 'zip' => $zip, 'creds' => $creds));
				}
				return $usrs;
			} catch (PDOException $e) {
				echo $e->getMessage();
				return false;
			}
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

    public static function get_user_email($usr_PK)
    {
        // This function takes a user_PK as an int and returns their email as a string.
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT email FROM usr WHERE usr_PK=:usr_PK";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usr_PK', $usr_PK);
        $status = $stmt->execute();
		if($status){
			$stmt->bindColumn('email', $email);
			$stmt->fetch(PDO::FETCH_BOUND);
			return $email;
		} else {
			return false;
		}
    }

    public static function get_teamid($usr_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT team_FK FROM teammember WHERE usr_FK=:usr_FK";
        $stmt = $conn->prepare($sql);
		$stmt->bindParam(':usr_FK', $usr_FK);
        $status = $stmt->execute();
		if($status) {
            $stmt->bindColumn('team_FK', $team_FK);
            $stmt->fetch(PDO::FETCH_BOUND);
            return $team_FK;
        } else {
            return false;
        }
    }

	public static function get_affiliation_name($usr_PK){
		/*	Ulenn Terry Chern - 26 March 2016 - 7:28PM
		 *	This function takes an integer representing the user's primary key and returns the string value of the
		 * 	affiliate that the user belongs to; returns false and an error message if user does not exist or if
		 *  there is a problem with the database.
		 */
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT affname FROM affiliation INNER JOIN usr ON affiliation.aff_PK = usr.aff_FK WHERE usr_PK=:usr_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':usr_PK', $usr_PK);
			try {
				$stmt->execute();
				$aff = $stmt->fetchColumn();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			return $aff;
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

}
?>
