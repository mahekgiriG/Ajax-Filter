<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */  

 add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_style' );
  function twentytwentyone_child_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',array('parent-style'));
   // wp_enqueue_script( 'jquery-script-js', get_stylesheet_directory_uri() . '/js/jquery.min.js', array(), '1.0.0', true );
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array('jquery'), '', true);
    wp_enqueue_script('team-tag-filter', get_stylesheet_directory_uri() . '/js/team-tag-filter.js', array('jquery'), '', true);
}



/**
 * enqueue custom scripts and styles
 */
function wp_theme_scripts() {
    wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/css/custom-style.css' ,array('parent-style'));
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_style('owl-carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
    wp_enqueue_style('owl-theme-default-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');

    wp_enqueue_script( 'custom-script-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '', true );
    wp_enqueue_script( 'bootstrap-script','https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js');
    wp_enqueue_script( 'owl-carousel-script','https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js');
    
}
add_action( 'wp_enqueue_scripts', 'wp_theme_scripts' );

function localize_ajaxurl() {
    wp_localize_script('custom-script', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'localize_ajaxurl');

// theme options for header, footer & CTA section
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme CTA Settings',
        'menu_title'    => 'CTA',
        'parent_slug'   => 'theme-general-settings',
    ));

}



// filter code that add class to the menu li 
function add_additional_class_on_li($classes, $item, $args) {
    if(isset($args->add_li_class)) {
        $classes[] = $args->add_li_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

// filter code that add class to the meu a tag
function add_menu_link_class( $atts, $item, $args ) {
        if (property_exists($args, 'link_class')) {
            $atts['class'] = $args->link_class;
        }
        return $atts;
    }
add_filter( 'nav_menu_link_attributes', 'add_menu_link_class', 1, 3 );


/*------------------------------------*\
    Custom Post Types Teams
\*------------------------------------*/

function post_type_team(){
    
    $singular = ' Team';
    $plural = ' Teams';


$labels = array(
    'name'                  => $plural,
    'singular_name'         => $singular,
    'add_name'              => 'Add New'. $singular,
    'add_new_item'          => 'Add New' . $singular,
    'edit'                  => 'Edit'. $singular,
    'edit_item'             => 'Edit' . $singular,
    'view'                  => 'view'. $singular,
    'view_item'             => 'View' . $singular,  
    'search_term'           => 'Search' . $plural,
    'parent'                => 'Parent' . $singular,
    'not_found'             => 'No' . $plural .'found',
    'not_found_in_trash'    => 'No' . $plural .'found in trash',

);


$args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'teams' ),
        'menu_icon'          => 'dashicons-groups',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title',  'thumbnail' , 'editor', 'excerpt' )
    );

register_post_type('teams', $args);


}

add_action('init','post_type_team');


//Category

function register_taxonomy_teams_category(){

    $singular = ' Team Category';
    $plural = ' Teams Category ';


$labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Search' .$plural ,
        'popular_items'              => 'Popular' .$plural ,
        'all_items'                  => 'All' .$plural ,
        'parent_item'                =>  null,
        'parent_item_colon'          =>  null,
        'edit_item'                  => 'Edit' .$singular ,
        'update_item'                => 'Update' .$singular ,
        'add_new_item'               => 'Add' .$singular ,
        'new_item_name'              => 'New' .$singular. 'Name',
        'separate_items_with_commas' => 'separate' .$singular. 'With commas',
        'add_or_remove_items'        => 'Add or Remove' .$plural ,
        'choose_from_most_used'      => 'choose from most used' .$plural ,
        'not_found'                  => 'No' . $plural .'found',
        'menu_name'                  => $plural,
    );


 $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'teams-category' ),
    );

register_taxonomy( 'teams-category', 'teams', $args );
}

add_action('init','register_taxonomy_teams_category');


//Tag

