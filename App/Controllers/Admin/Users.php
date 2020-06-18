<?php
namespace App\Controllers\Admin;
use \Core\View;

class Users extends \App\Controllers\Authenticated {
    /* Action Filter, "before" */
    protected function before() {
        //return false;
    }
    /* Action Filter, "after" */
    protected function after() {

    }

    public function indexAction() {
        //echo 'User admin index action';
        View::renderTemplate('Admin/index.html',[]);
    }
}
?>