<?php
namespace App\Controllers;

use \Core\View;//for rendering templates
use \App\Models\User;//for authenticating email/pw combos
use \App\Auth;//for logging in and out
use \App\Flash;//for showing success/error messages

/* Login Controller */
class Login extends \Core\Controller {
    
    /* METHOD: new
    *   @param void     :
    *   @return void    :   Render the login page
    */
    public function newAction() {
        /* Show the login screen */
        View::renderTemplate('Login/new.html');
    }

    /* METHOD: create
    *   @param void     :
    *   @return void    :   $user authenticates? Yes = Redirect, No = Render login
    */
    public function createAction() {
        // Check checkbox
        $remember_me = isset($_POST['remember_me']);

        /* Authenticate the email and password combination with a User data object method */
        $user=User::authenticate($_POST['email'],$_POST['password']);

        // Check is user is authenticated
        if ($user) {

            // Set the SESSION variable for user_id
            Auth::login($user,$remember_me);

            // Display a message
            Flash::addMessage('Login successful.');

            // Redirect to the original page OR home page
            $this->redirect(Auth::getReturnToPage());

        } else {

            // Login failed! Try again
            Flash::addMessage('Login unsuccessful; please try again.',Flash::WARNING);

            View::renderTemplate('Login/new.html',[
                'email'=>$_POST['email'],
                'remember_me'=>$remember_me
            ]);
            
        }
    }

    /* METHOD: destroy
    *   @param void     :
    *   @return void    :   Logout and redirect to home
    */
    public function destroyAction() {
        Auth::logout();
        $this->redirect('/login/show-logout-message');//this will start a new session
    }

    /* METHOD: showLogoutMessageAction
    *   @param void     :
    *   @return void    :   Show a logout flash message
    */
    public function showLogoutMessageAction() {
        // Display a confirmation message
        Flash::addMessage('Logout successful.');

        $this->redirect('/');
    }
}
?>