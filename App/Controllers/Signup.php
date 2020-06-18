<?php
namespace App\Controllers;
use \Core\View;
use \App\Models\User; 

/* Signup Controller */
class Signup extends \Core\Controller {

    /* Method, "create"
    *   @return void
    *   Action for dealing with signup form data
    */
    public function createAction() {
        /* Create a new User object with the variables from the superglobal POST array */
        $user = new User($_POST);

        /* Validate User Inputs and Execute DB Query, if applicable */
        if ($user->save()) {
            /* Redirect to the success action */
            $this->redirect('/signup/success');
        } else {
            /* Display the error messages from input validation */
            //var_dump($user->errors);
            View::renderTemplate('Signup/new.html',[
                'user' => $user
            ]);
        }   
    }//close function, "create"

    /* Method, "new"
    *   @return void
    *   Show the signup template
    */
    public function newAction() {
        View::renderTemplate('Signup/new.html');
    }//close function, "new"

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