function register_taxonomy_teams_tag(){

    $singular = 'Team Tag';
    $plural = 'Teams Tag';


$labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Search' .$plural ,
        'popular_items'              => 'Popular' .$plural ,
        'all_items'                  => 'All' .$plural ,
        'parent_item'                =>  null,
        'parent_item_colon'          =>  null,
        'edit_item'                  => 'Edit' .$singular ,
        'update_item'                => 'Update' .$singular ,
        'add_new_item'               => 'Add' .$singular ,
        'new_item_name'              => 'New' .$singular. 'Name',
        'separate_items_with_commas' => 'separate' .$singular. 'With commas',
        'add_or_remove_items'        => 'Add or Remaove' .$plural ,
        'choose_from_most_used'      => 'choose from most used' .$plural ,
        'not_found'                  => 'No' . $plural .'found',
        'menu_name'                  => $plural,
    );


 $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'teams-tag' ),
    );

register_taxonomy( 'teams-tag', 'teams', $args );
}

add_action('init','register_taxonomy_teams_tag');


/*------------------------------------*\
    Custom Post Types Products
\*------------------------------------*/

function post_type_product(){
    
    $singular = ' Product';
    $plural = ' Products';


$labels = array(
    'name'                  => $plural,
    'singular_name'         => $singular,
    'add_name'              => 'Add New'. $singular,
    'add_new_item'          => 'Add New' . $singular,
    'edit'                  => 'Edit'. $singular,
    'edit_item'             => 'Edit' . $singular,
    'view'                  => 'view'. $singular,
    'view_item'             => 'View' . $singular,  
    'search_term'           => 'Search' . $plural,
    'parent'                => 'Parent' . $singular,
    'not_found'             => 'No' . $plural .'found',
    'not_found_in_trash'    => 'No' . $plural .'found in trash',

);


$args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'products' ),
        'menu_icon'          => 'dashicons-products',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title',  'thumbnail' , 'editor', 'excerpt' )
    );

register_post_type('products', $args);


}

add_action('init','post_type_product');


//Category

function register_taxonomy_product_category(){

    $singular = ' Product Category';
    $plural = ' Products Category ';


$labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Search' .$plural ,
        'popular_items'              => 'Popular' .$plural ,
        'all_items'                  => 'All' .$plural ,
        'parent_item'                =>  null,
        'parent_item_colon'          =>  null,
        'edit_item'                  => 'Edit' .$singular ,
        'update_item'                => 'Update' .$singular ,
        'add_new_item'               => 'Add' .$singular ,
        'new_item_name'              => 'New' .$singular. 'Name',
        'separate_items_with_commas' => 'separate' .$singular. 'With commas',
        'add_or_remove_items'        => 'Add or Remove' .$plural ,
        'choose_from_most_used'      => 'choose from most used' .$plural ,
        'not_found'                  => 'No' . $plural .'found',
        'menu_name'                  => $plural,
    );


 $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'products-category' ),
    );

register_taxonomy( 'products-category', 'products', $args );
}

add_action('init','register_taxonomy_product_category');


//Tag

function register_taxonomy_product_tag(){

    $singular = 'Product Tag';
    $plural = 'Products Tag';


$labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Search' .$plural ,
        'popular_items'              => 'Popular' .$plural ,
        'all_items'                  => 'All' .$plural ,
        'parent_item'                =>  null,
        'parent_item_colon'          =>  null,
        'edit_item'                  => 'Edit' .$singular ,
        'update_item'                => 'Update' .$singular ,
        'add_new_item'               => 'Add' .$singular ,
        'new_item_name'              => 'New' .$singular. 'Name',
        'separate_items_with_commas' => 'separate' .$singular. 'With commas',
        'add_or_remove_items'        => 'Add or Remaove' .$plural ,
        'choose_from_most_used'      => 'choose from most used' .$plural ,
        'not_found'                  => 'No' . $plural .'found',
        'menu_name'                  => $plural,
    );


 $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'products-tag' ),
    );

