<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/29/2016
 * Time: 7:42 PM
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '/data/db-info.php');

class Team
{

    protected static $db;

    private function get_connection_mysqli()
    {
        self::$db = new mysqli(SERVER, USERNAME, PASSWD, SCHEMA);
        if (self::$db->connect_error) {
            die("Connection failed: " . self::$db->connect_error);
        }
        return self::$db;
    }

    public static function create_team($aff, $contact, $coach){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "INSERT INTO team (aff_FK, contact_FK, coach_FK) VALUES (?,?,?)";
        if( $stmt = $conn->prepare($sql) ){
            $stmt->bind_param('iii',$aff,$contact,$coach);
            $stmt->execute();
            $team = $stmt->insert_id;
            $stmt->close();
        } else {
            echo 'Error inserting.';
        }
        if(!$conn->commit()){
            print("Commit error.");
            $conn->close();
            exit();
        }
        $conn->close();
        return $team;
    }

    public static function add_team_member($teamid, $usr){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "INSERT INTO teammember(team_FK,usr_FK) VALUES (?,?)";
        if( $stmt = $conn->prepare($sql) ){
            $stmt->bind_param('ii',$teamid,$usr);
            $stmt->execute();
            $stmt->close();
        } else {
            echo 'Error inserting.';
        }
        if(!$conn->commit()){
            print('Commit error.');
            $conn->close();
            exit();
        }
        $conn->close();
    }

    public static function remove_team_member($teamid, $usr){
        $conn = self::get_connection_mysqli();
        $conn->autocommit(false);
        $sql = "DELETE FROM teammember WHERE team_FK=? AND usr_FK=?";
        if( $stmt = $conn->prepare($sql) ){
            $stmt->bind_param('ii',$teamid,$usr);
            $stmt->execute();
            $stmt->close();
        } else {
            echo 'Error deleting.';
        }
        if(!$conn->commit()){
            print('Commit error.');
            $conn->close();
            exit();
        }
        $conn->close();
    }

    public static function get_assigned_contests($teamid){
        $conn = self::get_connection_mysqli();

        $sql = "SELECT * FROM contestview WHERE team_FK=?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param('i',$teamid);
            $stmt->execute();
            $stmt->bind_result();
        }
    }
}