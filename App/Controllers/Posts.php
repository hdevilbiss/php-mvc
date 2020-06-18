<?php
namespace App\Controllers;

use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller {
    /*
    $controller = new Posts()
    $controller->index()
    Method "index" is not found, and __call is defined for the Posts controller; therefore, $controller->__call("index","") happens instead
    
    public function __call($name,$args) {
        echo 'Hello from the __call magic method in the Posts controller!';
        echo 'This code runs before call_user_func_array.';
        call_user_func_array([$this,"$nameAction"],$args);
        echo 'This code runs after call_user_func_array.';
    }*/
    
    public function addNewAction() {
        echo 'Hello from the addNew action inside the Posts controller!';
    }
    public function editAction() {
        echo 'Hello from the edit action in the Posts controller!';
        echo '<p>Route parameters: <pre>' . htmlspecialchars(print_r($this->route_params,true)) . '</pre></p>';
        $id = $this->route_params['id'];
        echo $id;
    }
    public function indexAction() {
        echo '<p>Query string parameters:<pre>' . htmlspecialchars(print_r($_GET,true)) . '</pre></p>';
        
        $posts = Post::getAll();
        
        View::renderTemplate('Posts/index.html',[
            'posts'=>$posts
        ]);
    }
}
?>