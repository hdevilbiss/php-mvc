<?php
/**
 * User class
 */
class User {
    /**
     * @var string
     */
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var email address
     */
    public $email;

    /**
     * Mailer Object for dependency injection
     * @var Mailer
     */
    protected $mailer;

    /**
     * METHOD: getFullName
     * @param void      :
     * @return string   : The user's full name
     */
    public function getFullName() {
        return trim("$this->first_name $this->last_name");
    }

    /**
     * Method: notify
     * @param string    : $message
     * @return boolean  : true if sent
     */
    public function notify($message) {
        return $this->mailer->sendMessage($this->email,$message);
    }

    /**
     * Method: setMailer
     * @param Mailer    : The Mailer object
     */
    public function setMailer(Mailer $mailer) {
        $this->mailer = $mailer;
    }
}