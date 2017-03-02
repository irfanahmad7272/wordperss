<?php

/*------------------------------------------------------------------------------------------------------------------------------------------------------ */
//wide banner custom code
function wide_bottom_banner($current_page_id) {

    $page_ids = array();
    $banners = new WP_Query(array('post_type' => 'advertiment_banners', 'orderby' => 'rand'));
    $banner_images = array();
    while ($banners->have_posts()): $banners->the_post();
         $pages = get_post_meta(get_the_ID(), 'choose_pages_to_show', FALSE);
        $banner_size = get_post_meta(get_the_ID(), 'choose_size', true);
        if ($banner_size == "wide"):
            foreach ($pages as $page):
                $page_ids[] = $page['ID'];
            endforeach;
            if(in_array($current_page_id, $page_ids)):
                $banner_images[] = array("image" => get_the_post_thumbnail_url(), "link" => get_post_meta(get_the_ID(), 'url', true) );
                //$banner_images['img_link'] = get_post_meta(get_the_ID(), 'url', true);
            endif;
       endif;
    endwhile;
     wp_reset_query();
    $total_images = count($banner_images);
    $randomImage = rand(0, $total_images - 1);
    //var_dump($banner_images[$randomImage]['image']);exit;


    if ($banner_images):
        ?>
        <a class="wide_banner" title="wide banner" href="<?php echo $banner_images[$randomImage]['link']; ?>">
            <img src="<?php echo $banner_images[$randomImage]['image']; ?>" class="img-responsive" alt=" wide banner image">
        </a>
        <?php
    endif;
}
/*------------------------------------------------------------------------------------------------------------------------------------------------------ */
//Homo sidebar banners.
function general_sidebar_banner($current_page_id) {

   $page_ids1 = array();
    $banners = new WP_Query(array('post_type' => 'advertiment_banners', 'orderby' => 'rand'));
    $banner_images = array();
    while ($banners->have_posts()): $banners->the_post();
        $pages = get_post_meta(get_the_ID(), 'choose_pages_to_show', FALSE);
        $banner_size = get_post_meta(get_the_ID(), 'choose_size', true);
        if ($banner_size == "side"):
            foreach ($pages as $page):
                $page_ids1[] = $page['ID'];
            endforeach;
            if (in_array($current_page_id, $page_ids1)):
                $banner_images[] = array("image" => get_the_post_thumbnail_url(), "link" => get_post_meta(get_the_ID(), 'url', true) );
                //$banner_images['img_link'] = get_post_meta(get_the_ID(), 'url', true);
            endif;
       endif;
       // endif;
    endwhile;
     wp_reset_query();
    $total_images = count($banner_images);
    $randomImage = rand(0, $total_images - 1);
    //var_dump($banner_images[$randomImage]['image']);exit;


    if ($banner_images):
        ?>
            <a class="home-side" title="home siebar banner" href="<?php echo $banner_images[$randomImage]['link']; ?>">
                <img src="<?php echo $banner_images[$randomImage]['image']; ?>" class="img-responsive" alt="sidebar banner image">
            </a>
        <?php
    endif;
}

/*------------------------------------------------------------------------------------------------------------------------------------------------------ */
//contact form 7 wpcf7_before_send_mail

do_action('wpcf7_before_send_mail', $contact_form);

// define the wpcf7_before_send_mail callback 
global $post;

