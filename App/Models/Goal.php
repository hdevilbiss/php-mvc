<?php
namespace App\Models;

/* Goal Model */

use PDO;

/* Post Model */
class Goal extends \Core\Model {
    

    /* METHOD, delete
    * @param void   :
    * @return void  : Delete the corresponding record in the DB
    */
    public function delete() {
        $sql = 'DELETE FROM goals WHERE token_hash = :token_hash';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash',$this->token_hash,PDO::PARAM_STR);
        $stmt->execute();
    }

    
    /* getAll
    *   @param void     :
    *   @return array   :   $goals Array of goals
    */
    public static function getAll() {
        /* Get database connection */
        $db = static::getDB();

        /* Write out the SQL statement */
        $stmt = $db->query('SELECT id,title,content FROM goals ORDER BY created_at');
    
        /* Fetch all the records as an array */
        $goals = $stmt->fetchAll(PDO::FETCH_ASSOC);

        /* Return the array */
        return $goals;
    }
}
?>