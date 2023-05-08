<?php

/**
 * Plugin Name:     Testify Box
 * Plugin URI:      https://myblog.com/testifybox
 * Description:     Create your own testimonials and enjoy!!
 * Version:         1.0.0
 * Author:          Anjali Shrestha
 * TextDomain:      testifybox
 */


/**
 * Register 'TestifyBox' Post Type
 * 
 * @since 1.0.0
 * @return void
 */
function testifybox_custom_post_type() {
    $labels = array(
        'name' => 'Testify Box',
        'singular_name' => 'Testify Box',
        'add new' => 'Add New',
    );
    $args = array(
        'labels' => $labels,
        'hierarchical ' => true,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true, 
        'show_in_rest' => true,
        'menu_position' => null,
        'show_in_menu' => true, 
    );
    register_post_type( 'testifybox', $args );
}
add_action( 'init','testifybox_custom_post_type' );


/**
 * register taxonomy
 */
function testifybox_new_taxonomy(){
    $labels = array(
        'name' => 'Testimonial Categories',
        'search_items'      => 'Search Categories' ,
        'all_items'         => 'All Categories' ,
        'parent_item'       => 'Parent Category' ,
        'parent_item_colon' => 'Parent Categories' ,
        'edit_item'         => 'Edit Category' ,
        'update_item'       => 'Update Category' ,
        'add_new_item'      => 'Add New Categories' ,
        'new_item_name'     => 'New Category Name' ,
        'menu_name'         => 'Categories',
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,

    );
    register_taxonomy( 'taxonomy-category', ['testifybox'], $args );
}
add_action( 'init', 'testifybox_new_taxonomy' );


/**
 * register client details metabox
 */
function testifybox_new_metabox(){
    add_meta_box( 'clientdetails_id', 'Client Details', 'testifybox_new_metabox_callback', 'testifybox' );
}
add_action( 'add_meta_boxes', 'testifybox_new_metabox' );

/**
 * add html form to metabox and get post meta
 * Callback for client details metabox
 * 
 * @param WP_Post $post Post Object. 
 */
function testifybox_new_metabox_callback( $post ){
 
    $value = get_post_meta( $post->ID, 'clientdetails', true );

    $fname = ! empty( $value['fname'] ) ? $value['fname'] : '';
    $email = ! empty( $value['email'] ) ? $value['email'] : '';
    $cname = ! empty( $value['cname'] ) ? $value['cname'] : '';
    $cwebsite = ! empty( $value['cwebsite']) ? $value['cwebsite'] : '';

    ?>
        <form method="POST" id="clientdetails" >
            <div class="form-group">
                <label for="name"><?php _e( 'Full Name', 'testifybox' ) ?></label>
                <input type="text" id="fname" name="fname" style="width:100%" value="<?php echo esc_attr(! empty( $value['fname'] ) ? $value['fname'] : ''); ?>" >
            </div>
            <br>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email"  id="email" name="email" style="width:100%" value="<?php echo esc_attr($email); ?>" >
            </div>
            <br>
            <div class="form-group">
                <label for="company name">Company Name</label>
                <input type="text"  id="cname" name="cname" style="width:100%" value="<?php echo esc_attr($cname); ?>" >
            </div>
            <br>
            <div class="form-group">
                <label for="company website">Company Website</label>
                <input type="text"  id="cwebsite" name="cwebsite" style="width:100%" value="<?php echo esc_attr($cwebsite); ?>" >
            </div>
            <br>
        </form>
    <?php
    
}

/**
 * save post meta
 */
function testifybox_save_postdata( $post_id ){

    if( (!empty($_POST['fname']) || !empty($_POST['email']) || !empty($_POST['cname']) || !empty($_POST['cwebsite'])) && (isset($_POST['fname']) || isset($_POST['email']) || isset($_POST['cname']) || isset($_POST['cwebsite'])) ){
        $postdata = [];
        $postdata['fname'] = sanitize_text_field($_POST['fname']);
        $postdata['email'] = sanitize_text_field($_POST['email']);
        $postdata['cname'] = sanitize_text_field($_POST['cname']);
        $postdata['cwebsite'] = sanitize_text_field($_POST['cwebsite']);

        update_post_meta( $post_id, 'clientdetails', $postdata );
            
    }
}
add_action( 'save_post', 'testifybox_save_postdata' );

