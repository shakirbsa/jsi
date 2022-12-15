<?php
/**
 * Style - 3
 * 
 * IOS like - WhatsApp icon
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$s3_options = get_option( 'ht_ctc_s3' );

$s3_img_size = esc_attr( $s3_options['s3_img_size'] );

if ( !isset( $s3_options['cta_on_hover'] ) ) {
    $call_to_action = '';
}

?>
<img class="img-icon ctc-analytics" title="<?php echo $call_to_action ?>" style="height: <?php echo $s3_img_size ?>;" src="<?php echo plugins_url( './new/inc/assets/img/whatsapp-logo.svg', HT_CTC_PLUGIN_FILE ) ?>" alt="WhatsApp chat">