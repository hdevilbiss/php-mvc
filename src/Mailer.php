<?php

/**
 * Mailer
 * Simulate sending messages
 */
class Mailer {
    /**
     * Function: sendMessage
     * @param string    : $email
     * @param string    : $message
     * @return boolean  : True if sent
     */
    public function sendMessage($email,$message) {
        
        if (empty($email)) {
            // Replace standard PHP Exception if you have a custom Error class
            throw new Exception;
        }
        //simulate delay time
        sleep(3);

        echo "Send " . $message . " to " . $email;

        return true;
    }
}