/**
 * Shortcode to display client details
 */
function testifybox_shortcode($atts)
{
        $args = array(
            'post_type'      => 'testifybox',
            'posts_per_page' => 10,
            'publish_status' => 'Published',
        );
        $query = new WP_Query($args);
        ?>
        <div class="container">
            <div class="row">
                <div class="col">
                <table id="table-css">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Company Name</th>
                        <th>Company Website</th>
                    </tr>   
                        <?php if($query->have_posts()):
                            while($query->have_posts()):
                                $query->the_post() ;

                                $value = get_post_meta( get_the_ID(), 'clientdetails', true );

                                if(!empty($value)):
                                ?>
                                    <tr>
                                        <td><?php echo ( isset( $value['fname'] ) ? $value['fname'] : '' ); ?></td>
                                        <td><?php echo ( isset( $value['email'] ) ? $value['email'] : '' ); ?></td>
                                        <td><?php echo ( isset( $value['cname'] ) ? $value['cname'] : '' ); ?></td>
                                        <td><?php echo ( isset( $value['cwebsite'] ) ? $value['fname'] : '' ); ?></td>
                                    </tr>
                                <?php
                                endif;
                            endwhile;
                            wp_reset_postdata();
                            endif;
                        ?>              
                </table>
                </div>
            </div>
        </div>
        <?php
        $result = ob_get_clean();
        return $result;
 
}
add_shortcode('clientdetails_list','testifybox_shortcode');


/**
 * display meta key/fields in backend table
 */
function testifybox_column_page( $column ){

    $column['fname'] = 'Full Name';
    $column['email'] = 'Email Address';
    $column['cname'] = 'Company Name';
    $column['cwebsite'] = 'Company Website';

    return $column;
}
add_filter( 'manage_testifybox_posts_columns', 'testifybox_column_page' );

/**
 * display meta data/values
 */
function testifybox_column_value( $column_name, $post_ID ) {
    $custom_field_values = get_post_meta( $post_ID,'clientdetails', true );
    if(!empty( $custom_field_values )){
        if( $column_name == 'fname' ){
            echo $custom_field_values['fname'];
        }
        if( $column_name == 'email' ){
            echo $custom_field_values['email'];
        }
        if( $column_name == 'cname' ){
            echo $custom_field_values['cname'];
        }
        if( $column_name == 'cwebsite' ){
            echo $custom_field_values['cwebsite'];
        }
        if( $column_name == 'excerpt' ){
            echo get_the_excerpt($post_ID);
        }
    }
}
add_action('manage_testifybox_posts_custom_column','testifybox_column_value',10,2);
  


/**
 * shortcode to display all the testimonials list
 */

function testifybox_shortcode_lists(){
    $args = array(
        'post_type'      => 'testifybox',
        'posts_per_page' => 10,
        'publish_status' => 'publish',
    );

    $query = new WP_Query( $args );
    ob_start();
    ?>
    <div class="container">
        <div class="row">
    
    <?php
    $get_selected_option = get_option( 'save_value' );
    if ( $query->have_posts() ){
        while ( $query->have_posts() ){
            $query->the_post();
            $value = get_post_meta( get_the_ID(), 'clientdetails', true );
            $full_name = get_post_meta( get_the_ID(), 'full_name', true );
            $email_address = get_post_meta( get_the_ID(), 'email_address', true );
            $company_name = get_post_meta( get_the_ID(), 'company_name', true );
           
            $name = ( isset( $value['fname'] ) ? $value['fname'] : $full_name );
            $email = ( isset( $value['email'] ) ? $value['email'] : $email_address );
            $company_name = ( isset( $value['cname'] ) ? $value['cname'] : $company_name );
        
                if(!empty($value) || !empty($full_name) || !empty($email_address) || !empty($company_name) ){
                ?>
                    <div class="col-md-6 testimonial_css">
                        <div class="card bg-light card_css">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo get_the_title(); ?></h5><hr>
                                <?php echo get_the_content(); ?>
                                <div class="card-footer">
                                    <div class="row">
                                    <div class="col-md-4">
                                        <img src="https://img.lovepik.com/element/40128/7461.png_1200.png" alt="Avatar" class="img_avatar">
                                    </div>
                                    <div class="col-md-8">
                                        <small class="text-muted"><?php echo ( ($get_selected_option['name_check']) ? $name : '' ); ?>
                                                <?php echo ( ($get_selected_option['email_check']) ? $email : '' );  ?>
                                            <b> <?php echo ( ($get_selected_option['company_name_check']) ? $company_name : '' ); ?></b>
                                        </small>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                }
        }
    }else{
        echo 'No Testimonials Found.';
    }
    ?>

    </div>
    </div>
    <?php
    $result = ob_get_clean();
    return $result;
}
add_shortcode( 'all_testimonail_list','testifybox_shortcode_lists' );


