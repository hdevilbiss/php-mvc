<?php
namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Flash;

/* Password Controller */
class Password extends \Core\Controller {
    
    /* METHOD, forgotAction
    * @param void   :
    * @return void  : Render the "forgot password" template
    */
    public function forgotAction() {
        View::renderTemplate('Password/forgot.html');
    }


    /* METHDO, getUserOrExit
    * @param string     : Reset token
    * @return mixed     : User object if found, or false
    */
    protected function getUserOrExit($token) {
        //Get the User using the token
        $user = User::findByPasswordReset($token);

        if ($user) {
            return $user;
        }

        else {
            View::renderTemplate('Password/token_expired.html');
            exit;
        }
    }


    /* METHOD, requestResetAction
    * @param void   :
    * @return void  : Send the password reset link to the supplied email from POST
    */
    public function requestResetAction() {
        User::sendPasswordReset($_POST['email']);

        Flash::addMessage('Reset request received.');

        View::renderTemplate('Password/reset_confirmation.html');
    }


    /* METHOD, resetAction
    * @param void   :
    * @return void  : User object if found, false otherwise
    */
    public function resetAction() {
        //Get the hexdex token from the route $params array
        $token = $this->route_params['token'];

        $user = $this->getUserOrExit($token);

        // If valid token, then display the View for setting a new password
        View::renderTemplate('Password/reset.html'
            ,[
                // Pass the token to the View
                'token'=>$token
            ]
        );
    }


    /* METHOD, passwordResetAction
    * @param void   :
    * @return void  : 
    */
    public function passwordResetAction() {
        // Get the token value from the hidden input on the form
        $token = $_POST['token'];

        $user = $this->getUserOrExit($token);

        // Validate password from the form
        if ($user->resetPassword($_POST['password'])) {
            // Show Password valid template
            View::renderTemplate('Password/reset_success.html');
        }

        else {
            //Password invalid; redisplay form
            View::renderTemplate('Password/reset.html'
                ,[
                    'token' => $token,
                    'user' => $user
                ]
            );
        }
    }
}
?>