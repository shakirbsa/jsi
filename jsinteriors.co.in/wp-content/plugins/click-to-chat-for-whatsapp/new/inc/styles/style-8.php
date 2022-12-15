<?php
/**
 * Style - 5
 * 
 * Button with icon
 * 
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$s8_options = get_option( 'ht_ctc_s8' );

?>
<style>
.ht-ctc-style-8 {
  display: none;
}
</style>
<?php

wp_enqueue_style('ht_ctc_font_css');
wp_enqueue_style('ht_ctc_mdstyle8_css');


$s8_txt_color = esc_attr( $s8_options['s8_txt_color'] );
$s8_icon_color = esc_attr( $s8_options['s8_icon_color'] );
$s8_txt_color_on_hover = esc_attr( $s8_options['s8_txt_color_on_hover'] );
$s8_bg_color = esc_attr( $s8_options['s8_bg_color'] );
$s8_bg_color_on_hover = esc_attr( $s8_options['s8_bg_color_on_hover'] );

$s8_icon_color_on_hover = esc_attr( $s8_options['s8_icon_color_on_hover'] );

$s8_icon_position = esc_attr( $s8_options['s8_icon_position'] );

?>

<div class = "mdstyle8 ht-ctc-style-8" >
     
      <span class="waves-effect waves-light btn ctc-analytics" style="background-color: <?php echo $s8_bg_color ?>; "
      onmouseover = "this.style.backgroundColor = '<?php echo $s8_bg_color_on_hover ?>',
      document.getElementsByClassName('ht-ctc-s8-icon')[0].style.color = '<?php echo $s8_icon_color_on_hover ?>',
      document.getElementsByClassName('ht-ctc-s8-text')[0].style.color = '<?php echo $s8_txt_color_on_hover ?>'
      "
      onmouseout  = "this.style.backgroundColor = '<?php echo $s8_bg_color ?>',
      document.getElementsByClassName('ht-ctc-s8-icon')[0].style.color = '<?php echo $s8_icon_color ?>',
      document.getElementsByClassName('ht-ctc-s8-text')[0].style.color = '<?php echo $s8_txt_color ?>'
      ">
        <i class="material-icons ctc-analytics <?php echo $s8_icon_position ?> ctc-icon ctc-icon-whatsapp2 ht-ctc-s8-icon" 
        style="color: <?php echo $s8_icon_color ?>;">
        </i>
        <span class="ht-ctc-s8-text ctc-analytics" style="color: <?php echo $s8_txt_color ?>;">
          <?php echo $call_to_action ?>
        </span>
      </span>

</div>