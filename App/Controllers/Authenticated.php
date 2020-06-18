<?php
namespace App\Controllers;

abstract class Authenticated extends \Core\Controller {
    /* ACTION FILTER, before
    *   @param void     :
    *   @return void    :   Runs before each action method for controllers that inherit from this abstract class; use this to authenticate
    */
    protected function before() {
        $this->requireLogin();
    }
}

?>