function action_wpcf7_before_send_mail($contact_form, $post) {
    // make action magic happen here... 

    if ($contact_form->id !== '4') {
        $submission = WPCF7_Submission::get_instance();

        if ($submission) {
            $current_post_id = $submission->get_posted_data()['postID'];
            $user_name = $submission->get_posted_data()['name'];
            $user_email = $submission->get_posted_data()['email'];
            $user_phone = $submission->get_posted_data()['phone'];
            $user_question = $submission->get_posted_data()['question'];
            $branch_name = get_the_title($current_post_id);
            $branch_owner = get_post_meta($current_post_id, 'name_of_owner', true);
            $branch_phone = get_post_meta($current_post_id, 'phone', true);
            $branch_mobile = get_post_meta($current_post_id, 'mobile', true);
            $branch_email = get_post_meta($current_post_id, 'email', true);
            $branch_link = get_the_permalink($current_post_id). '/?c_post='. $current_post_id;
            
            if(empty($branch_email)){
                $branch_email = get_option('admin_email');
            }
            
            $mail_body = 'User Name: '.$user_name. '<br>'.
                    'User Email: '.$user_email. '<br>'.
                    'User Phone: '.$user_phone. '<br>'.
                    'Branch Name: '.$branch_name. '<br>'.
                    'Branch Link: <a href="'.$branch_link.'">'. $branch_name . '</a><br>'.
                    'Branch Owner Name: '.$branch_owner. '<br>'.
                    'User Question: '.$user_question. '<br>';
            
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($branch_email, $user_question, $mail_body, $headers);
        }
    };
}

// add the action 
add_action('wpcf7_before_send_mail', 'action_wpcf7_before_send_mail', 10, 1);



/*------------------------------------------------------------------------------------------------------------------------------------------------------ */

//Search autocomplete using flexdatalist library.
function searchAutoComplete() {

    $aria_location = get_terms(array('taxonomy' => 'area_list', 'hide_empty' => false));
    $branches_lists = new WP_Query(
            array('post_type' => 'branch_profile', 'post_status' => 'publish', 'posts_per_page' => -1, 'order' => 'ASC', 'orderby' => 'menu_order')
    );

    $search_locations = array();

    if ($branches_lists->have_posts()) {
        while ($branches_lists->have_posts()): $branches_lists->the_post();
            $search_locations[] = array(
                'ID' => get_the_ID(),
                'name' => get_the_title() . ' ',
                'item_type' => 'branch_profile',
                'url' => get_the_permalink(),
            );
        endwhile;
    }

    foreach ($aria_location as $loc) {

        $search_locations[] = array(
            'ID' => $loc->term_id,
            'name' => $loc->name . ' ',
            'item_type' => 'taxonomy',
            'url' => get_term_link($loc->term_id),
        );
    }
    ?>

    <script type="text/javascript">
        jQuery('.searchField').flexdatalist({
            minLength: 1,
            visibleProperties: ["name"],
            valueProperty: 'name',
            searchIn: 'name',
            searchByWord: true,
            searchContain: true,
            data: <?php echo json_encode($search_locations); ?>
        });
    </script>
    <?php
}

/*------------------------------------------------------------------------------------------------------------------------------------------------------ */

//Login method that used in sharamfarm project (can be customized according to nature of project)
function sharamfarmLogin() {

    if (isset($_POST['loginuser']) && isset($_POST['loginpass'])):
        $creds = array('user_login' => $_POST['loginuser'], 'user_password' => $_POST['loginpass'], 'remember' => true);
        $user = wp_signon($creds, false);
        if (is_wp_error($user)) {
            wp_redirect('/?return=login_error');
        } else {
            wp_set_current_user($user->ID, $user->user_login);
            wp_set_auth_cookie($user->ID, true, false);
            do_action('wp_login', $user->user_login);
            if (is_user_logged_in()) {
                wp_redirect('/wp-admin');
            }
        }

    endif;
}


/*------------------------------------------------------------------------------------------------------------------------------------------------------ */

//Change default users roles to rename them.
function wps_change_role_name() {
    global $wp_roles;
    if (!isset($wp_roles))
        $wp_roles = new WP_Roles();
    $wp_roles->roles['subscriber']['name'] = ' לקוחות רשומים';
    $wp_roles->role_names['subscriber'] = 'לקוחות רשומים';
    $wp_roles->roles['contributor']['name'] = ' סניפים';
    $wp_roles->role_names['contributor'] = 'סניפים';
}
