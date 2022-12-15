<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

 add_action( 'phpmailer_init', 'setup_phpmailer_init' );
function setup_phpmailer_init( $phpmailer ) {
    $phpmailer->Sender='noreply@dev.jsinteriors.co.in';
    //$phpmailer->SMTPDebug=1;
   // $phpmailer->Mailer='smtp';
    // $phpmailer->Host = 'dev.jsinteriors.co.in'; // for example, smtp.mailtrap.io
    // $phpmailer->Port = 465; // set the appropriate port: 465, 2525, etc.
    // $phpmailer->Username = 'noreply@dev.jsinteriors.co.in'; // your SMTP username
    // $phpmailer->Password = '!rPMWsI,$%v,'; // your SMTP password
    // $phpmailer->SMTPAuth = true;
    // $phpmailer->SMTPSecure = 'tls'; // preferable but optional
}
/**
 * Note: It's not recommended to add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * Learn more here: http://codex.wordpress.org/Child_Themes
 */

/**

$rawData = file_get_contents("php://input");
$json_string = $rawData. json_encode(apache_request_headers()).json_encode($_REQUEST);
$file_handle = fopen(time().'my_filename.json', 'w');
fwrite($file_handle, $json_string);
fclose($file_handle);

**/