register_taxonomy( 'products-tag', 'products', $args );
}

add_action('init','register_taxonomy_product_tag');


/*------------------------------------*\
    Custom Post Types Downloads
\*------------------------------------*/

function post_type_download(){
    
    $singular = ' Download';
    $plural = ' Downloads';


$labels = array(
    'name'                  => $plural,
    'singular_name'         => $singular,
    'add_name'              => 'Add New'. $singular,
    'add_new_item'          => 'Add New' . $singular,
    'edit'                  => 'Edit'. $singular,
    'edit_item'             => 'Edit' . $singular,
    'view'                  => 'view'. $singular,
    'view_item'             => 'View' . $singular,  
    'search_term'           => 'Search' . $plural,
    'parent'                => 'Parent' . $singular,
    'not_found'             => 'No' . $plural .'found',
    'not_found_in_trash'    => 'No' . $plural .'found in trash',

);


$args = array(
        'labels'             => $labels,
        'description'        => __( 'Description.', 'your-plugin-textdomain' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'downloads' ),
        'menu_icon'          => 'dashicons-download',
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title',  'thumbnail' , 'editor', 'excerpt' )
    );

register_post_type('downloads', $args);


}

add_action('init','post_type_download');


//Category

function register_taxonomy_downloads_category(){

    $singular = ' Download Category';
    $plural = ' Downloads Category ';


$labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Search' .$plural ,
        'popular_items'              => 'Popular' .$plural ,
        'all_items'                  => 'All' .$plural ,
        'parent_item'                =>  null,
        'parent_item_colon'          =>  null,
        'edit_item'                  => 'Edit' .$singular ,
        'update_item'                => 'Update' .$singular ,
        'add_new_item'               => 'Add' .$singular ,
        'new_item_name'              => 'New' .$singular. 'Name',
        'separate_items_with_commas' => 'separate' .$singular. 'With commas',
        'add_or_remove_items'        => 'Add or Remove' .$plural ,
        'choose_from_most_used'      => 'choose from most used' .$plural ,
        'not_found'                  => 'No' . $plural .'found',
        'menu_name'                  => $plural,
    );


 $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'downloads-category' ),
    );

register_taxonomy( 'downloads-category', 'downloads', $args );
}

add_action('init','register_taxonomy_downloads_category');


//Tag

function register_taxonomy_downloads_tag(){

    $singular = 'Download Tag';
    $plural = 'Downloads Tag';


$labels = array(
        'name'                       => $plural,
        'singular_name'              => $singular,
        'search_items'               => 'Search' .$plural ,
        'popular_items'              => 'Popular' .$plural ,
        'all_items'                  => 'All' .$plural ,
        'parent_item'                =>  null,
        'parent_item_colon'          =>  null,
        'edit_item'                  => 'Edit' .$singular ,
        'update_item'                => 'Update' .$singular ,
        'add_new_item'               => 'Add' .$singular ,
        'new_item_name'              => 'New' .$singular. 'Name',
        'separate_items_with_commas' => 'separate' .$singular. 'With commas',
        'add_or_remove_items'        => 'Add or Remaove' .$plural ,
        'choose_from_most_used'      => 'choose from most used' .$plural ,
        'not_found'                  => 'No' . $plural .'found',
        'menu_name'                  => $plural,
    );


 $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'downloads-tag' ),
    );

register_taxonomy( 'downloads-tag', 'downloads', $args );
}

add_action('init','register_taxonomy_downloads_tag');