/**
 * shortcode with arguments
 * to display testimonials according to category
 */

 function testifybox_category_display( $atts ){

	// override default attributes with user attributes
	$short_args = shortcode_atts(
		array(
            'categories' => '',
		), $atts
	);

    $args = array(
        'post_type' => 'testifybox',
        'post' => '6',
        'tax_query' => array(
            array(
            'taxonomy' => 'taxonomy-category',
            'field' => 'slug',
            'terms' => $short_args['categories'],
            ),
        ),
    );

    $query = new WP_Query( $args );

    ob_start();
    ?>
        <div class="container">
            <div class="row">
        
                <?php
                $get_selected_option = get_option( 'save_value' );
                if ( $query->have_posts() ){
                    while ( $query->have_posts() ){
                        $query->the_post();
                        $value = get_post_meta( get_the_ID(), 'clientdetails', true );
                        $full_name = get_post_meta( get_the_ID(), 'full_name', true );
                        $email_address = get_post_meta( get_the_ID(), 'email_address', true );
                        $company_name = get_post_meta( get_the_ID(), 'company_name', true );
                    
                        $name = ( isset( $value['fname'] ) ? $value['fname'] : $full_name );
                        $email = ( isset( $value['email'] ) ? $value['email'] : $email_address );
                        $company_name = ( isset( $value['cname'] ) ? $value['cname'] : $company_name );
                    
                            if(!empty($value) || !empty($full_name) || !empty($email_address) || !empty($company_name) ){
                            ?>
                                <div class="col-md-6 testimonial_css">
                                    <div class="card bg-light card_css">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo get_the_title(); ?></h5><hr>
                                            <?php echo get_the_content(); ?>
                                            <div class="card-footer">
                                                <div class="row">
                                                <div class="col-md-4">
                                                    <img src="https://img.lovepik.com/element/40128/7461.png_1200.png" alt="Avatar" class="img_avatar">
                                                </div>
                                                <div class="col-md-8">
                                                    <small class="text-muted"><?php echo ( ($get_selected_option['name_check']) ? $name : '' ); ?>
                                                        <?php echo ( ($get_selected_option['email_check']) ? $email : '' );  ?><br>
                                                        <b> <?php echo ( ($get_selected_option['company_name_check']) ? $company_name : '' ); ?></b>
                                                    </small>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php
                        }
                    }
                }else{
                    echo 'No Testimonials Found.';
                }
                ?>

            </div>
        </div>
    <?php

    $result = ob_get_clean();
    return $result;

 
 }
 add_shortcode( 'category_testinomial', 'testifybox_category_display'  );


 /**
  * display using slider
  */

function testifybox_slider(){
    $args = array(
        'post_type'      => 'testifybox',
        'posts_per_page' => 10,
        'publish_status' => 'publish',
    );

    $query = new WP_Query( $args );
   
    ob_start();
    ?>
        <div class="container">
        <div class="row">
    <?php

    include('templates/testifybox-slider-view.php');
    ?>
    </div>
    </div>
    <?php
    $result = ob_get_clean();
    return $result;
}
add_shortcode( 'testimonial_slider', 'testifybox_slider' );


/**
 * create submenu
 */

function testifybox_add_sub_menu(){
    
    add_submenu_page(
        'edit.php?post_type=testifybox', //parent slug
        'Easy Settings', //page title
        'Easy Settings', //menu title
        'manage_options', //roles and capability needed
        'easy_setting',  //menu slug
        'testifybox_menu_option',  //callback function
    );
}
add_action( 'admin_menu', 'testifybox_add_sub_menu' );

