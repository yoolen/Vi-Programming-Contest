<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 3/20/2016
 * Time: 7:22 PM
 */
require_once ($_SERVER['DOCUMENT_ROOT'].'\data\database-connection.php');

class Admin {
    public static function get_all_affiliates(){
        /*  Ulenn Terry Chern - 20 March 2016 - 7:24PM
         *  This function returns an array of associated arrays containing all information related to an affiliate
         */
        $conn = DatabaseConnection::get_connection();
        $sql = 'SELECT * FROM affiliation';
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
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
                array_push($affs, array('aff_PK'=>$aff_PK, 'affname'=>$affname, 'email'=>$email, 'phone'=>$phone, 'street1'=>$street1,
                    'street2'=>$street2, 'city'=>$city, 'state'=>$state, 'zip'=>$zip));
            }
            return $affs;
        } else {
            return false;
        }
    }

    public static function set_affiliate($affinfo){
        /*  Ulenn Terry Chern - 20 March 2016 - 8:27PM
         *  This function takes an affiliation name, an email address, a phone number, a street address (street 1 & 2), a
         *  city, state, and zipcode and enters a new affiliation into the database. It returns 1 on successful entry and
         *  0 on a failed insertion.
         */
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

            // PDO has silent errors on insertion, cannot use $status flag to catch these errors.
            try {
                $stmt->execute();
            } catch (PDOException $e){
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }

    }
}