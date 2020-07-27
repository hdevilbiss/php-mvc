<?php
namespace App\Controllers;
//Note: The Authenticated controller is in the same namespace as Profile

use \Core\View;
use \App\Auth;
use \App\Flash;

/* Profile Controller (Private due to inheriting from the Authenticated abstract class) */
class Profile extends Authenticated {


    /**
     * ACTION FILTER: before
     * @param void
     * @return void     : Call the parent (Authenticated) before action filter and then Get the User using the Auth class
     */
    protected function before() {
        parent::before();
        $this->user = Auth::getUser();
    }

    /**
     * METHOD: index
     * @param void     :
     * @return void    : Render the index template
     */
    public function indexAction() {
        View::renderTemplate('Profile/index.html',
            [
                'user' => $this->user
            ]);
    }


    /**
     * METHOD: editAction
     * @param void   :
     * @return void  :
     */
    public function editAction() {
        View::renderTemplate('Profile/edit.html',
        [
            'user' => $this->user
        ]);
    }

    /**
     * METHOD: updateAction
     * @param void
     * @return void : Update the user record in the database
     */
    public function updateAction() {

        if ($this->user->updateUserProfile($_POST)) {
            
            // UPDATE query was successful
            Flash::addMessage('Changes saved');
            $this->redirect('/profile/index');
        }

        else {

            // Validation Error - Redisplay the form
            View::renderTemplate('Profile/edit.html',
            [
                'user' => $this->user
            ]);
        }
    }
}
?>