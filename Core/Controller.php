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
    
    /**
     * @var array   : Store the route parameters
     * */
    protected $route_params = [];//initialize array

    /**
     * MAGIC METHOD: __call
     * @param string    : $name, the name of the method
     * @param array     : $args
     * This magic method gets executed if the method cannot be found, or if it is private.
     */
    public function __call($name,$args) {
        $method = $name . 'Action';
        
        if (method_exists($this,$method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this,$method],$args);
                $this->after();
            }
        } else {

            throw new \Exception("Method $method not found in controller " . get_class($this));

        }
    }

    /**
     * MAGIC METHOD: __construct
     * @param string    : The URL route parameters
     * @return void     : This magic method is a class ("object") constructor
    */
    public function __construct($route_params) {
        $this->route_params = $route_params;
    }

    /**
     * ACTION FILTER: before
     * @param void      :
     * @return boolean  : This action filter can be used to apply tests before executing a method 
     */
    protected function before() {
        //there's nothing here
    }

    /**
     * ACTION FILTER: after
     * @param void  :
     * @return void  : This action filter can be used to perform actions after executing a method.
     */
    protected function after() {
        //there's nothing here
    }

    /**
     * METHOD, redirect
     * @param string   : $url where to redirect (e.g., '/' for home)
     * @return void    : Set header with HTTP status code 303
     */
    public function redirect($url) {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url,true,303);
        exit;
    }

    /**
     * METHOD, requireLogin
     * @param void     :
     * @return void    : If the Auth static method does not evaluate to true, then redirect the client to login
     */
    public function requireLogin() {
        
        /* Check if user is logged in */
        if ( ! Auth::getUser() ) {
            
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