function filter_custom_posts() {
    // Get the filter parameters from the AJAX request
    $post_title_filter = sanitize_text_field($_POST['post_title_filter']);
    $position_filter = sanitize_text_field($_POST['position_filter']);
    $filter_input_text = sanitize_text_field($_POST['filter_input_text']);


    // Customize this query to match your custom post type and filters

    $args_data = array(
        'post_type' => 'teams',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'meta_query' => array(),
    );

    // Add filter by post title
    if (!empty($post_title_filter)) {
        $args_data['s'] = $post_title_filter; // Search by post title.
    }

    // Add filter by position
    if (!empty($position_filter)) {
        $args_data['meta_query'][] = array(
            'key' => 'position', // Replace with the actual custom field name for positions.
            'value' => $position_filter,
            'compare' => '=',
        );
    }

    // Query the posts
    $team_query_data = new WP_Query($args_data);

    // Output the filtered posts
    if ($team_query_data->have_posts()) :
        while ($team_query_data->have_posts()) : $team_query_data->the_post();
            // Customize this part to display your post content
            $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $team_position = get_field('position');

            echo '<div class="col-lg-3 col-md-4">';
            echo '<div class="team-item-block">';
            echo '<div class="team-item-block-inner">';
            echo '<div class="round-icon-inner d-md-none">';
            echo '<a href="tel:<?php echo $mobile_number; ?>"><img src="https://fiducia.ama-staging.co.uk/wp-content/uploads/2023/10/icon-mob.png" alt="Mobile" /></a>';
            echo '</div>';
            echo '<a href="' . get_permalink() . '">';
            echo '<div class="team-img-sec">';
            echo '<img src="' . esc_url($featured_img_url) . '" alt="" class="img-fluid">';
            echo '</div>';
            echo '<div class="team-txt-block">';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<p>' . esc_html($team_position) . '</p>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found.';
    endif;

    die(); // Always end with die() in AJAX functions.
}

add_action('wp_ajax_filter_custom_posts', 'filter_custom_posts'); // For logged-in users
add_action('wp_ajax_nopriv_filter_custom_posts', 'filter_custom_posts'); // For non-logged-in users



function your_function() { ?>

    <script>
      
    jQuery(document).ready(function () {
        // Handle form submission when select elements change
        jQuery('#post-title-filter, #position-filter').change(function (e) {
            e.preventDefault();

            // Get the filter values
            var postTitleFilter = jQuery('#post-title-filter').val();
            var positionFilter = jQuery('#position-filter').val();

            // Make an AJAX request
            jQuery.ajax({
                type: 'POST',
                url: myAjax.ajaxurl,
                data: {
                    action: 'filter_custom_posts',
                    post_title_filter: postTitleFilter,
                    position_filter: positionFilter,
                },
                success: function (response) {
                    // Handle the response and update your HTML
                    jQuery('.team-content-sec-inner .row').html(response);
                },
                error: function () {
                    alert('An error occurred.');
                },
            });
        });

        // You can uncomment and adapt the input field filtering code as needed
        // jQuery('#filter-input').on('input', function () {
        //     var inputText = jQuery(this).val();

        //     // Filter the options in both select elements
        //     jQuery('#post-title-filter option, #position-filter option').each(function () {
        //         var optionText = jQuery(this).text().toLowerCase();

        //         // Your filtering logic here
        //     });
        // });
    });


</script>
<?php

}
add_action( 'wp_footer', 'your_function' );



