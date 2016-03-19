<?php

/**
 * CS490 Project Fall 2015
 *
 * Helper
 *
 * Generates Useful Code Hints for Code Compilation and Execution
 *
 * @author Jan Chris Tacbianan
 */
class Java {

    public static function compilation($code, $result) {
        $compilation = array(
            "';' expected" => 'You may have forgotten to include a semicolon! Check your code at the line number specified in the error message. This error may also pop up if you forgot to use braces!',
            "'(' expected" => 'You may have forgotten to include a parenthesis! Check your code at the line number specified in the error message',
            "identifier" => 'Looks like you are missing an identifier. Check that all your variables and methods have names. This error may also pop up if you forgot to close brackets or braces.',
            ": error: package" => 'Looks like you  tried to reference a package or class that does not exist. Make sure everything is spelled correctly!',
            "error: cannot find symbol" => 'Looks like you  tried to reference a variable or method that does not exist. Make sure everything is spelled and defined correctly!',
            "bad operand types for" => "Looks like you tried to use an operator with incompatible data types! Make sure you have casted or reference your items correctly."
        );
        foreach ($compilation as $k => $v) {
            if (strstr(strtok($result, "\n"), $k) !== false) {
                return $v;
            }
        }
        return "Sorry. We currently do not have any hints for you at the moment.";
    }
    
    public static function execution($code, $result) {
        $execution = array(
            "java.lang.ArithmeticException: / by zero" => 'Looks like you tried to divide by zero! Make sure that you check for division cases like that!',
        );
        if(strlen($result) == 0) {
            return "Looks like we didn't find a main method! Check that you have an entry point for your application!";
        }
        foreach ($execution as $k => $v) {
            if (strstr(strtok($result, "\n"), $k) !== false) {
                return $v;
            }
        }
        return "Sorry. We currently do not have any hints for you at the moment.";
    }

}

class Python {
	
	public static function execution($code, $result) {
        $execution = array(
            "SyntaxError" => 'There was a syntax error! Check for errors in your code.',
			"TypeError" => 'Make sure the operations you are performing are supported (like adding strings and ints)',
			"ValueError" => 'Invalid value was inputted for a specific data type.',
			"ZeroDivisionError" => 'Did you attempt to divide by zero?',
			"IndexError" => 'You appear to have been attempting to access an invalid index.',
        );
        foreach ($execution as $k => $v) {
            if (strstr(strtok($result, "\n"), $k) !== false) {
                return $v;
            }
        }
        return "Sorry. We currently do not have any hints for you at the moment.";
    }
}

class Utility {

    public static function contains($needle, $haystack) {
        return strpos($haystack, $needle) !== false;
    }

}

?>