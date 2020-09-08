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
        //simulate delay time
        sleep(3);

        echo "Send " . $message . " to " . $email;

        return true;
    }
}