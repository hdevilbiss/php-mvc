<?php
namespace App\Controllers;

use \Core\View;
use App\Auth;

/* Goals Controller (inheriting from the Authenticated abstract class) */
class Goals extends Authenticated {

    
    /* METHOD: index
    *   @param void     :
    *   @return void    :   Render the index template
    */
    public function indexAction() {
        View::renderTemplate('Goals/index.html');
    }
}
?>