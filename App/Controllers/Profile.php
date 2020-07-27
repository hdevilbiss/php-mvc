<?php
namespace App\Controllers;
//Note: The Authenticated controller is in the same namespace as Profile

use \Core\View;
use \App\Auth;
use \App\Flash;

/* Profile Controller (Private due to inheriting from the Authenticated abstract class) */
class Profile extends Authenticated {

    /**
     * METHOD: index
     * @param void     :
     * @return void    : Render the index template
     */
    public function indexAction() {
        View::renderTemplate('Profile/index.html',
            [
                'user' => Auth::getUser()
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
            'user' => Auth::getUser()
        ]);
    }

    /**
     * METHOD: updateAction
     * @param void
     * @return void : Update the user record in the database
     */
    public function updateAction() {
        $user = Auth::getUser();

        if ($user->updateUserProfile($_POST)) {
            
            // UPDATE query was successful
            Flash::addMessage('Changes saved');
            $this->redirect('/profile/index');
        }

        else {

            // Validation Error - Redisplay the form
            View::renderTemplate('Profile/edit.html',
            [
                'user' => $user
            ]);
        }
    }
}
?>