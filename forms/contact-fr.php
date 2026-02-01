<?php
  /**
  * Formulaire de contact - Version française
  * Envoie les messages à contact@famousagrotech.com
  */

  $receiving_email_address = 'contact@famousagrotech.com';

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Méthode non autorisée.';
    exit;
  }

  if( file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php' )) {
    include( $php_email_form );
  } else {
    die( 'Impossible de charger la bibliothèque "PHP Email Form" !');
  }

  $contact = new PHP_Email_Form;
  $contact->ajax = true;
  
  $contact->to = $receiving_email_address;
  $contact->from_name = $_POST['name'];
  $contact->from_email = $_POST['email'];
  $contact->subject = $_POST['subject'];

  $contact->add_message( $_POST['name'], 'De');
  $contact->add_message( $_POST['email'], 'Email');
  $contact->add_message( $_POST['subject'], 'Sujet');
  $contact->add_message( $_POST['message'], 'Message', 10);

  echo $contact->send('fr');
?>
