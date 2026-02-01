<?php
/**
 * PHP Email Form - Simple contact form handler
 * Compatible with BootstrapMade validate.js
 */

class PHP_Email_Form {
  public $to = '';
  public $from_name = '';
  public $from_email = '';
  public $subject = '';
  public $ajax = false;
  public $smtp = array();
  private $message_parts = array();
  private $lang = 'en';
  private static $errors_fr = array(
    'Recipient email is required.' => 'L\'adresse email du destinataire est requise.',
    'Valid sender email is required.' => 'Une adresse email d\'expéditeur valide est requise.',
    'Failed to send email. Please check your server mail configuration.' => 'Échec de l\'envoi. Vérifiez la configuration mail de votre serveur.',
    'SMTP configuration is incomplete.' => 'La configuration SMTP est incomplète.',
    'Failed to send email.' => 'Échec de l\'envoi de l\'email.'
  );

  public function add_message($content, $label, $max_length = 0) {
    $content = strip_tags($content);
    if ($max_length > 0 && strlen($content) > $max_length) {
      $content = substr($content, 0, $max_length);
    }
    $this->message_parts[] = array('label' => $label, 'content' => $content);
  }

  public function send($lang = 'en') {
    $this->lang = $lang;
    if (empty($this->to)) {
      return $this->t('Recipient email is required.');
    }
    if (empty($this->from_email) || !filter_var($this->from_email, FILTER_VALIDATE_EMAIL)) {
      return $this->t('Valid sender email is required.');
    }

    $message = '';
    foreach ($this->message_parts as $part) {
      $message .= $part['label'] . ': ' . $part['content'] . "\n\n";
    }

    $headers = array(
      'From: ' . $this->from_name . ' <' . $this->from_email . '>',
      'Reply-To: ' . $this->from_email,
      'X-Mailer: PHP/' . phpversion(),
      'Content-Type: text/plain; charset=UTF-8'
    );

    $subject = $this->subject ?: ($lang === 'fr' ? 'Message du formulaire de contact' : 'Contact Form Submission');

    if (!empty($this->smtp)) {
      return $this->send_smtp($subject, $message, $headers);
    }

    $sent = @mail($this->to, $subject, $message, implode("\r\n", $headers));
    return $sent ? 'OK' : $this->t('Failed to send email. Please check your server mail configuration.');
  }

  private function t($msg) {
    if ($this->lang === 'fr' && isset(self::$errors_fr[$msg])) {
      return self::$errors_fr[$msg];
    }
    return $msg;
  }

  private function send_smtp($subject, $message, $headers) {
    if (empty($this->smtp['host']) || empty($this->smtp['username']) || empty($this->smtp['password'])) {
      return $this->t('SMTP configuration is incomplete.');
    }
    $sent = @mail($this->to, $subject, $message, implode("\r\n", $headers));
    return $sent ? 'OK' : $this->t('Failed to send email.');
  }
}
