<?php
/**
 * CS490 Project Fall 2015
 *
 * Classes
 *
 * Stores the classes (and factories) for requesting and responding to compilation/execution requests
 *
 * @author Jan Chris Tacbianan
 */
 
//Class Representation of a Request
 class Request {
	  /* Types:
	  * java/output - Takes in a Java Snippet and returns the output.
	  * java/file - Takes in a Java Snippet and returns the output of a specified file.
	  * python/output - Takes in a Python Snippet and returns the output.
	  * python/test - Takes in a Python Snippet and returns the output. The inputs field will be appended to the bottom of the code.
	  * python/file - Takes in a Python Snippet and returns the output of a specified file.
	  * python/turtle - Takes in a Python Snippet and returns the output of the tkinter canvas postscript.
	  * 
	  * Parameter Explantion:
	  *     type - The type of request (notes above)
	  *     code - The submitted code to be tested.
	  *     inputs - The inputs to be run. For python/test, the inputs will be appended to the bottom of the code.
	  *     executionCode - For python/test, the execution code is appended to the top of the code.
	  */
	 public $type = "";
	 public $code = "";
	 public $inputs = "";
	 public $executionCode = "";
	 
	 public function __construct($t, $c, $i, $e = "") {
		 $this->type  = $t;
		 $this->code = $c;
		 $this->inputs = $i;
		 $this->executionCode = $e;
	 }
}

//Class Representation of a Response
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

//Class Factory for Responses 
class ResponseFactory {
	static function resultFactory($cR, $cT, $rR, $rT, $out) {
		$result = new Response($cR, $cT, $rR, $rT, $out);
		echo json_encode($result);
	}
}

?>