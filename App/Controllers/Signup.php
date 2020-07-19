<?php
namespace App\Controllers;
use \Core\View;
use \App\Models\User; 

/* Signup Controller */
class Signup extends \Core\Controller {

    /* METHOD: createAction
    * @param void   :  
    * @return void  : Create a new User record in the users table
    */
    public function createAction() {

        /* Create a new User from the POST array */
        $user = new User($_POST);

        /* User/save action will validate inputs and INSERT INTO database */
        if ($user->save()) {

            /* Redirect to Signup/success action */
            $this->redirect('/signup/success');

        }
        
        /* Invalid inputs */
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