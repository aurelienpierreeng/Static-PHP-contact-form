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

  // We use HTML body
  $mail->isHTML(true);

  // Typical email values
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // Define plain-text alternative
  $mail->AltBody = $message;

  // $message is unformatted text.
  // Relace line breaks by HTML paragraphs for proper display.
  $paragraphs = "";
  foreach (explode("\n", $message) as $line) {
      if (trim($line)) {
          $paragraphs .= '<p>' . $line . '</p>';
      }
  }

  // Variables prefixed with $_ will break in the $content string litteral.
  // We need to unpack them before to regular variables.
  $origin = $_SERVER['HTTP_REFERER'];
  $ip = $_POST['ip'];


  $content = <<<EOD
  <!DOCTYPE html>
  <html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <style>
        * {font-size: 1em}
      </style>
    </head>
    <body style="background: #ddd;">
      <table cellspacing="0" cellpadding="20" style="max-width: 800px; margin: 20px auto; background: white;">
      <tr style="background: #111; color: white;">
        <td>
          <h1>New contact form</h1>
        </td>
      </tr>
      <tr>
        <td>$paragraphs</td>
      </tr>
      <tr style="background: #f2f2f2;">
        <td>Message sent from $origin by $ip.</td>
      </tr>
      </table>
    </body>
  </html>
  EOD;

  return $content;
 }
