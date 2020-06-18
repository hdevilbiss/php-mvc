<?php
namespace App;

/* Flash Class */
class Flash {
    /* Flash CONSTANTS: Message Types */
    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';

    /* METHOD: addMessage
    *   @param string   : $message to display and the type, which defaults to 'success'; therefore, this argument is optional
    *   @return void    : Render the $message in a View
    */
    public static function addMessage($message,$type = 'success') {
        /* Ensure that an array exists in the SESSION for the message, so that it can be used to render a flash notification */
        if (! isset($_SESSION['flash_notifications'])) {
            $_SESSION['flash_notifications'] = [];
        }
        /* Append the message to an array */
        $_SESSION['flash_notifications'][] = [
            'body' => $message,
            'type' => $type
        ];
    }

    /* METHOD: getMessages
    *   @param void     :
    *   @return mixed   :   Returns an array with all the messages, or null
    */
    public static function getMessages() {
        if (isset($_SESSION['flash_notifications'])) {
            /* Store the SESSION flash notifications into a variable, unset the SESSION, and then return just the variable */            
            $messages = $_SESSION['flash_notifications'];
            unset($_SESSION['flash_notifications']);

            return $messages;
        }
    }
}
?>