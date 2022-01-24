<?php
include('phpmailer.php');
class Mail extends PhpMailer
{
    public $From     = 'admin@ignitestore.com.pk';
    public $FromName = IGNITESTORE;
    public $Host     = 'mail.ignitestore.com.pk';
    public $Mailer   = 'smtp';
    public $SMTPAuth = true;
    public $Username = 'admin@ignitestore.com.pk';
    public $Password = ',y=z&94_$FgH';
    public $SMTPSecure = 'tls';
    public $WordWrap = 75;

    public function subject($subject)
    {
        $this->Subject = $subject;
    }

    public function body($body)
    {
        $this->Body = $body;
    }

    public function send()
    {
        $this->AltBody = strip_tags(stripslashes($this->Body))."\n\n";
        $this->AltBody = str_replace("&nbsp;", "\n\n", $this->AltBody);
        return parent::send();
    }
}