/**
 * callback function for sub menu
 * testifybox_menu_option
 */
function testifybox_menu_option(){
    $get_checked_values = get_option( 'save_value' );
    $name_checked = ( ($get_checked_values['name_check']) ? '1' : '0' );
    $email_checked = ( ($get_checked_values['email_check']) ? '1' : '0' );
    $company_name_checked = ( ($get_checked_values['company_name_check']) ? '1' : '0' );
    $rating_checked = ( ($get_checked_values['rating_check']) ? '1' : '0' );

    ?>
        <div class="container">
            <div class="row">
            <h1>Easy Enable/Disable</h1>
            <hr>
                <div class="col">
                <form method="POST">
                    <div class="form-check">
                        <input type="checkbox" id="name_check" name="name_check" <?php checked( 1, $name_checked );  ?> >
                        <label class="form-check-label" for="name_check">Enable client name in the view</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="email_check" name="email_check" <?php checked( 1, $email_checked );  ?> >
                        <label class="form-check-label" for="email_check">Enable client email in the view</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="company_name_check" name="company_name_check" <?php checked( 1, $company_name_checked );  ?> >
                        <label class="form-check-label" for="company_name_check">Enable company name in the view</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="rating_check" name="rating_check" <?php checked( 1, $rating_checked );  ?> >
                        <label class="form-check-label" for="rating_check">Enable rating in the view</label>
                    </div><br>
                    <?php wp_nonce_field( 'testimonial_setting_form', 'testimonial_setting_nonce' ); ?>

                    <input type="submit" name="save_settings" id="save_settings" value="Save Changes">
                </form>
                </div>
            </div>
        </div>
    <?php
    
}

/**
 * Save values to DB
 */
function testifybox_save_setings(){
    if(isset($_POST['save_settings']))
    {
        if( ! wp_verify_nonce( $_POST['testimonial_setting_nonce'], 'testimonial_setting_form' ) ) {
            return;
        };
        $postdata = [];
        $postdata['name_check'] = ( ( isset($_POST['name_check']) ) ? '1' : '0' );
        $postdata['email_check'] = ( ( isset($_POST['email_check']) ) ? '1' : '0' );
        $postdata['company_name_check'] = ( ( isset($_POST['company_name_check']) ) ? '1' : '0' );
        $postdata['rating_check'] = ( ( isset($_POST['rating_check']) ) ? '1' : '0' );

        update_option( 'save_value', $postdata );

    }
}
add_action( 'admin_init', 'testifybox_save_setings' );



/**
 * testimonial submission form
 *  allows website visitors to submit their testimonials using a form
 */
function testifybox_form(){

    ob_start();
    ?>
    <form method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" required >
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control" id="email_address" name="email_address" required >
        </div>
        <div class="mb-3">
            <label for="company_name" class="form-label">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name" required >
        </div>
        <div class="mb-3">
            <label for="company_website" class="form-label">Company Website</label>
            <input type="text" class="form-control" id="company_website" name="company_website" required >
        </div>
        <div class="mb-3">
            <label for="testimonial_heading">Testimonial Heading</label>
            <input type="text" class="form-control" id="testimonial_heading" name="testimonial_heading" required >
        </div>
        <div class="mb-3">
            <label for="testimonial">Testimonials</label>
            <textarea class="form-control" name="testimonial_textarea" id="testimonial_textarea" style="height: 100px" required ></textarea>
        </div>
        <div class="mb-3">
            <label for="rating">Rating</label>
            <div class="stars">
                <input class="star" type="radio" id="star1" name="rating" value="1" >
                <label class="star" for="star1"></label>
                <input class="star" type="radio" id="star2" name="rating" value="2" >
                <label class="star" for="star2"></label>
                <input class="star" type="radio" id="star3" name="rating" value="3" >
                <label class="star" for="star3"></label>
                <input class="star" type="radio" id="star4" name="rating" value="4" >
                <label class="star" for="star4"></label>
                <input class="star" type="radio" id="star5" name="rating" value="5" >
                <label class="star" for="star5"></label>
            </div>
        </div>

        <?php wp_nonce_field( 'testifybox_form', 'testimonial_nonce' ); ?>
       
        <input type="submit" name="testimonial_form_submit" id="testimonial_form_submit" value="Add New Testimonial">

    </form>
    <?php
    $result = ob_get_clean();
        
    return $result;
}
add_shortcode( 'add_testimonial', 'testifybox_form' );


