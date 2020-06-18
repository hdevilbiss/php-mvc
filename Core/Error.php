<?php
namespace Core;
/*
*   ERROR AND EXCEPTION HANDLER
*/
class Error {
    /*METHOD: "errorHandler"
    *   CONVERT ERRORS INTO EXCEPTIONS.
    *   @param int      :   $level = Error level
    *   @param string   :   $message = Error message
    *   @param string   :   $file = File name in which error was raised
    *   @param int      :   $line = Line number within the file
    *   @return void    :   "throw new" keywords
    */
    public static function errorHandler($level,$message,$file,$line) {
        if (\error_reporting()!==0) {
            throw new \ErrorException($message,0,$level,$file,$line);
        }
    }//close function, "errorHandler"

    /*METHOD: "exceptionHandler"
    *   @param Exception    :   $exception = The exception
    *   @return void        :   Echo or log Exception information
    */
    public static function exceptionHandler($exception) {
        // Code is either 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        
        /* List the previous status code ? */
        http_response_code($code);
        
        /* Check the current config settings for error reporting (local or live) */
        
        /* LOCAL SETTING */
        if (\App\Config::SHOW_ERRORS) {
            echo "<h1>Fatal Error!</h1>";
            echo "<p>Uncaught Exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack Trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
        }
        
        /* LIVE SETTING */
        else {
            /* Set the log filename and create a log */
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            
            /* Set the value of a config setting: error_log */
            ini_set('error_log',$log);

            /* Append all exception info to a message */
            $message = "Uncaught Exception: '" . get_class($exception) . "'";
            $message .= " with message: '" . $exception->getMessage() . "'.";
            $message .= "\n Stack trace: " . $exception->getTraceAsString() . ".";
            $message .= "\n Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

            /* Log the error and display a generic message */
            error_log($message);
            View::renderTemplate("$code.html");
        }
    }//close function, "exceptionHandler"
}
?>