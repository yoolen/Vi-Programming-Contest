<?php
class Home extends Page {
  public function getPageTitle() {
    return "NJIT Programming Contest for High School Students!";
  }

  public function getPageContent() {
    if (!isset($_SESSION['creds']) or $_SESSION['creds'] <= 0) {
        echo <<<HOME
        <h2>Computer Programming Contest for High School Students</h2>

        <div style="float: right; border: 2px black solid; border-radius: 3px; width: 40%; height: 150px; padding: 10px;">
        <h3>See photos on our Flickr </h3>
				<p>
        We&#39;ve got photos on our NJIT Flickr photo sharing site at <a href="http://www.flickr.com/photos/njit/collections/72157606418010799/">http://www.flickr.com/njit</a>

				</p>
        </div>

        Every year, the Computer Science Department at NJIT holds a High School Programming Contest.
        <p>Talented students from high schools throughout the state of New Jersey come to the NJIT campus to participate in a programming competition.&nbsp; Participating teams compete by completing programming assignments and are ranked according to the number of correct programs they deliver within a predefined period of time. Top-ranked teams are awarded a plaque.</p>
        <p>Teams are made up of three programmers, supervised by a coach. Each high school is allowed only one team. Registration is $50.00 per team.</p>
        <ul>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2012/index.php">2012 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2011/index.php">2011 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2010/index.php">2010 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2009/index.php">2009 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2008/index.php">2008 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2007/index.php">2007 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2006/index.php">2006 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2005/index.php">2005 contest</a></li>
            <li><a href="http://cs.njit.edu/news/programmingcontest//cs/news/programmingcontest/2005/2004_info.php">2004 contest</a></li>
        </ul>
        <p></p>

HOME;
    } else {
      $user = User::get_user($_SESSION['uid']);
      $affiliation = User::get_affiliation_name($_SESSION['uid']);
      ?>
        <div class="dashboard">
          <h2><?php echo "Welcome Back ".$user['fname'].' '.$user['lname']."!"; ?></h2>
          <div class="row">
            <div class="col-md-6">
              <div class="row">
                <div class="panel panel-default">
                  <div class="panel-heading">User Information</div>
                  <div class="panel-body">
                      <b>Name: </b> <?php echo $user['fname'].' '.$user['lname'].""; ?>
                      <br>
                      <b>Email: </b> <?php echo $user['email'].""; ?>
                      <br><br>
                      <b>Affiliation: </b> <?php echo $affiliation.""; ?>
                      <br><br>
                      <!--a href='../_settings/' class='btn btn-default'> Click here to change account settings.</a-->
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="row">
                <div class="panel panel-default">
                  <div class="panel-heading">Contests</div>
                  <div class="panel-body">
                      <?php
                        require_once $_SERVER['DOCUMENT_ROOT']."/data/contest.php";
                        $contests = Contest::get_all_contests();
                        foreach($contests as $contest) {
                            echo '<b>Contest Name:</b> '.$contest['name'].'<br>';
                        }
                      ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php
    }
    return "";
  }
}
?>
