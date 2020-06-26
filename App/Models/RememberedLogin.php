<?php
namespace App\Models;

use \App\Token;
use PDO;

/* RememberedLogin model */
class RememberedLogin extends \Core\Model {

    
    /* METHOD, delete
    * @param void   :
    * @return void  : Delete the corresponding record in the DB
    */
    public function delete() {
        $sql = 'DELETE FROM rememberedLogins WHERE token_hash = :token_hash';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash',$this->token_hash,PDO::PARAM_STR);
        $stmt->execute();
    }
    

    /* METHOD, findByToken (@param string, @return mixed)
    Given the remember login token, return the Login object or false.*/
    public static function findByToken($token) {
        //Create a new token object and then hash it
        $token = new Token($token);
        $token_hash = $token->getHash();

        //Use the new token hash to select all columns from all relevant records
        $sql = 'SELECT * FROM rememberedLogins WHERE token_hash = :token_hash';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':token_hash',$token_hash,PDO::PARAM_STR);

        //FETCH_CLASS = Fetch the class of the PDO object
        //get_called_class = The class name will be the class name in which this static function is called
        $stmt->setFetchMode(PDO::FETCH_CLASS,get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }


    /* METHOD, getUser (@param void, @return User)
    */
    public function getUser() {
        return User::findByID($this->user_id);
    }


    /* METHOD, hasExpired
    *   @param void     :
    *   @return boolean : Convert the expires_at column in the DB to a Unix timestamp and then compare to the current timestamp.
    */
    public function hasExpired() {
        return strtotime($this->expires_at) < time();
    }
}
?>