<?php
/**
 * Created by PhpStorm.
 * User: yoolen
 * Date: 2/29/2016
 * Time: 7:42 PM
 */
 require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class Team{
    public static function create_team($aff_FK, $contact_FK, $coach_FK, $teamname){
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO team (aff_FK, contact_FK, coach_FK, teamname) VALUES (:aff_FK,:contact_FK,:coach_FK,:teamname)";
        if($stmt = $conn->prepare($sql)){
       		$stmt->bindParam(':aff_FK', $aff_FK);
			$stmt->bindParam(':contact_FK', $contact_FK);
			$stmt->bindParam(':coach_FK', $coach_FK);
			$stmt->bindParam(':teamname', $teamname);

			try{
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			return $conn->lastInsertId();
		} else {
			echo $stmt->errorCode();
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

	public static function get_all_teams(){
	/*	Ulenn Terry Chern - 20 March 2016 - 7:20PM
	 *	This function returns an array of all the teams and their related information
	 */	
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT team.team_PK, team.teamname, affiliation.affname, CONCAT(usr1.fname,' ',usr1.lname) AS contactname, CONCAT(usr2.fname,' ',usr2.lname) AS coachname 
				FROM team 
				INNER JOIN affiliation ON team.aff_FK = affiliation.aff_PK 
				INNER JOIN usr AS usr1 ON team.contact_FK=usr1.usr_PK 
				INNER JOIN usr AS usr2 ON team.coach_FK=usr2.usr_PK";

		if($stmt = $conn->prepare($sql)){
			$stmt->bindColumn('team_PK',$team_PK);
			$stmt->bindColumn('teamname',$teamname);
			$stmt->bindColumn('affname',$aff);
			$stmt->bindColumn('contactname',$contact);
			$stmt->bindColumn('coachname',$coach);
			try{
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			$teams = array();
			while($stmt->fetch(PDO::FETCH_BOUND)){
				array_push($teams, array('team_PK'=>$team_PK,'teamname'=>$teamname,'aff'=>$aff,'contact'=>$contact,'coach'=>$coach));
			}
			return $teams;
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

	public static function get_team_info($team_PK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT team.teamname, team.aff_FK, affiliation.affname, team.contact_FK, CONCAT(usr1.fname,' ',usr1.lname) AS contactname, team.coach_FK, CONCAT(usr2.fname,' ',usr2.lname) AS coachname 
				FROM team 
				INNER JOIN affiliation ON team.aff_FK = affiliation.aff_PK 
				INNER JOIN usr AS usr1 ON team.contact_FK=usr1.usr_PK 
				INNER JOIN usr AS usr2 ON team.coach_FK=usr2.usr_PK
				WHERE team.team_PK=:team_PK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':team_PK', $team_PK);
			try{
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}

			$stmt->bindColumn('teamname',$teamname);
			$stmt->bindColumn('aff_FK', $aff_FK);
			$stmt->bindColumn('affname',$aff);
			$stmt->bindColumn('contact_FK', $contact_FK);
			$stmt->bindColumn('contactname',$contact);
			$stmt->bindColumn('coach_FK', $coach_FK);
			$stmt->bindColumn('coachname',$coach);

			$stmt->fetch(PDO::FETCH_BOUND);
			return array('team_PK'=>$team_PK,'teamname'=>$teamname,'aff_FK'=>$aff_FK,'aff'=>$aff,'contact_FK'=>$contact_FK,
				'contact'=>$contact,'coach_FK'=>$coach_FK,'coach'=>$coach);
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}

	public static function get_team_members($team_FK){
		$conn = DatabaseConnection::get_connection();
		$sql = "SELECT teammember.usr_FK, CONCAT(usr.fname,' ',usr.lname) AS usrname
				FROM teammember
				INNER JOIN usr ON teammember.usr_FK=usr.usr_PK
				WHERE team_FK=:team_FK";
		if($stmt = $conn->prepare($sql)){
			$stmt->bindParam(':team_FK', $team_FK);
			try {
				$stmt->execute();
			} catch (PDOException $e){
				echo $e->getMessage();
				return false;
			}
			$stmt->bindColumn('usr_FK',$usr_FK);
			$stmt->bindColumn('usrname',$usrname);
			$teammembers = array();
			while($stmt->fetch(PDO::FETCH_BOUND)){
				array_push($teammembers, array('uid'=>$usr_FK,'usrname'=>$usrname));
			}
			return $teammembers;
		} else {
			echo $stmt->errorCode();
			return false;
		}
	}
}
?>