<?php
namespace Core;

class View {

    /**
     * METHOD, renderTemplate
     * @param string : The filepath of the template
     * @param array  : Optional arguments
     * @return void  : echo the template with arguments
     */
    public static function renderTemplate(string $template,array $args = []) {
    echo static::getTemplate($template,$args);
    }

    /**
     * METHOD, getTemplate
     * @param string : The filepath of the template
     * @param array  : Optional arguments
     * @return void  : return the template with arguments for emailing
     */
    public static function getTemplate(string $template,array $args = []) {
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

    return $twig->render($template,$args);
    
    }
}
?>