<?php

class Imaginarium {

    public function getPageTitle() {
        return "NJIT Programming Contest for High School Students!";
    }

    public function getPageImports() {
        return "";
    }

    public function getInitialization() {
        return "";
    }

    public function onLoad() {
        return "";
    }

    public function getPageContent() {
        echo <<<CONTENT
<h2>Code Imaginarium</h2>
<p>The code imaginarium is a programming environment for you to write, test, and share your code. The development environment will be exactly the same as if you are taking a contest. Take an opportunity with your team to get well acquainted with the system and practice for upcoming contests!</p>
CONTENT;
        require_once $_SERVER['DOCUMENT_ROOT'] . "/data/files.php";
        $user_folders = File_Functions::retrieve_user_folders($_SESSION['uid']);
        $shared = File_Functions::retrieve_accessible_folders($_SESSION['uid']);
        echo "<h4><b>My Folders</b></h4>\n";
        echo '<a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-folder-open"></span> Create new Folder </a><br><br>';
        echo "<div class='panel-group'>";
        foreach ($user_folders as $f) {
            ?>
            <div class="panel panel-default">
                <div class="panel-heading"><a href="#uf<?php echo $f['folderId'] ?>" data-toggle="collapse"><?php echo $f['name'] ?> <span class="caret"></span></a></div>
                <div id="uf<?php echo $f['folderId'] . "" ?>" class="panel-body panel-collapse collapse">
                    <?php
                    echo "<b>Folder Name:</b> " . $f['name'] . '&ensp; <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-pencil"></span> Rename Folder </a><br>';
                    if ($f['teamShare'] == 0) {
                        echo '<b>Shared with Team: </b> No. <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-share"></span> Share with Team </a>';
                    } else {
                        echo '<b>Shared with Team: </b> Yes. <a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-edit"></span> Unshare with Team </a>';
                    }
                    echo '<br><a href="#" class="btn btn-default btn-sm"> <span class="glyphicon glyphicon-trash"></span> Delete Folder </a><br>';
                    echo "<h4><b>Folder Files</b></h4>";
                    $files = File_Functions::retrieve_folder_files($f['folderId']);
                    echo <<<TSET
                    <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>File Name</th>
                            <th>Open File</th>
                            <th>Rename File</th>
                            <th>Download File</th>
                            <th>Delete File</th>
                          </tr>
                        </thead>
                        <tbody>
TSET;
                    foreach ($files as $file) {
                        echo '<tr>';
                        echo '<td>'.$file['name'].'.'.$file['ext'].'</td>';
                        echo '<td style="text-align: center;"><a href="http://njit1.initiateid.com/imaginarium2.0/imaginarium.php?file='.$file['fileId'].'" class="btn btn-default btn-sm">Open File <span class="glyphicon glyphicon-open"></span></a>'.'</td>';
                        echo '<td style="text-align: center;"><a href="#" class="btn btn-default btn-sm">Rename File <span class="glyphicon glyphicon-edit"></span></a>'.'</td>';
                        echo '<td style="text-align: center;"><a href="../=download_'.$file['fileId'].'" class="btn btn-default btn-sm">Download File <span class="glyphicon glyphicon-download-alt"></span></a>'.'</td>';
                        echo '<td style="text-align: center;"><a href="#" class="btn btn-default btn-sm">Delete File <span class="glyphicon glyphicon-trash"></span></a>'.'</td>';
                        echo '</tr>';
                    }
                    echo "</tbody></table>";
                    ?>
                </div>
            </div>
            <?php
        }
        echo "\n</div>";
        return "";
    }

}
?>