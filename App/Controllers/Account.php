<?php
namespace App\Controllers;

use \App\Models\User;

/* Account Controller */
class Account extends \Core\Controller {
    /* Send AJAX Request to Determine Whether Email is Available
    *   @param void @return void
    */
    public function validateEmailAction() {
        $is_valid = ! User::emailExists($_GET['email']);

        header('Content-Type: application/json');
        echo json_encode($is_valid);
    }
}
?>