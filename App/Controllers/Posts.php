<?php
namespace App\Controllers;

use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller {
    
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