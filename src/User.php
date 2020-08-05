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
     * METHOD: getFullName
     * @param void      :
     * @return string   : The user's full name
     */
    public function getFullName() {
        return trim("$this->first_name $this->last_name");
    }
}