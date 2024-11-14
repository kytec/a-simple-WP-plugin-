<?php


/**
 * plugin name : simple contact form 
 * version : 1.0 
 * description: simple contact form
 * author: David Ewudiwa 
 * text domain: simple-contact-form 
 */

if( !defined('ABSPATH') )
{
    exit; // exit if access directly 
}


class simplecontactform {

public function _construct()
{
    // create custom post type 
    add_action ('init', array($this, 'create_custom_post_type'));
    // add assets (js, css, etc)
    add_action('wp_enqueue_scripts', array($this, 'load assets')); 
    // add shortcode 
    add_shortcode ('contact_form', array($this, 'load shortcode'));
    //load java script
    add_action('wp_footer', array($this, 'load script'));
    // resgister res api
    add_action('rest_api_init', array($this, 'register_rest_api'));
}

public function create_custom_post_type()
{
$args = array(

    'public' => true,
    'has_archive' => true,
    'supports' => array('title'),
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'capabilities'=> 'manage)options',
    'labels' => array(
        'name' => 'Simple Contact Form',
        'singular_name' => 'Simple Contact Form',
    ),
    'menu_icon' => 'dashicons-format-aside',
);
register_post_type('simple contact form', $args);

}

public function load_assets()
{
    wp_enqueue_style(
        'simple_contact_form,
     plugin_dir_url (__FILE__) . 'css/simple-contact-form.css', 
     array(),
     1, 
     'all'
    );

    wp_enqueue_script(
        'simple_contact_form',
        plugin_dir_url (__FILE__) . 'js/simple-contact-form.js', 
        array('jquery'),
        1, 
        true
    );


    }



    public fucntion load_shortcode() 

    {?>
    <div class="simple-contact-form">

    <h1> send us an email <h1?
    <p> please fill the below form </P>

    <form id ="simple-contact-form__form">

    <div class="form-group mb-2">
    <input name = "name" type="text" placeholder="name" class="form-control">
    </div>

    <div class="form-group mb-2">
    <input name = "email" type="text" placeholder="email" class="form-control>
    </div>

    <div class="form-group mb-2">
    <input name="phone" type="tel" placeholder="phone" class="form-control>
    </div>

    <div class="form-group mb-2">
    <textarea name="message" placeholder= " type your message"> </textarea>
    </div>

    <div class="form-group mb-2">
    <button class="btn btn-success btn-block w-100 "> submit message </button>
    </div>
    </form>
    </div>
    
    <?php }


    public function load_script()
    {?>
            <script>

            var nonce = '<?php echo wp_create_nonce('wp_rest'); ?>';

               (function ($) {
                         $('#simple-contact-form__form').submit(function (e) {
                                  event.preventDefault();
                                  var form = $($this).serialize();
                                  $.ajax({
                                      method ='POST',
                                      url = '<?php echo get_rest_url(null,'simple-contact-form/v1/send email'); ?>',
                                      header: {'x_wp_nonce': nonce},
                                      data = form,
                                      
                                   })
                          

                         });
               })(jquery)

           </script>
    <?php 

        
    }

    public function register_rest_api()
    {
        register_rest_route('simple-contact-form/v1', 'send email' array(
            'methods' => 'POST',
            'callback' => array($this, 'handle_contact_form')
        ));
    }
    public function handle_contact_form($data)
    {
        $headers = $data-get_headers();
        $params = $data-get_params();
        $nonce = $data-get_header['x_wp_nonce'][0];

        if(wp_verify_nonce($nonce, 'wp_rest'))
        {
            return wp_rest_response('message not sent',422);

        }
        $post_id = wp_insert_post(array{
            'post_type' => 'simple contact form',
            'post_title' => 'contact enquiry',
            'post_status' =>  'publish',

        });

        if (! $post_id) {
            return wp_rest_response('thank you for your email', 422);
        }   

    }  


}

new simplecontactform;