<?php
namespace App;
use \App\Models\User;
use \App\Models\RememberedLogin;

/* Authentication Class */
class Auth {

    /* METHOD: forgetLogin
    * @param void   :
    * @return void  : If present, forget the remembered login
    */
    protected static function forgetLogin() {

        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {

            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login) {
                //Delete database record where the token hash matches
                $remembered_login->delete();

                //Expire the cookie
                setcookie('remember_me','',time()-3600);
            }
        }
    }

    /* METHOD, getReturnToPage
    *   @param void     :
    *   @return string  :   Return either the SESSION-saved URI or '/', indicating home
    */
    public static function getReturnToPage() {
        return $_SESSION['return_to'] ?? '/';
    }


    /* METHOD: getUser
    *   @param void     :
    *   @return mixed   :   Get User model from SESSION user_id or null if not logged in
    */
    public static function getUser() {
        if (isset($_SESSION['user_id'])) {

            return User::findByID($_SESSION['user_id']);

        } else {

            return static::loginFromRememberedCookie();

        }
    }

    
    /* METHOD, login
    *   @param mixed    :   $user object
    *   @return void    :   Generate new session ID and set SESSION user_id
    */
    public static function login($user,$remember_me) {
        //Generate a new SESSION ID to avoid Session Fixation attacks
        session_regenerate_id(true);//true deletes old

        $_SESSION['user_id'] = $user->user_id;

        if ($remember_me) {

            if ($user->rememberLogin()) {

                //Set the cookie
                setcookie('remember_me',$user->remember_token,$user->expiry_timestamp,'/');//'/' indicates the path (root)

            }
        }

        // Create SESSION info
        $_SESSION['user_id'] = $user->user_id;
    }


    /* METHOD, loginFromRememberedCookie
    *   @param void     :
    *   @return mixed   :   User model or null
    */
    protected static function loginFromRememberedCookie() {

        $cookie = $_COOKIE['remember_me'] ?? false;

        if ($cookie) {

            $remembered_login = RememberedLogin::findByToken($cookie);

            if ($remembered_login && ! $remembered_login->hasExpired()) {

                $user = $remembered_login->getUser();
                static::login($user,false);
                return $user;

            }
        }
    }


    /* METHOD, logout
    *   @param void     :   
    *   @return void    :   Destroy cookies and session
    */
    public static function logout() {
        //Unset session variable
        $_SESSION = array();

        // Delete cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            // Expire the SESSION ID cookie
            setcookie(
                session_name(),
                '',
                time()-42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Destroy session
        session_destroy();

        //Delete the remembered login cookie and DB record
        static::forgetLogin();
    }


    /* METHOD, rememberRequestedPage
    *   @param void     :
    *   @return void    :   Save the requested URI to a SESSION variable on the server
    */
    public static function rememberRequestedPage() {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }
}
?>