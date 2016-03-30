<?php

/**
 * File Backend Functions
 * 
 * @author Jan Chris Tacbianan
 * @author Matthew Wolfman
 * @version 1.0 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . '\data\database-connection.php');

class File_Functions {

    /**
     * Create File
     * @param str $name The Name of the File
     * @param str $extension The Extension of the File
     * @param int $folder The Folder/Project to which the file belongs to
     * @returns A boolean value noting whether the operation was a success or failure.
     */
    public static function create_file($name, $extension, $folder) {
        $empty = "";
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO file (name, extension, content,folder) VALUES (:name,:extension,:content,:folder)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':extension', $extension);
            $stmt->bindParam(':content', $empty);
            $stmt->bindParam(':folder', $folder);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Save File
     * @param int $fileId The file that is being updated
     * @param str $content The content of the file to be saved.
     * @return A boolean value noting whether the operation was a success or failure.                                                                      
     */
    public static function save_file($fileId, $content) {
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE file SET content=:content WHERE fileId=:fileId";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':fileId', $fileId);
            $stmt->bindParam(':content', $content);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Rename File
     * @param int $fileId
     * @param str $fileName
     * @param str $fileExtension
     * @return A boolean value noting whether the operation was a success or failure. 
     */
    public static function rename_file($fileId, $name, $extension) {
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE file SET name=:name, extension=:extension WHERE fileId=:fileId";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':fileId', $fileId);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':extension', $extension);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Retrieve File
     * 
     * @param type $fileId
     */
    public static function retrieve_file($fileId) {
        $connection = DatabaseConnection::get_connection();
        $query = "select * from file where fileId=:fileId";
        $statement = $connection->prepare($query);
        $statement->bindParam(':fileId', $fileId);
        $result = $statement->execute();
        if ($result) {
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $fileData = $statement->fetch();
            return $fileData;
        } else {
            return false;
        }
    }

    /**
     * Delete File
     * 
     * @param type $fileId
     * @return A boolean value noting whether the operation was a success or failure. 
     */
    public static function delete_file($fileId) {
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM file WHERE fileId=:fileId";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':fileId', $fileId);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Create Folder
     * 
     * @param int $owner The owner of the folder
     * @param bool $teamShare A boolean value denoting whether or not the folder is visible to the owner's team.
     * @param bool $contestRelated A boolean value denoting whether or not the folder is a contest entry
     * @param str $name The name of the folder.
     * @return A boolean value noting whether the operation was a success or failure. 
     */
    public static function create_folder($owner, $teamShare, $contestRelated, $name) {
        $conn = DatabaseConnection::get_connection();
        $sql = "INSERT INTO folder (owner, teamShare, contestRelated,name) VALUES (:owner, :teamShare, :contestRelated,:name)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':owner', $owner);
            $stmt->bindParam(':teamShare', $teamShare);
            $stmt->bindParam(':contestRelated', $contestRelated);
            $stmt->bindParam(':name', $name);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Delete Folder
     * 
     * @param type $folderId
     * @return A boolean value noting whether the operation was a success or failure. 
     */
    public static function delete_folder($folderId) {
        $conn = DatabaseConnection::get_connection();
        $sql = "DELETE FROM folder WHERE folderId=:folderId";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':folderId', $folderId);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Retrieve User Folders
     * 
     * @param type $userId
     * @return An array containing all NON CONTEST RELATED folders pertaining to a particular user. Format should be an associative array of folder ids to folder names.
     */
    public static function retrieve_user_folders($owner) {
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT * FROM folder WHERE owner=:owner AND contestRelated = 0";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':owner', $owner);
        $status = $stmt->execute();
        if ($status) {
            $stmt->bindColumn('folderId', $folderId);
            $stmt->bindColumn('owner', $owner);
            $stmt->bindColumn('teamShare', $teamShare);
            $stmt->bindColumn('contestRelated', $contestRelated);
            $stmt->bindColumn('name', $name);
            $folders = array();
            while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
                array_push($folders, array('folderId' => $folderId, 'owner' => $owner, 'teamShare' => $teamShare, 'contestRelated' => $contestRelated, 'name' => $name));
            }
            return $folders;
        } else {
            return false;
        }
    }

    /**
     * Retrieve User Accessible Folders
     * 
     * @param type $userId
     * @return An array containing all NON CONTEST RELATED folders pertaining to a particular user. <br>
     *          Must ALSO contain folders that teammates have denoted as team shared. <br>
     *          Format should be an associative array of folder ids to folder names. <br>
     */
    public static function retrieve_accessible_folders($usr_FK) {
        $conn = DatabaseConnection::get_connection();
        $sql = "SELECT usr_FK FROM teammember WHERE team_FK = (SELECT team_FK FROM teammember WHERE usr_FK=:usr_FK)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usr_FK', $usr_FK);
        $status = $stmt->execute();
        if ($status) {
			$stmt->bindColumn('usr_FK', $usr_FK);
			$teams = array();
			while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
				array_push($teams, array('usr_FK' => $usr_FK));
			}
			if($teams){
				$folders = array();
				for ($i = 0; $i < count($teams); $i++) {
					$sql = "SELECT * FROM folder WHERE owner=:owner AND contestRelated = 0 AND teamShare = 1";
					$stmt = $conn->prepare($sql);
					$stmt->bindParam(':owner', $teams[$i]['usr_FK']);
					$status = $stmt->execute();
					if ($status) {
						$stmt->bindColumn('folderId', $folderId);
						$stmt->bindColumn('owner', $owner);
						$stmt->bindColumn('teamShare', $teamShare);
						$stmt->bindColumn('contestRelated', $contestRelated);
						$stmt->bindColumn('name', $name);
						while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
							array_push($folders, array('folderId' => $folderId, 'owner' => $owner, 'teamShare' => $teamShare, 'contestRelated' => $contestRelated, 'name' => $name));
						}
					} else {
						return false;
					}
				}
				return $folders;
			} else {
				return File_Functions::retrieve_user_folders($usr_FK);
			}
		} else {
			return false;
		}
    }

    /**
     * Rename Folder
     * 
     * @param type $folderId
     * @param type $folderName
     * @return A boolean value noting whether the operation was a success or failure. 
     */
    public static function rename_folder($folderId, $name) {
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE folder SET name=:name WHERE folderId=:folderId";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':folderId', $folderId);
            $stmt->bindParam(':name', $name);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
            return true;
        } else {
            echo $stmt->errorCode();
            return false;
        }
    }

    /**
     * Toggle Share Folder
     * 
     * Enables or disables team sharing of a folder.
     * 
     * @param type $folderId
     * @return A boolean value noting whether the operation was a success or failure. 
     */
    public static function toggle_share_folder($folderId) {
        $conn = DatabaseConnection::get_connection();
        $sql = "UPDATE folder SET teamShare = CASE WHEN teamShare < 1 THEN '1' WHEN teamShare >= 1 THEN '0' END WHERE folderId=:folderId";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(':folderId', $folderId);
            try {
                $stmt->execute();
            } catch (PDOException $e) {
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

?>
