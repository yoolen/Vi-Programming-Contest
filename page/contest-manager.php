<?php
class contestManager extends Page {

	public function getPageTitle() {
		return "Contest Management";
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
		if (isset($_GET['sub'])) {
			switch ($_GET['sub']) {
				case 'create':
					require_once '/contest-management/newContest.php';
					return '';
				case 'modify':
					if (isset($_GET['unit'])) {
						switch ($_GET['unit']){
							case 'newQ':
								require_once '/contest-management/newQuestion.php';
								return '';
							case 'addQ':
								require_once '/contest-management/addQuestion.php';
								return '';
							case 'editQ':
								require_once '/contest-management/editQuestion.php';
								return '';
							case 'qtc':
								require_once '/contest-management/questionTestCases.php';
								return '';
							case 'etc':
								require_once '/contest-management/editTestCase.php';
								return '';
							case 'atc':
								require_once '/contest-management/addTestCase.php';
								return '';
							default:
						}
//						require_once '/contest-management/contestQuestions.php';
//						return '';
					}
					require_once '/contest-management/contestQuestions.php';
					return '';
				default:

			}
		} else {
			require_once '/contest-management/view-all-contests-page.php';
			return '';
		}
	}
}