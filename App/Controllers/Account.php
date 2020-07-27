<?php
namespace App\Controllers;

use \App\Models\User;

/* Account Controller */
class Account extends \Core\Controller {

    /**
     * METHOD: validateEmail
    * @param void
    * @return void  : Send AJAX Request to Determine Whether Email is Available
    */
    public function validateEmailAction() {
        $is_valid = ! User::emailExists(
            $_GET['user_email'],
            $_GET['ignore_id'] ?? null
        );

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}
?>