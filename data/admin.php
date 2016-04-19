<?php
/**
 * @author Matt Wolfman
 * @auther Terry Chern
 * @version 2.0
 * @since 4/19/2016
 * @see DatabaseConnection::getConnection() for information about the database connection
 */
require_once ($_SERVER['DOCUMENT_ROOT'].'\data\database-connection.php');

class Admin {
	/**
     * This function returns an array of associated arrays containing all information related to an affiliate
	 * @return array aff_PK, affname, email, phone, street1, street2, city, state, and zip. If it fails it returns false.
     */
    public static function get_all_affiliates(){
        $conn = DatabaseConnection::get_connection();
        $sql = 'SELECT * FROM affiliation';
		if($stmt = $conn->prepare($sql)){
			try {
                $stmt->execute();
            } catch (PDOException $e){ 
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            $stmt->bindColumn('aff_PK',$aff_PK);
            $stmt->bindColumn('affname',$affname);
            $stmt->bindColumn('email',$email);
            $stmt->bindColumn('phone',$phone);
            $stmt->bindColumn('street1',$street1);
            $stmt->bindColumn('street2',$street2);
            $stmt->bindColumn('city',$city);
            $stmt->bindColumn('state',$state);
            $stmt->bindColumn('zip',$zip);
            $affs = array();
            while($stmt->fetch(PDO::FETCH_BOUND)){
                array_push($affs, array('aff_PK'=>$aff_PK, 'affname'=>$affname, 'email'=>$email, 'phone'=>$phone, 'street1'=>$street1, 'street2'=>$street2, 'city'=>$city, 'state'=>$state, 'zip'=>$zip));
            }
            return $affs;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
    /**
	 *  This function enters a new affiliation into the database. 
	 * @param affinfo array this is affiliation name, an email address, a phone number, a street address (street 1 & 2), a city, state, and zipcode. 
	 * @return true on successful entry and false on a failed insertion.
	 */
    public static function set_affiliate($affinfo){
        $conn = DatabaseConnection::get_connection();
        $sql = 'INSERT INTO affiliation (affname, email, phone, street1, street2, city, state, zip) 
                VALUES(:affname, :email, :phone, :street1, :street2, :city, :state, :zip)';
        if($stmt = $conn->prepare($sql)){
            $stmt->bindParam(':affname', $affinfo['affname']);
            $stmt->bindParam(':email', $affinfo['email']);
            $stmt->bindParam(':phone', $affinfo['phone']);
            $stmt->bindParam(':street1', $affinfo['street1']);
            $stmt->bindParam(':street2', $affinfo['street2']);
            $stmt->bindParam(':city', $affinfo['city']);
            $stmt->bindParam(':state', $affinfo['state']);
            $stmt->bindParam(':zip', $affinfo['zip']);
            try {
                $stmt->execute();
            } catch (PDOException $e){
				//Gets the error if the query fails to execute
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
			//Fetches the SQLSTATE associated with the last operation on the database handle
            echo $stmt->errorCode();
            return false;
        }
    }
}
?>