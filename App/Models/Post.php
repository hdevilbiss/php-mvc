<?php
namespace App\Models;

/* Post Model */

// Use the PDO namespace without prefixing
use PDO;

/* Post Model */
class Post extends \Core\Model {
    /* getAll
    *   @param void     :
    *   @return array   :   Get all posts as an associative array
    */
    public static function getAll() {
        /* Get database connection */
        $db = static::getDB();

        /* Write out the SQL statement */
        $stmt = $db->query('SELECT id,title,content FROM posts ORDER BY created_at');
    
        /* Fetch all the records as an array */
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Return the array */
        return $results;
    }//close function, "getAll"
}
?>