function search_teams() {
    $search_term = sanitize_text_field($_POST['search_term']);

    $args = array(
        'post_type' => 'teams',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
        's' => $search_term,
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Output the post title or any other information you want to display
            $featured_img_url1 = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $team_position1 = get_field('position');

            echo '<div class="col-lg-3 col-md-4">';
            echo '<div class="team-item-block">';
            echo '<div class="team-item-block-inner">';
            echo '<div class="round-icon-inner d-md-none">';
            echo '<a href="tel:<?php echo $mobile_number; ?>"><img src="https://fiducia.ama-staging.co.uk/wp-content/uploads/2023/10/icon-mob.png" alt="Mobile" /></a>';
            echo '</div>';
            echo '<a href="' . get_permalink() . '">';
            echo '<div class="team-img-sec">';
            echo '<img src="' . esc_url($featured_img_url1) . '" alt="" class="img-fluid">';
            echo '</div>';
            echo '<div class="team-txt-block">';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<p>' . esc_html($team_position1) . '</p>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
         endwhile;
        wp_reset_postdata();
    else :
        echo 'No posts found.';
    endif;

    $response = ob_get_clean();

    echo $response;

    die(); 
}

add_action('wp_ajax_search_teams', 'search_teams');
add_action('wp_ajax_nopriv_search_teams', 'search_teams');


// added a sidebar for header search
function wpb_widgets_init() {
 
    register_sidebar( array(
        'name'          => 'Custom Header Widget Area',
        'id'            => 'custom-header-widget',
        'before_widget' => '<div class="chw-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="chw-title">',
        'after_title'   => '</h2>',
    ) );
 
}
add_action( 'widgets_init', 'wpb_widgets_init' );

add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
     
    if ($pagenow === 'edit-comments.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
 
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
 
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
 
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
 
// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);
 
// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
 
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

function filter_media_comment_status( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );



// 1. Register AJAX Handler
add_action('wp_ajax_filter_posts_by_team_tag', 'filter_posts_by_team_tag');
add_action('wp_ajax_nopriv_filter_posts_by_team_tag', 'filter_posts_by_team_tag');

function filter_posts_by_team_tag() {
    // Verify nonce for security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'team_tag_filter_nonce' ) ) {
        die( 'Permission denied' );
    }

    // Sanitize tag slug
    $tag_slug = isset($_POST['tag']) ? sanitize_text_field($_POST['tag']) : '';

    // Check if "Show All" option is selected
    if ($tag_slug === 'show-all') {
        $args = array(
            'post_type' => 'teams',
            'posts_per_page' => -1, // Show all posts
        );
    } else {
        $args = array(
            'post_type' => 'teams',
            'tax_query' => array(
                array(
                    'taxonomy' => 'teams-tag',
                    'field' => 'slug',
                    'terms' => $tag_slug,
                ),
            ),
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            // Output the post content or any other information you want to display
            $featured_img_url1 = get_the_post_thumbnail_url(get_the_ID(), 'full');
            $team_position1 = get_field('position');

            echo '<div class="col-lg-3 col-md-4">';
            echo '<div class="team-item-block">';
            echo '<div class="team-item-block-inner">';
            echo '<div class="round-icon-inner d-md-none">';
            echo '<a href="tel:' . get_field('mobile_number') . '"><img src="https://fiducia.ama-staging.co.uk/wp-content/uploads/2023/10/icon-mob.png" alt="Mobile" /></a>';
            echo '</div>';
            echo '<a href="' . get_permalink() . '">';
            echo '<div class="team-img-sec">';
            echo '<img src="' . esc_url($featured_img_url1) . '" alt="" class="img-fluid">';
            echo '</div>';
            echo '<div class="team-txt-block">';
            echo '<h2>' . get_the_title() . '</h2>';
            echo '<p>' . esc_html($team_position1) . '</p>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        endwhile;
        wp_reset_postdata();
    else:
        echo 'No posts found';
    endif;

    die(); // Always end with die() to prevent extra output
}

// 2. Create the Select Dropdown
function team_tag_filter_dropdown() {
    $terms = get_terms(array(
        'taxonomy' => 'teams-tag',
        'hide_empty' => true,
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<select class="form-control" id="team_tag_filter">';
        echo '<option value="show-all">Filter By Specialty</option>'; // Option to show all posts
        foreach ($terms as $term) {
            echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
        }
        echo '</select>';
    }
}

// 3. Enqueue Script for AJAX Handling
function enqueue_team_tag_filter_script() {
    wp_enqueue_script('team-tag-filter', get_template_directory_uri() . '/js/team-tag-filter.js', array('jquery'), null, true);
    wp_localize_script('team-tag-filter', 'team_tag_filter_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce( 'team_tag_filter_nonce' ), // Create nonce for security
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_team_tag_filter_script');

