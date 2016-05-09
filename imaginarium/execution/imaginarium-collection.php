<?php

class File {

    public $filename;
    public $extension;
    public $code;

    public function __construct($name, $ext, $code) {
        $this->code = $code;
        $this->filename = $name;
        $this->extension = $ext;
    }
    
    

}

class Folder {

    public $files;
    public $folderName;

    public function __construct($folderName, $files) {
        $this->files = $files;
        $this->folderName = $folderName;
    }

}

class Request {

    public $folder;
    public $executable;
    public $watch;
    public $arguments;

    public function __construct($folder, $executable, $watch, $arguments) {
        $this->folder = $folder;
        $this->executable = $executable;
        $this->watch = $watch;
        $this->arguments = $arguments;
    }

}

class Response {

    public $compileResult = false; //True is compiles successfully.
    public $compileTime = 0; //In ms
    public $runResult = true;
    public $runTime = 0; //In ms
    public $output = "";

    public function __construct($cR, $cT, $rR, $rT, $out) {
        $this->compileResult = $cR;
        $this->compileTime = $cT;
        $this->runResult = $rR;
        $this->runTime = $rT;
        $this->output = $out;
    }

}

?>
