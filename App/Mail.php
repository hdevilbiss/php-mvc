<?php
namespace App;

use Mailgun\Mailgun;

/* Mail class */
class Mail {

    /* METHOD, send
    * @param string     : $to = recipient
    * @param string     : $subject
    * @param string     : $text (core msg)
    * @param string     : $html HTML content of the message
    * @return mixed     :
    */
    public static function send($to,$subject,$text,$html) {
        //$mailgun = new Mailgun(Config::MAILGUN_API_KEY);//private API key

        $mailgun = Mailgun::create(Config::MAILGUN_API_KEY);//private API key
        $domain = Config::MAILGUN_DOMAIN;

        $mailgun->domains()->verify($domain);

        //Compose and send message
        $mailgun->messages()->send($domain,array(
            'from'=>'bob@example.com',
            'to'=>$to,
            'subject'=>$subject,
            'text'=>$text,
            'html'=>$html
        ));
    }
}
?>