<?php
/**
 * Group chat/invite feature - main page
 * 
 * @subpackage group
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'HT_CTC_Group' ) ) :

class HT_CTC_Group {

    public function __construct() {
        // $this->group();
    }


    /**
     * Which features are enable - based on this call function .. 
     */
    public function group() {
        
        $options = get_option('ht_ctc_group');


        // show/hide ..
        include_once HT_CTC_PLUGIN_DIR .'new/inc/group/group-show-hide.php';

        if ( 'no' == $display ) {
            return;
        }

        $main_options = ht_ctc()->values->ctc_main_options;

        // position
        include_once HT_CTC_PLUGIN_DIR .'new/inc/group/group-position.php';
        
        // is mobile to select styles
        $is_mobile = ht_ctc()->device_type->is_mobile();

        // style
        if ( 'yes' == $is_mobile ) {
            $style = esc_attr( $options['style_mobile'] );
        } else {
            $style = esc_attr( $options['style_desktop'] );
        }

        // call to action
        $call_to_action = esc_attr( $options['call_to_action'] );

        // class names
        $class_names = "ht-ctc-group style-$style";

        // group id
        $group_id = esc_attr( $options['group_id'] );

        // group_id - at page level
        $page_group_id = esc_attr( get_post_meta( $page_id, 'ht_ctc_page_group_id', true ) );

        if ( isset( $page_group_id ) && '' !== $page_group_id ){
            $group_id = $page_group_id;
        }

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
                data-return_type="group" 
                data-group_id="<?php echo $group_id ?>" 
                data-is_ga_enable="<?php echo $is_ga_enable ?>" 
                data-is_fb_an_enable="<?php echo $is_fb_an_enable ?>" 
                >
                <?php include $path; ?>
            </div>
            <?php
        }

        
    }

}

// new HT_CTC_Group();

$ht_ctc_group = new HT_CTC_Group();
add_action( 'wp_footer', array( $ht_ctc_group, 'group' ) );


endif; // END class_exists check