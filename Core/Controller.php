<?php
namespace Core;
use \App\Auth;
use \App\Flash;

/*
BASE CONTROLLER 
************************************
Each Controller inherits from this class.

An "abstract" class means that no instances of the class will be created directly (inheritance only)
*/
abstract class Controller {
    
    /* Store the route parameters */
    protected $route_params = [];//initialize array

    /* MAGIC METHOD: __call
    *   This magic method gets executed if the method cannot be found, or if it is private.
    */
    public function __call($name,$args) {
        $method = $name . 'Action';
        
        if (method_exists($this,$method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this,$method],$args);
                $this->after();
            }
        } else {
            //echo "Method $method was not found in controller:<b> " . get_class($this) . '</b>.';

            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /* MAGIC METHOD: __construct
    *   @return void
    *   This magic method is a class ("object") constructor.
    */
    public function __construct($route_params) {
        $this->route_params = $route_params;
    }

    /* METHOD: before
    *   @return boolean
    *   This action filter can be used to apply tests before executing a method;
    *   For example, the before filter can return FALSE if the client is NOT authenticated.
    */
    protected function before() {
        //return false;
    }

    /* METHOD: after
    *   @return void
    *   This action filter can be used to perform actions after executing a method.
    */
    protected function after() {

    }

    /* METHOD, redirect
    *   @param string   : Where to redirect (e.g., '/' for home)
    *   @return void    :
    */
    public function redirect($url) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url,true,303);
        exit;
    }

    /* METHOD, requireLogin
    *   @param void     :
    *   @return void    :   If the Auth static method does not evaluate to true, then redirect the client to login
    */
    public function requireLogin() {
        
        /* Check if user is logged in */
        if (!Auth::getUser()) {
            /* Flash Message for user feedback */
            Flash::addMessage('Please login to access that page.',Flash::INFO);

            /* Remember the original requested URI */
            Auth::rememberRequestedPage();

            /* Redirect to login */
            $this->redirect('/login');
        }
    }
}
?>