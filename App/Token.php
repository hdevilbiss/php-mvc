<?php
namespace App;

/* Token class to generate unique, random tokens */
/*
    $token = new \App\Token();
*/
class Token {

    /* Variables */
    protected $token;//@var array

    /* MAGIC METHOD: __construct
    *   @param void     :
    *   @return void    :   Run this code when a new instance of Token is created
    */
    public function __construct($token_value = null)//optional argument
    {
        if ($token_value) {
            // Set the value at index "token" for the calling Token object equal to the parameter
            $this->token = $token_value;
        } else {
            // Create a new token by generating random bytes and then converting binary to hex
            $this->token = bin2hex(random_bytes(16));
        }
    }

    /* METHOD: getHash
    *   @param void     :
    *   @return string  :   Return the hashed value of the token
    */
    public function getHash() {
        return hash_hmac('sha256',$this->token,\App\Config::HASH_KEY);//sha256 = 64 chars
    }

    /* METHOD: getValue
    *   @param void     :
    *   @return string  :   Return the current string saved at the $token index of the calling Token object
    */
    public function getValue() {
        return $this->token;
    }
}
?>