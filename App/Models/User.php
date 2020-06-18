<?php
namespace App\Models;
use PDO;
use \App\Token;

/* User Model */
class User extends \Core\Model {
    /* Array to save error messages */
    public $errors = [];

    /* MAGIC METHOD: __construct
    *   @param array    :   $data from $_POST (optional)
    *   @return void    :   Create a User object from $_POST array
    */
    public function __construct($data = []) {
        /* Loop through the $_POST array */
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    /* METHOD: authenticate
    *   @param string   :   $email from login form
    *   @param string   :   $password from login form
    *   @return mixed   :   If Password matches its hash, then return the User object. Otherwise, false.
    */
    public static function authenticate($email,$password) {
        // Search the user in the database using a custom, static User method
        $user = static::findByEmail($email);

        if ($user) {
            // Verify password
            if (password_verify($password,$user->user_password_hash)) {
                return $user;
            }
        }
        return false;
    }

    /* METHOD: emailExists
    *   @param string   :   $email from login form
    *   @return boolean :   Search for $email in the DB (unique index) using a custom, static User method
    */
    public static function emailExists($email) {
        //returns true if the static User method returns a User object
        return static::findByEmail($email) !== false;
    }

    /* METHOD: findByEmail
    *   @param string   :   $email from a user input
    *   @return mixed   :   Either returns a
    */
    public static function findByEmail($email) {
        //Parametrized query
        $sql = 'SELECT * FROM users WHERE user_email = :email';

        //Make database connection using a static method of the core Model
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':email',$email,PDO::PARAM_STR);

        //We want to return a User object, not an array, so change the PDO fetch mode
        $stmt->setFetchMode(PDO::FETCH_CLASS,get_called_class());
        //get_called_class returns the name of the class that the static method (findByEmail) is called in

        $stmt->execute();
        return $stmt->fetch();//fetch returns false if nothing is found
    }

    /* METHOD: findByID
    *   @param $string  :   The $user user_id
    *   @return mixed   :   User object if found; otherwise false
    */
    public static function findByID($id) {
        $sql = 'SELECT * FROM users WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id',$id,PDO::PARAM_INT);//user_id is an INT

        $stmt->setFetchMode(PDO::FETCH_CLASS,get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /* METHOD: rememberLogin
    *   @param void         :
    *   @return boolean     :   Save a new row in the rememberedLogins table
    */
    public function rememberLogin() {
        //Generate a new token and hash
        $token = new Token();
        $hashed_token = $token->getHash();

        //Save the value and expiration date for later cookie setting
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 *24 * 30;//30 days

        // Prepare DB connection
        $sql = 'INSERT INTO rememberedlogins (token_hash,user_id,expires_at) VALUES (:token_hash,:user_id,:expires_at)';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        if (\App\Config::SHOW_ERRORS===true) {
            echo "$this->expiry_timestamp<br>";
            echo date('Y-m-d H:i:s',$this->expiry_timestamp);
        }

        // Bind values
        $stmt->bindValue(':token_hash',$hashed_token,PDO::PARAM_STR);
        $stmt->bindValue(':user_id',$this->user_id,PDO::PARAM_INT);
        $stmt->bindValue(':expires_at',date('Y-m-d H:i:s',$this->expiry_timestamp),PDO::PARAM_STR);//convert datetime to string

        return $stmt->execute();
    }

    /* METHOD: save
    *   @param void     : 
    *   @return boolean :   Save form data to the database
    */
    public function save() {
        /* Validate the inputs */
        $this->validate();

        /* Check for error messages before database actions */
        if (empty($this->errors)) {
            /* Salt that password */
            $password_hash = password_hash($this->password,PASSWORD_DEFAULT);
            
            /* Parameter-ized SQL query */
            $sql = 'INSERT INTO users (user_name,user_email,user_password_hash) VALUES (:name,:email,:password_hash)';

            /* Make the database connection using the static method of the Ccre Model class and prepare the query */
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            /* Bind the parameters */
            $stmt->bindValue(':name',$this->name,PDO::PARAM_STR);
            $stmt->bindValue(':email',$this->email,PDO::PARAM_STR);
            $stmt->bindValue(':password_hash',$password_hash,PDO::PARAM_STR);

            /* Execute the SQL statement (returns true or false) */
            return $stmt->execute();
        }
        return false;
    }

    /* METHOD: sendPasswordreset
    * @param string     : $email to reset
    * @return void      : Search for the email and pull up that User object; otherwise, the search will return false
    */
    public static function sendPasswordReset($email) {
        $user = static::findByEmail($email);

        if ($user) {
            //Start password reset process
            //...
        }
    }

    /* METHOD: validate
    *   @param void     :
    *   @return void    :   Populate the errors[] array of the calling User object as needed
    */
    public function validate() {
        /* Validate the name */
        if ($this->name == '') {
            $this->errors[] = 'Name is required.';
        }

        /* Validate the email */
        if (filter_var($this->email,FILTER_VALIDATE_EMAIL)===false) {
            $this->errors[] = 'Invalid email.';
        }

        /* Check whether the email already exists in the database */
        if (static::emailExists($this->email)) {
            $this->errors[] = 'That email is already taken.';
        }

        /* Check password length */
        if (strlen($this->password) < 6) {
            $this->errors[] = 'Your password must be at least 6 characters in length.';
        }

        /* Check for at least one letter (amongst any number of any other character, including none) */
        if (preg_match('/.*[a-z]+.*/i',$this->password) == 0) {
            $this->errors[] = 'There must be at least one letter in your password (a-z or A-Z).';
        }

        /* Check for at least one number */
        if (preg_match('/.*\d+.*/',$this->password) == 0) {
            $this->errors[] = 'There must be at least one digit in your password.';
        }
    }
}
?>