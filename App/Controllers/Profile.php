<?php
namespace App\Controllers;

use \Core\View;
use App\Auth;

/* Profile Controller (Private due to inheriting from the Authenticated abstract class) */
class Profile extends Authenticated {
    //Note: The Authenticated controller is in the same namespace as Profile

    /* METHOD: index
    *   @param void     :
    *   @return void    :   Render the index template
    */
    public function indexAction() {
        View::renderTemplate('Profile/index.html');
    }
}
?>