/**
 * save testimonial form
 */
function testifybox_save_form(){
    
    if ( isset($_POST['testimonial_form_submit']) ) {

        if( ! wp_verify_nonce( $_POST['testimonial_nonce'], 'testifybox_form' ) ) {
            return;
        };
        $postdata = [];
        $postdata['full_name'] = sanitize_text_field($_POST['full_name']);
        $postdata['email_address'] = sanitize_text_field($_POST['email_address']);
        $postdata['company_name'] = sanitize_text_field($_POST['company_name']);
        $postdata['company_website'] = sanitize_text_field($_POST['company_website']);
        $postdata['rating'] = sanitize_text_field($_POST['rating']);

        $testimonial_heading = sanitize_text_field($_POST['testimonial_heading']);
        $testimonial_textarea = sanitize_textarea_field($_POST['testimonial_textarea']);
       
        $args = array(
            'post_title' => $testimonial_heading,
            'post_content' => $testimonial_textarea,
            'post_status' => 'publish',
            'post_type' => 'testifybox',
            'meta_input' => $postdata,
        );

        $save_testimonial = wp_insert_post($args);


        if ( $save_testimonial ) {
            wp_redirect( home_url( $_POST['_wp_http_referer'] ) );
            die;
        }

    }

}
add_action( 'init', 'testifybox_save_form' );



/**
 * display meta data/values
 */
function testifybox_new_column_value( $column_name, $post_ID ) {
    if ($column_name == 'fname') {
        $full_name = get_post_meta($post_ID, 'full_name', true);
        echo $full_name;
    }
    if ($column_name == 'email') {
        $email_address = get_post_meta($post_ID, 'email_address', true);
        echo $email_address;
    }
    if ($column_name == 'cname') {
        $company_name = get_post_meta($post_ID, 'company_name', true);
        echo $company_name;
    }
    if ($column_name == 'cwebsite') {
        $company_website = get_post_meta($post_ID, 'company_website', true);
        echo $company_website;
    }
}
add_action( 'manage_testifybox_posts_custom_column', 'testifybox_new_column_value', 10, 2 );
  

/**
 * stylesheet
 * bootstrap CDN
 * swiper slider cdn
 */
function testifybox_stylesheet() 
{
    //custom css
    wp_enqueue_style( 'myCSS', plugins_url( 'assets/css/testifybox-style.css', __FILE__ ) );

    //bootstrap cdn
    wp_enqueue_style( 'myplugin-style', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' );
    wp_enqueue_script( 'myplugin-script', 'https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', null, null, true );

    //jquery 
    wp_enqueue_script( 'ajax-script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', null, null, true );

    //swiper slider cdn
    wp_enqueue_style( 'swipercss-cdn', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css' );
    wp_enqueue_script( 'swiperjs_cdn', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', null, null, true );

    //custom js
    wp_enqueue_script( 'myScript', plugins_url( 'assets/js/testifybox-script.js', __FILE__ ), array(), null, true );

}

add_action( 'wp_enqueue_scripts', 'testifybox_stylesheet' );


/**
 * testimonial display option shortcode
 * grid view, slider view, list view
 */
function testifybox_display_option( $atts ){

	$display_args = shortcode_atts(
		array(
            'view' => '',
		), $atts
	);
  
    $args = array(
        'post_type' => 'testifybox',
        'post' => '5',
    );

    $query = new WP_Query( $args );
    $get_selected_option = get_option( 'save_value' );

    ob_start();
    ?>
        <div class="container">
            <div class="row">
                <?php
                if ( $display_args['view'] === 'slider' ) {
                    include('templates/testifybox-slider-view.php');
                } elseif ( $display_args['view'] === 'list' ) {
                    include('templates/testifybox-list-view.php');
                } elseif ( $display_args['view'] === 'grid' ) {
                    include('templates/testifybox-grid-view.php');
                } else {
                    include('templates/testifybox-grid-view.php');
                }
                ?>
            </div>
        </div>
    <?php

    $result = ob_get_clean();
    return $result;

}
add_shortcode( 'display_option', 'testifybox_display_option'  );