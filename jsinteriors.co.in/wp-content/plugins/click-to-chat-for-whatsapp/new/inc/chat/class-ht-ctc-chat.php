<?php
/**
 * WhatsApp Chat  - main page .. 
 * 
 * @subpackage chat
 */



if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Chat' ) ) :

class HT_CTC_Chat {


    /**
     * Which features are enable - based on this call function .. 
     */
    public function chat() {
        
        $options = get_option('ht_ctc_chat_options');
        

        // show/hide .. 
        include_once HT_CTC_PLUGIN_DIR .'new/inc/chat/chat-show-hide.php';

        if ( 'no' == $display ) {
            return;
        }
        
        $main_options = ht_ctc()->values->ctc_main_options;

        // position
        include_once HT_CTC_PLUGIN_DIR .'new/inc/chat/chat-position.php';
        
        // is mobile to select styles
        $is_mobile = ht_ctc()->device_type->is_mobile();

        $device_class = '';

        // style
        if ( 'yes' == $is_mobile ) {
            $style = esc_attr( $options['style_mobile'] );
            $device_class = 'mobile';
        } else {
            $style = esc_attr( $options['style_desktop'] );
            $device_class = 'desktop';
        }

        // call to action
        // todo localization for number, .. ( at variables page ) - call to action for share, group
        $call_to_action_db = esc_attr( $options['call_to_action'] );
        $call_to_action = __( $call_to_action_db , 'click-to-chat-for-whatsapp' );


        // call to action - at page level
        $page_id = get_the_ID();
        $page_call_to_action = esc_attr( get_post_meta( $page_id, 'ht_ctc_page_call_to_action', true ) );

        if ( isset( $page_call_to_action ) && '' !== $page_call_to_action ){
            $call_to_action = $page_call_to_action;
        }

        // class names
        $class_names = "ht-ctc-chat style-$style $device_class";

        $page_id = get_the_ID();
        $page_url = get_permalink();
        $post_title = esc_html( get_the_title() );

        // number
        $number = esc_attr( $options['number'] );
        
        // number - at page level
        $page_number = esc_attr( get_post_meta( $page_id, 'ht_ctc_page_number', true ) );

        if ( isset( $page_number ) && '' !== $page_number ){
            $number = $page_number;
        }

        // prefilled text
        $pre_filled = esc_attr( $options['pre_filled'] );
        $pre_filled = str_replace( '{{url}}', $page_url, $pre_filled );
        $pre_filled = str_replace( '{{title}}', $post_title, $pre_filled );
        
        // analytics
        $is_ga_enable = 'no';
        $is_fb_an_enable = 'no';

        if ( isset( $main_options['google_analytics'] ) ) {
            $is_ga_enable = 'yes';
        }

        if ( isset( $main_options['fb_analytics'] ) ) {
            $is_fb_an_enable = 'yes';
        }
        
        // 1: web,  api:  wa.me
        $webandapi = 'api';
        if ( isset( $options['webandapi'] ) ) {
            $webandapi = '1';
        }

        
        // call style
        $path = plugin_dir_path( HT_CTC_PLUGIN_FILE ) . 'new/inc/styles/style-' . $style. '.php';

        if ( is_file( $path ) ) {
            ?>
            <div onclick="ht_ctc_click(this);" class="<?php echo $class_names ?>" style="position: fixed; <?php echo $position ?> cursor: pointer; z-index: 99999999;"
                data-return_type="chat" 
                data-number="<?php echo $number ?>" 
                data-pre_filled="<?php echo $pre_filled ?>" 
                data-is_ga_enable="<?php echo $is_ga_enable ?>" 
                data-is_fb_an_enable="<?php echo $is_fb_an_enable ?>" 
                data-webandapi="<?php echo $webandapi ?>" 
                >
                <?php include $path; ?>
            </div>
            <?php
        }

        
    }

}

// new HT_CTC_Chat();

$ht_ctc_chat = new HT_CTC_Chat();
add_action( 'wp_footer', array( $ht_ctc_chat, 'chat' ) );


endif; // END class_exists check