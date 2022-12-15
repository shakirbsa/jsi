<?php
/**
 * Share feature - main page
 * 
 * @subpackage share
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Share' ) ) :

class HT_CTC_Share {

    public function __construct() {
        // $this->share();
    }


    /**
     * Which features are enable - based on this call function .. 
     */
    public function share() {
        
        $options = get_option('ht_ctc_share');


        // show/hide ..
        include_once HT_CTC_PLUGIN_DIR .'new/inc/share/share-show-hide.php';

        if ( 'no' == $display ) {
            return;
        }

        $main_options = ht_ctc()->values->ctc_main_options;
        
        // position
        include_once HT_CTC_PLUGIN_DIR .'new/inc/share/share-position.php';
        
        // is mobile to select styles
        $is_mobile = ht_ctc()->device_type->is_mobile();

        // style
        if ( 'yes' == $is_mobile ) {
            $style = esc_html( $options['style_mobile'] );
        } else {
            $style = esc_html( $options['style_desktop'] );
        }

        // call to action
        $call_to_action = esc_html( $options['call_to_action'] );

        // class names
        $class_names = "ht-ctc-share style-$style";

        $page_url = get_permalink();
        $post_title = esc_html( get_the_title() );

        // share text
        $share_text = esc_attr( $options['share_text'] );

        // if ( is_home() || is_front_page() ) {
        if ( is_home() ) {
            $page_url = get_bloginfo('url');
            $post_title = get_bloginfo('name');
        }

        $share_text = str_replace( '{{url}}', $page_url, $share_text );
        $share_text = str_replace( '{{title}}', $post_title, $share_text );

        // analytics
        $is_ga_enable = 'no';
        $is_fb_an_enable = 'no';

        if ( isset( $main_options['google_analytics'] ) ) {
            $is_ga_enable = 'yes';
        }

        if ( isset( $main_options['fb_analytics'] ) ) {
            $is_fb_an_enable = 'yes';
        }

        // call style
        $path = plugin_dir_path( HT_CTC_PLUGIN_FILE ) . 'new/inc/styles/style-' . $style. '.php';

        if ( is_file( $path ) ) {
            ?>
            <div onclick="ht_ctc_click(this);" class="<?php echo $class_names ?>" style="position: fixed; <?php echo $position ?> cursor: pointer; z-index: 99999999;"
                data-return_type="share" 
                data-share_text="<?php echo $share_text ?>" 
                data-is_ga_enable="<?php echo $is_ga_enable ?>" 
                data-is_fb_an_enable="<?php echo $is_fb_an_enable ?>" 
                >
                <?php include $path; ?>
            </div>
            <?php
        }

        
    }

}

// new HT_CTC_Share();

$ht_ctc_share = new HT_CTC_Share();
add_action( 'wp_footer', array( $ht_ctc_share, 'share' ) );


endif; // END class_exists check