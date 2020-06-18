<?php
namespace Core;

class View {

public static function render($view,$args=[]) {
    // Convert the associative array into individual variable(s)
    extract($args,EXTR_SKIP);

    $file = "../App/Views/$view";//Save the parameter's expected file path to a variable

    if(\is_readable($file)){
        require $file;
    }else{
        //echo "$file not found";
        throw new \Exception("$file not found.");
    }
}//close function, "render

public static function renderTemplate(string $template,array $args = []) {
    static $twig = null;

    if ($twig === null) {
        $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
        $twig = new \Twig\Environment($loader);
        $twig->addGlobal('session',$_SESSION);
        
        /* Logged In User Object */
        $twig->addGlobal('current_user',\App\Auth::getUser());

        /* Get all the flash messages */
        $twig->addGlobal('flash_messages', \App\Flash::getMessages());
    }
    echo $twig->render($template,$args);
}//close function, "renderTemplate"
}
?>