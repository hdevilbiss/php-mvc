<?php
namespace App\Controllers;
//Note: The Authenticated controller is in the same namespace as Profile

use \Core\View;
use App\Auth;

/* Profile Controller (Private due to inheriting from the Authenticated abstract class) */
class Profile extends Authenticated {

    /* METHOD: index
    * @param void     :
    * @return void    :   Render the index template
    */
    public function indexAction() {
        View::renderTemplate('Profile/index.html');
    }
}
?>