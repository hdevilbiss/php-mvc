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
    }
    public function indexAction() {
       
        $posts = Post::getAll();
        
        View::renderTemplate('Posts/index.html',[
            'posts'=>$posts
        ]);
    }
}
?>