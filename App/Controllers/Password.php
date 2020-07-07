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

        //Get the User using the token
        $user = User::findByPasswordReset($token);

        // If valid token, then display the View for setting a new password
        if ($user) {
            View::renderTemplate('Password/reset.html');
        }
        
        // If invalid token...
        else {
            echo "Password reset token invalid";
        }
    }
}
?>