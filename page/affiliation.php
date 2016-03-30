<?php
class Affiliation extends Page {

    public function getPageTitle() {
        return "Affiliation Manager";
    }

    public function getInitialization() {
        return "";
    }

    public function getPageContent() {
        require_once '/admin/affiliation-page.php';
    }
}

?>
