<?php
namespace App\Controllers;
use \Core\View;
use \App\Models\User; 

/* Signup Controller */
class Signup extends \Core\Controller {

    /* METHOD: activateAction
    * @param void   :
    * @return void  :
    */
    public function activateAction() {
        /* Get the activation token from the route's parameters array and Try to find it in the users table */
        $user = User::findByActivationToken($this->route_params['token']);

        $this->redirect('/signup/activation-complete');
        
    }


    /* METHOD: activationCompleteAction
    * @param void   :
    * @return void  : Render the Signup activation success view
    */
    public function activationCompleteAction() {
        View::renderTemplate('Signup/activated.html');
    }



    /* METHOD: createAction
    * @param void   :  
    * @return void  : Create a new User record in the users table
    */
    public function createAction() {

        /* Create a new User from the POST array */
        $user = new User($_POST);

        /* User/save action will validate inputs and INSERT INTO database */
        if ($user->save()) {

            /* Send activation email */
            $user->sendActivationEmail();

            /* Redirect to Signup/success action */
            $this->redirect('/signup/success');

        }
        
        /* Ope, invalid inputs */
        else {

            /* Redisplay the signup form */
            View::renderTemplate('Signup/new.html',[
                'user' => $user
            ]);

        }   
    }


    /* Method, "new"
    *   @return void
    *   Show the signup template
    */
    public function newAction() {
        View::renderTemplate('Signup/new.html');
    }


    /* Method, "success"
    *   @return void
    *   Display the success page
    */
    public function successAction() {
        /* Show success page */
        View::renderTemplate('Signup/success.html');
    }
}
?>