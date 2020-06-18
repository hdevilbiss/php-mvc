<?php
namespace App\Controllers;
use \Core\View;

/* HOME CONTROLLER */
class Home extends \Core\Controller {
/* NOTES
"class Home extends \Core\Controller" means the Home class inherits the functionality of the core controller class.
*/
    /* Action Filter, "before" */
    protected function before() {
        //echo '(before)';
        //return false;//won't execute method if this is enabled
    }

    /* Action Filter, "after" */
    protected function after() {
       // echo '(after)';
    }
    
    /* METHOD: SHOW THE INDEX PAGE:
    *   @return void
    */
    public function indexAction() {
        /* Test Mailgun */
        \App\Mail::send('hannah.031425@gmail.com','Test','This is a test','<h1>This is a test</h1>');
        
        /* Render Template Using Twig */
        View::renderTemplate('Home/index.html',[]);
    }//close function, "index"
}
?>