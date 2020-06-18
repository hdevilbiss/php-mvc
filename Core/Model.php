<?php
namespace Core;

use PDO;
use App\Config;
/* 
*   Base Model
*/

abstract class Model {
    /* METHOD: "getDB"
    *   Get the PDO connection
    *   @return database object
    */
    protected static function getDB() {
        //The database connection is cached by using a static variable, whose value is remembered between calls to the method - the connection is reused, saving resources.
        static $db = null;

        /* On the first call, the database connection will be null */
        if ($db === null) {
            /* Setup the string to list the host and database */
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';

            /* Connect to the database with username and password */
            $db = new PDO($dsn,Config::DB_USER, Config::DB_PW);
            
            /* Throw an Exception if an error occurs */
            $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
         /* Return the connection */
         return $db;
    }//close function, "getDB"
}
?>