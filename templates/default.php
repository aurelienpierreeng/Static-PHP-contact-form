<?php
/**
 * Email template(s).
 *
 * Params:
 *    $email: the `PHPMailer()` instance (email object) being written to.
 *            Use it for example to set `$mail->isHTML(true);`
 *    $config: your configuration dictionnary. Access anything you like from there.
 *
 * Note :
 *    The POST parameters are a global array variable, you can access everything from
 *    template functions through `$_POST`. Same with `$_SERVER`.
 *
 * Return:
 *    The email body.
 */

 function email_body($mail, $config)
 {
  /* Mix and match $_POST and $_SERVER params to create the email body */

  // We use plain text body
  $mail->isHTML(false);

  // Typical email values
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  $message .= "\r\n\r\n---";
  $message .= "\r\n" . 'Sent by ' . $_POST['ip'] . ' at ' . $_POST['localip'] . ' from `' . $_POST['utm']. '` through `' . $_SERVER['HTTP_REFERER'] . '`';
  $message .= "\r\n" . 'Country: ' . $_POST['country'] . ' / ISP: ' . $_POST['isp'] . ' / OS: ' . $_POST['os'] . ' / Browser: ' . $_POST['browser'] . ' / Language: ' . $_POST['lang'];

  return $message;
 }
