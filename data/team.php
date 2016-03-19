<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/29/2016
 * Time: 7:42 PM
 */
 require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class Team{
    public static function create_team($aff_FK, $contact_FK, $coach_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO team (aff_FK, contact_FK, coach_FK) VALUES (:aff_FK,:contact_FK,:coach_FK)";
        $stmt = $conn->prepare($sql);
       	$stmt->bindParam(':aff_FK', $aff_FK);
		$stmt->bindParam(':contact_FK', $contact_FK);
		$stmt->bindParam(':coach_FK', $coach_FK);
        $status = $stmt->execute();
		if($status){
			$team = $conn->lastInsertId();
			return $team;
		} else {
			return false;
		}
    }

    public static function add_team_member($team_FK, $usr_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO teammember(team_FK,usr_FK) VALUES (:team_FK,:usr_FK)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':team_FK', $team_FK);
		$stmt->bindParam(':usr_FK', $usr_FK);
        $status = $stmt->execute();
		if($status){
			$team = $conn->lastInsertId();
			return $team;
		} else {
			return false;
		}
    }

    public static function remove_team_member($team_FK, $usr_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM teammember WHERE team_FK=:team_FK AND usr_FK=:usr_FK";
		$stmt = $conn->prepare($sql);
        $stmt->bindParam(':team_FK', $team_FK);
		$stmt->bindParam(':usr_FK', $usr_FK);
        $status = $stmt->execute();
		if($status){
			return true;
		} else {
			return false;
		}
    }

    public static function get_assigned_contests($team_FK){
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT contest_FK FROM contestview WHERE team_FK=:team_FK";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':team_FK', $team_FK);
        $status = $stmt->execute();
		if($status) {
            $stmt->bindColumn('contest_FK', $contest_FK);
            $contests = array();
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($contests, array('cid'=>$contest_FK));
			}
			return $contests;
		} else {
			return false;
		}
    }
}
?>