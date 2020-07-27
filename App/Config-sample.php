<?php
namespace App;

/*
*   APPLICATION CONFIGURATION
*/

class Config {
    /* localhost Database Constants */
    const DB_HOST = "%%HOST%%";//e.g. localhost
    const DB_USER = "%%USER%%";
    const DB_PW = "%%PASSWORD%%";
    const DB_NAME = "%%DATABASE%%";

    const SHOW_ERRORS = true;//true = dev, false = live

    const HASH_KEY = "%%GENERATE_ME_32_HEXDEX%%";

    const MAILGUN_API_KEY = "%%GET_YOUR_OWN%%";

    const MAILGUN_DOMAIN = "%%GET_YOUR_OWN%%.mailgun.org";

    const MAILGUN_TEST_EMAIL = '%%GET_YOUR_OWN%%@gmail.com';

    // Are we just testing Mailgun at the moment? true=yes
    const MAILGUN_TEST_STATUS = true;
}
?>