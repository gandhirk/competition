<?php
if(!defined('SUBMIT_ENQUIRY_PAGE_TITLE')){
    define('SUBMIT_ENQUIRY_PAGE_TITLE','Submit Entry');
}
if(!defined('SUBMIT_ENQUIRY_PAGE_SLUG')) {
    define('SUBMIT_ENQUIRY_PAGE_SLUG', 'submit-entry');
}

add_action( 'admin_enqueue_scripts', 'competition_load_admin_style_and_scripts' );
add_action( 'wp_enqueue_scripts', 'competition_load_admin_style_and_scripts' );

function competition_load_admin_style_and_scripts() {
    wp_register_style('competition-jquery-ui-style','https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css','','','');
    wp_enqueue_style('competition-jquery-ui-style');

    wp_enqueue_script( 'competition-custom-script', get_template_directory_uri() . '/task-files/js/custom.js', array('jquery','jquery-ui-datepicker'), '', true );
    $competition_script_array = array(
        'ajax_url' => admin_url('admin-ajax.php')
    );
    wp_localize_script('competition-custom-script', 'competition_scripts', $competition_script_array );
}

/**
 * add action for register custom posttype when theme is activate.
 */
add_action( 'init', 'cp_register_custom_posttype' );

if( !function_exists( 'cp_register_custom_posttype' ) ) {

    /**
     * Function are use to register custom PostType.
     *
     * @since 1.0.0
     */
    function cp_register_custom_posttype() {

        /**
         * Competitions PostType labels.
         */
        $competition_labels = array(
            'name'               => _x( 'Competitions', 'post type general name', 'competition' ),
            'singular_name'      => _x( 'Competition', 'post type singular name', 'competition' ),
            'menu_name'          => _x( 'Competitions', 'admin menu', 'competition' ),
            'name_admin_bar'     => _x( 'Competition', 'add new on admin bar', 'competition' ),
            'add_new'            => _x( 'Add New', 'competition', 'competition' ),
            'add_new_item'       => __( 'Add New Competition', 'competition' ),
            'new_item'           => __( 'New Competition', 'competition' ),
            'edit_item'          => __( 'Edit Competition', 'competition' ),
            'view_item'          => __( 'View Competition', 'competition' ),
            'all_items'          => __( 'All Competitions', 'competition' ),
            'search_items'       => __( 'Search Competitions', 'competition' ),
            'parent_item_colon'  => __( 'Parent Competitions:', 'competition' ),
            'not_found'          => __( 'No competitions found.', 'competition' ),
            'not_found_in_trash' => __( 'No competitions found in Trash.', 'competition' )
        );

        /**
         * Competitions PostType arguments.
         */
        $competition_args = array(
            'labels'             => $competition_labels,
            'description'        => __( 'Description.', 'competition' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'competitions' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' )
        );

        /**
         * Competitions PostType register.
         */
        register_post_type( 'competitions', $competition_args );

        /**
         * Entries PostType labels.
         */
        $entries_labels = array(
            'name'               => _x( 'Entries', 'post type general name', 'competition' ),
            'singular_name'      => _x( 'Entry', 'post type singular name', 'competition' ),
            'menu_name'          => _x( 'Entries', 'admin menu', 'competition' ),
            'name_admin_bar'     => _x( 'Entry', 'add new on admin bar', 'competition' ),
            'add_new'            => _x( 'Add New', 'Entry', 'competition' ),
            'add_new_item'       => __( 'Add New Entry', 'competition' ),
            'new_item'           => __( 'New Entry', 'competition' ),
            'edit_item'          => __( 'Edit Entry', 'competition' ),
            'view_item'          => __( 'View Entry', 'competition' ),
            'all_items'          => __( 'All Entries', 'competition' ),
            'search_items'       => __( 'Search Entries', 'competition' ),
            'parent_item_colon'  => __( 'Parent Entries:', 'competition' ),
            'not_found'          => __( 'No Entries found.', 'competition' ),
            'not_found_in_trash' => __( 'No Entries found in Trash.', 'competition' )
        );

        /**
         * Entries PostType arguments.
         */
        $entries_args = array(
            'labels'             => $entries_labels,
            'description'        => __( 'Description.', 'competition' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'entries' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields' )
        );

        /**
         * Entries PostType register.
         */
        register_post_type( 'entries', $entries_args );

        $getPage = get_page_by_path(SUBMIT_ENQUIRY_PAGE_SLUG );

        if( empty($getPage) && '' == $getPage ) {

            $page_args = array(
                'post_title' => wp_strip_all_tags(SUBMIT_ENQUIRY_PAGE_TITLE),
                'post_content' => "[submit_entry_form]",
                'post_status' => 'publish',
                'post_name' => SUBMIT_ENQUIRY_PAGE_SLUG,
                'post_author' => 1,
                'post_type' => 'page'
            );

            $page_ID = wp_insert_post($page_args);

            update_option('submit_entry_page_id',$page_ID); //@ToDO provide settings for entry page in backend.
        }

        add_rewrite_rule( '([^/]*)/submit-entry?$', 'index.php?c_id=$matches[1]', 'top' );
    }
}

add_filter( 'query_vars', 'cp_register_query_var' );
function cp_register_query_var( $vars ) {
    $vars[] = 'c_id';

    return $vars;
}

add_action( 'add_meta_boxes', 'cp_register_meta_box');
function cp_register_meta_box() {
    add_meta_box( 'competition-meta-fields', esc_html__( 'Competition Fields', 'competition' ), 'cp_meta_box_cb', 'competitions', 'advanced', 'high' );
    add_meta_box( 'entries-meta-fields', esc_html__( 'Submitted Fields', 'competition' ), 'cp_cp_entries_meta_box_cb', 'entries', 'advanced', 'high' );
}

if( !function_exists('cp_meta_box_cb')) {

    function cp_meta_box_cb() {
        global $post;
        $competitions_start_date = get_post_meta($post->ID, 'competitions_start_date', true);
        $competitions_end_date = get_post_meta($post->ID, 'competitions_end_date', true);
        ?>
        <div class="competition-fields">

            <?php do_action('competition_before_fields'); ?>

            <div class="competition-start-date">
                <p><label><?php _e('Start Date:','competition'); ?></label> <input type="text" value="<?php echo !empty( $competitions_start_date ) ? $competitions_start_date : ''; ?>" class="regular-text competition-date-picker" name="competition_start_date" id="competition_start_date"></p>
            </div>
            <div class="competition-end-date">
                <p><label><?php _e('End Date:','competition'); ?></label> <input type="text" value="<?php echo !empty( $competitions_end_date ) ? $competitions_end_date : ''; ?>" class="regular-text competition-date-picker" name="competition_end_date" id="competition_end_date"></p>
            </div>

            <?php do_action('competition_after_fields'); ?>

        </div>
        <?php
    }

}

if( !function_exists('cp_entries_meta_box_cb') ) {

    function cp_entries_meta_box_cb() {

        global $post;
        $se_first_name = get_post_meta($post->ID, 'se_first_name', true);
        $se_last_name = get_post_meta($post->ID, 'se_last_name', true);
        $se_email = get_post_meta($post->ID, 'se_email', true);
        $se_phone = get_post_meta($post->ID, 'se_phone', true);
        $se_c_id = get_post_meta($post->ID, 'se_c_id', true);
        ?>
        <div class="entries-fields">

            <?php do_action('entries_before_fields'); ?>

            <div class="entries-blog">
                <p><label><?php _e('First Name:','competition'); ?></label> <?php echo $se_first_name; ?></p>
            </div>
            <div class="entries-blog">
                <p><label><?php _e('Last Name:','competition'); ?></label> <?php echo $se_last_name; ?></p>
            </div>
            <div class="entries-blog">
                <p><label><?php _e('Email:','competition'); ?></label> <?php echo $se_email; ?></p>
            </div>
            <div class="entries-blog">
                <p><label><?php _e('Phone:','competition'); ?></label> <?php echo $se_phone; ?></p>
            </div>
            <div class="entries-blog">
                <p><label><?php _e('Competition ID:','competition'); ?></label> <?php echo $se_c_id; ?></p>
            </div>
            <div class="entries-blog">
                <p><label><?php _e('Competition Title:','competition'); ?></label> <?php echo get_the_title($se_c_id); ?></p>
            </div>

            <?php do_action('entries_before_fields'); ?>

        </div>
        <?php
    }
}

add_action('save_post', 'cp_save_custom_meta_data');

if( !function_exists( 'cp_save_custom_meta_data') ) {

    function cp_save_custom_meta_data() {

        if (!empty($_POST['post_type']) && 'competitions' == $_POST['post_type']) {

            $competitions_start_date = !empty( $_POST['competition_start_date'] ) ? $_POST['competition_start_date'] : '';
            $competitions_end_date = !empty( $_POST['competition_end_date'] ) ? $_POST['competition_end_date'] : '';

            update_post_meta($_POST['ID'], 'competitions_start_date', $competitions_start_date);
            update_post_meta($_POST['ID'], 'competitions_end_date', $competitions_end_date);
        }

    }

}

function cp_url_rewrite_templates($template) {

    if ( get_query_var( 'c_id' )) {
        return get_template_directory() . '/task-files/template-entry-form.php';
    }

    return $template;
}

add_action( 'template_include', 'cp_url_rewrite_templates' );

add_shortcode( 'submit_entry_form', 'submit_entry_form_cb' );

function submit_entry_form_cb( $atts ) {

    $atts = shortcode_atts( array(
        'title' => __('Submit Entry','competition'),
    ), $atts, 'submit_entry_form' );

    $c_id = get_query_var( 'c_id' );

    $args = array(
        'name'        => $c_id,
        'post_type'   => 'competitions',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $c_posts = get_posts($args);
    if( $c_posts ){
        $c_post_id = $c_posts[0]->ID;
    } else {
        return '';
    }

    ob_start();
    ?>
    <div class="competition-entry-form-main">
        <div class="competition-form-title"><h2><?php echo !empty($atts['title'] ) ? $atts['title'] :''; ?></h2></div>
        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" >
            <input type="hidden" name="action" value="submit_enquiry">
            <input type="hidden" name="_nonce" id="se_nonce" value="<?php echo wp_create_nonce('submit_enquiry_form'); ?>">
            <input type="hidden" name="post_id" id="se_post_id" value="<?php echo $c_post_id; ?>">
            <div class="form-row">
                <label><?php _e('First Name:','competition'); ?></label>
                <input type="text" name="se_first_name" id="se_first_name" value="">
            </div>
            <div class="form-row">
                <label><?php _e('Last Name:','competition'); ?></label>
                <input type="text" name="se_last_name" id="se_last_name" value="">
            </div>
            <div class="form-row">
                <label><?php _e('Email:','competition'); ?></label>
                <input type="email" name="se_email" id="se_email" value="">
            </div>
            <div class="form-row">
                <label><?php _e('Phone:','competition'); ?></label>
                <input type="text" name="se_phone" id="se_phone" value="">
            </div>
            <div class="form-row">
                <label><?php _e('Description:','competition'); ?></label>
                <textarea name="se_description" id="se_description"></textarea>
            </div>
            <div class="form-row">
                <input type="button" name="se_submit" id="se_submit" value="<?php _e('Submit Entry','competition'); ?>">
                <label class="message"></label>
            </div>
        </form>
    </div>
    <?php

    $html = ob_get_contents();
    ob_get_clean();

    return $html;
}

add_filter( 'the_content', 'cp_after_content_cb' );

function cp_after_content_cb( $content ) {

    global $post;

    if( !empty( $post->post_type ) && 'competitions' === $post->post_type ) {

        $getPageID = get_option('submit_entry_page_id');

        if( !empty( $getPageID ) ) {
            $getFormPageLink = site_url('/').$post->post_name.'/'.SUBMIT_ENQUIRY_PAGE_SLUG;
            $content .= '<div class="entry-form-btn"><a href="'.$getFormPageLink.'" class="button btn">'.__('Submit Entry','competition').'</a></div>';
        }

    }

    return $content;
}

add_action('wp_ajax_competition_submit_enquiry', 'cp_submit_enquiry_cb');
add_action('wp_ajax_nopriv_competition_submit_enquiry', 'cp_submit_enquiry_cb');

function cp_submit_enquiry_cb() {

    $first_name = !empty( $_POST['first_name'] ) ? $_POST['first_name'] :'';
    $last_name = !empty( $_POST['last_name'] ) ? $_POST['last_name'] :'';
    $email = !empty( $_POST['email'] ) ? $_POST['email'] :'';
    $phone = !empty( $_POST['phone'] ) ? $_POST['phone'] :'';
    $description = !empty( $_POST['description'] ) ? $_POST['description'] :'';
    $c_id = !empty( $_POST['post_id'] ) ? $_POST['post_id'] :'';

    if( check_ajax_referer( 'submit_enquiry_form', '_nonce' ) ) {

        $cTitle = get_the_title($c_id);
        $currentDate = date('Y-m-d h:i:s');
        $entries_title = __('Inquiry by: ','competition').$first_name.' '.$last_name .__(' for ', 'competition'). $cTitle.' ('.$currentDate.')';
        $post_args = array(
            'post_title' => wp_strip_all_tags($entries_title),
            'post_content' => $description,
            'post_status' => 'publish',
            'post_type' => 'entries'
        );

        $post_id = wp_insert_post($post_args);

        update_post_meta($post_id,'se_first_name',$first_name);
        update_post_meta($post_id,'se_last_name',$last_name);
        update_post_meta($post_id,'se_email',$email);
        update_post_meta($post_id,'se_phone',$phone);
        update_post_meta($post_id,'se_c_id',$c_id);

        $message = __('Form submitted successfully','competition');
    } else{
        $message = __('Failed to submit form. Please try again after refreshing page.','competition');
    }

    echo $message;
    wp_die();

}