<?php
namespace App\Models;

use PDO;
use \App\Token;
use \App\Mail;
use \Core\View;

/* User Model */
class User extends \Core\Model {
    
    /**
     * @var array   : Error messages
     */
    public $errors = [];


    /**
     * MAGIC METHOD: __construct
     * @param array    :   $data from $_POST (optional)
     * @return void    :   Create a User object from $_POST array
     */
    public function __construct($data = []) {
        /* Loop through the $_POST array */
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }


    /**
     * METHOD: authenticate
     * @param string   :   $email from login form
     * @param string   :   $password from login form
     * @return mixed   :   If Password matches its hash, then return the User object. Otherwise, false.
     */
    public static function authenticate($email,$password) {

        // Search the user in the database using a custom, static User method
        $user = static::findByEmail($email);

        /* Restrict login to only active users (is_active) */
        if ($user && $user->is_active) {

            // Verify password
            if (password_verify($password,$user->user_password_hash)) {

                //If the provided password hash matches password hash from the database, then return the User object
                return $user;

            }
        }
        return false;
    }


    /** 
     * METHOD: emailExists
     * @param string   :   $email from login form
     * @param string   :   Optional ignore_id (NULL for signup, user_id for reset or edit)
     *
     * @return boolean :   Search for $email in the DB
     */
    public static function emailExists($email,$ignore_id = null) {

        //returns true if the static User method returns a User object
        $user = static::findByEmail($email);

        if ($user) {
            // Check whether the user_id exists, or whether it is null
            if ($user->user_id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * METHOD: findByActivationToken
     * @param string     : $token_value from the URL
     * @return void      :
     */
    public static function findByActivationToken($token_value) {
        /* Create a new Token from the value in the activation link, and then hash it */
        $token = new Token($token_value);
        $hashed_token = $token->getHash();

        /* SQL to search for the unique activation hash */
        $sql = "UPDATE users
        SET is_active = 1,
            activation_token_hash = NULL
        WHERE activation_token_hash = :hashed_token";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        /* Bind Values */
        $stmt->bindValue(':hashed_token',$hashed_token,PDO::PARAM_STR);//VARCHAR(64)

        $stmt->execute();
    }


    /**
     * METHOD: findByEmail
     * @param string   :   $email from a user input
     * @return mixed   :   User object instance or false
     */
    public static function findByEmail($email) {

        $sql = 'SELECT * FROM users WHERE user_email = :email';

        //Make database connection using a static method of the core Model
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email',$email,PDO::PARAM_STR);

        // Change the PDO fetch mode to get a class instance instead of an array
        $stmt->setFetchMode(PDO::FETCH_CLASS,get_called_class());

        //FYI: get_called_class returns the name of the class that the static method (findByEmail) is called in

        $stmt->execute();
        return $stmt->fetch();
        //fetch returns either a User object, or false if no record was found
    }


    /**
     * METHOD: findByID
     * @param $string  :   The $user user_id
     * @return mixed   :   User object if found; otherwise false
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


    /**
     * METHOD, findByPasswordReset
     * @param string     : Password reset hexdex token
     * @return mixed     : User object or false
     */
    public static function findByPasswordReset($token) {
        //Create a token object and hash it
        $token = new Token($token);
        $hashed_token = $token->getHash();

        //Prepare the SQL query
        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :hashed_token';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Bind the hashed token from the URL to the SQL param
        $stmt->bindValue(':hashed_token',$hashed_token,PDO::PARAM_STR);

        //Fetch the $user record as a class if a matching hashed token is found in the database
        $stmt->setFetchMode(PDO::FETCH_CLASS,get_called_class());
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            //Is the token expired?
            if (strtotime($user->password_reset_expiry) > time()) {
                //Not Expired, return the user object
                return $user;
            }
        }
    }


    /**
     * METHOD: rememberLogin
     * @param void         :
     * @return boolean     :   Save a new row in the rememberedLogins table
     */
    public function rememberLogin() {
        //Generate a new token and hash
        $token = new Token();
        $hashed_token = $token->getHash();

        //Save the value and expiration date (to local User model?)
        $this->remember_token = $token->getValue();
        $this->expiry_timestamp = time() + 60 * 60 *24 * 30;//30 days

        // Prepare DB query and connection
        $sql = 'INSERT INTO rememberedlogins (token_hash,user_id,expires_at) 
        VALUES (:token_hash,:user_id,:expires_at)';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Bind values
        $stmt->bindValue(':token_hash',$hashed_token,PDO::PARAM_STR);
        $stmt->bindValue(':user_id',$this->user_id,PDO::PARAM_INT);
        $stmt->bindValue(':expires_at',date('Y-m-d H:i:s',$this->expiry_timestamp),PDO::PARAM_STR);//convert datetime to string

        return $stmt->execute();
    }


    /**
     * METHOD, resetPassword
     * @param string     : The new password
     * @return boolean   : True = Successful Update
     */
    public function resetPassword($password) {
        $this->password = $password;

        $this->validate();

        //True = No errors
        if (empty($this->errors)) {
            //Regen the password hash
            $password_hash = password_hash($this->password,PASSWORD_DEFAULT);

            //Prepare the insertion query for the new password: set new hash, nullify the reset token and expiry
            $sql = "UPDATE users
                    SET user_password_hash = :password_hash,
                    password_reset_hash = NULL,
                    password_reset_expiry = NULL
                    WHERE user_id = :user_id";

            //Get DB connection, prepare the SQL statement
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            //bind the parameters (user_id and pw hash)
            $stmt->bindValue(':user_id',$this->user_id,PDO::PARAM_INT);
            $stmt->bindValue(':password_hash',$password_hash,PDO::PARAM_STR);

            //Execute
            return $stmt->execute();
        }
        //Validation fails
        return false;
    }


    /**
     * METHOD: save
     * @param void     : 
     * @return boolean :   Save form data to the database
     */
    public function save() {
        /* Validate the inputs */
        $this->validate();

        /* Generate a new activation hash */
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->activation_token_hash = $token->getValue();

        /* Check for error messages before proceeding to database action */
        if (empty($this->errors)) {
            /* Salt that password */
            $password_hash = password_hash($this->password,PASSWORD_DEFAULT);
            
            /* Parameter-ized SQL query */
            $sql = 'INSERT INTO users 
                    (user_name,user_email,user_password_hash,activation_token_hash) 
                    VALUES 
                    (:user_name,:user_email,:user_password_hash,:activation_token_hash)';

            /* Make the database connection using the static method of the Core Model class and prepare the query */
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            /* Bind the parameters */
            $stmt->bindValue(':user_name',$this->user_name,PDO::PARAM_STR);
            $stmt->bindValue(':user_email',$this->user_email,PDO::PARAM_STR);
            $stmt->bindValue(':user_password_hash',$password_hash,PDO::PARAM_STR);
            $stmt->bindValue(':activation_token_hash',$hashed_token,PDO::PARAM_STR);

            /* Execute the SQL statement (returns true or false) */
            return $stmt->execute();
        }
        return false;
    }


    /**
     * METHOD, sendActivationEmail
     * @param void       :
     * @return void      : Send an activation token to the user
     */
    public function sendActivationEmail() {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token_hash;

        $text = View::getTemplate('Signup/activation_email.txt',[
            'url' => $url
            ]);
        
        $html = View::getTemplate('Signup/activation_email.html',[
            'url'=>$url
        ]);

        Mail::send(
            $this->user_email,//$email
            'Please activate your account',//$subject
            $text,//$text
            $html//$html
        );
    }


    /**
     * METHOD: sendPasswordreset
     * @param string     : $email to reset
     * @return void      : Search for the email and pull up that User object; otherwise, the search will return false
     */
    public static function sendPasswordReset($email) {
        $user = static::findByEmail($email);

        if ($user) {
            if ($user->startPasswordReset()) {
                // Send email to the $user
                $user->sendPasswordResetEmail();
            }
        }
    }


    /**
     * METHOD, sendPasswordResetEmail
     * @param void       :
     * @return void      : Send an email to the $user with reset instructions
     */
    protected function sendPasswordResetEmail() {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_hash;

        $text = View::getTemplate('Password/reset_email.txt',[
            'url' => $url
            ]);
        
        $html = View::getTemplate('Password/reset_email.html',[
            'url'=>$url
        ]);

        Mail::send(
            $this->user_email,//$email
            'Password reset',//$subject
            $text,//$text
            $html//$html
        );
    }


    /**
     * METHOD, startPasswordReset
     * @param void       :
     * @return boolean   : Does the statement execute?
     */
    protected function startPasswordReset() {
        //Generate a new Token hash and expiry date
        $token = new Token();
        $hashed_token = $token->getHash();

        //Save the token value (to be emailed to the user)
        $this->password_reset_hash = $token->getValue();
        $expiry_timestamp = time() + 60 * 60 * 2;//2 hours in future

        //Update the user record
        $sql = 'UPDATE users
                SET password_reset_hash = :hashed_token, password_reset_expiry = :expiry_timestamp
                WHERE user_id=:id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Bind parameters
        $stmt->bindValue(":hashed_token",$hashed_token,PDO::PARAM_STR);
        $stmt->bindValue(":expiry_timestamp",date('Y:m:d H:i:s',$expiry_timestamp),PDO::PARAM_STR);
        $stmt->bindValue(":id",$this->user_id,PDO::PARAM_INT);//id comes from the User object

        return $stmt->execute();
    }

    /**
     * METHOD: updateUserProfile
     * @param array     : $data Data from the Profile/edit <form>
     * @return boolean  : True if valid update, false otherwise
     */
    public function updateUserProfile($data) {

        // Get the form values and assign to the User object
        $this->user_name = $data['user_name'];
        $this->user_email = $data['user_email'];
        
        // Only validate/update the password if it was supplied
        if ($data['password'] != '') {
            $this->password = $data['password'];
        }

        $this->validate();

        if ( empty($this->errors) ) {

            // No errors here, so start the query to Update the User record
            $sql = "UPDATE users
                SET user_name = :user_name,
                    user_email = :user_email";
            
            // Check whether the password was set by POST
            if ( isset($this->password) ) {
                
                // If set, then add the password to the SQL query
                $sql .= ", user_password_hash = :password_hash";

            }

            // Finish the SQL statement   
            $sql .= "\nWHERE user_id = :user_id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            // Bind values
            $stmt->bindValue(':user_name',$this->user_name,PDO::PARAM_STR);
            $stmt->bindValue(':user_email',$this->user_email,PDO::PARAM_STR);
            $stmt->bindValue(':user_id',$this->user_id,PDO::PARAM_INT);

            // Only hash and bind the password hash if set
            if ( isset($this->password) ) {
                
                $password_hash = password_hash($this->password,PASSWORD_DEFAULT);

                $stmt->bindValue(':password_hash',$password_hash,PDO::PARAM_STR);

            }
    
            return $stmt->execute();
        }

        // If you get here, then there was a validation error
        return false;
    }

    /**
     * METHOD: validate
     * @param void     :
     * @return void    :   Populate the errors[] array of the calling User object as needed
     */
    public function validate() {
        /* Validate the name */
        if ($this->user_name == '') {
            $this->errors[] = 'Name is required.';
        }

        /* Validate the email */
        if (filter_var($this->user_email,FILTER_VALIDATE_EMAIL)===false) {
            $this->errors[] = 'Invalid email.';
        }

        /* Check whether the email already exists in the database; the second argument allows us to use the validate function for reset email password validation; the id will be null if this function call is trying to enter a new record */
        if (static::emailExists($this->user_email, $this->user_id ?? null )) {
            $this->errors[] = 'That email is already taken.';
        }

        // Password validation (only if set)
        if (isset($this->password)) {
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
}
?>