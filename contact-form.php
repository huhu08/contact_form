<?php
/**
 * Plugin Name: contact-form
 * Description: A simple contact form plugin.
 * Version: 1.0
 * Author: Huda
 */

// Shortcode function to display the contact form
function my_contact_form_enqueue_styles() {
    // Use plugins_url() to correctly reference the CSS file in your plugin directory
    global $post;
 
    // We want scripts to load only on single pages, posts or other CPT
    if ( ! is_singular() ) { return; }
    if ( ! $post ) { return; }

    if ( ! has_shortcode( $post->post_content, 'contact-form-shortcode' ) ) {
      return;
    }
       
    wp_enqueue_style('my-contact-form-styles', plugins_url('/styles.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'my_contact_form_enqueue_styles');

function my_contact_form_shortcode() {
    $form = '
    <form action="' . esc_url($_SERVER['REQUEST_URI']) . '" method="post">
       
        
        <p>Subject <br/>
        <input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . (isset($_POST["cf-subject"]) ? esc_attr($_POST["cf-subject"]) : '') . '" size="40" /></p>
        <p>Your Email (required) <br/>
        <input type="email" name="cf-email" value="' . (isset($_POST["cf-email"]) ? esc_attr($_POST["cf-email"]) : '') . '" size="40" /></p>
        <p>Your Message <br/>
        <textarea rows="10" cols="35" name="cf-message">' . (isset($_POST["cf-message"]) ? esc_attr($_POST["cf-message"]) : '') . '</textarea></p>
        <p><input type="submit" name="cf-submitted" value="Send"></p>
    </form>
    ';

    return $form;
}
add_shortcode('my_contact_form', 'my_contact_form_shortcode');
//shtort code to type in wordpress [my_contact_form_shortcode]
// Function to handle form submission
function my_contact_form_capture() {
    if (isset($_POST['cf-submitted'])) {
        // Sanitize form values
        $email   = sanitize_email($_POST['cf-email']);
        $subject = sanitize_text_field($_POST['cf-subject']);
        $message = esc_textarea($_POST['cf-message']);
        
        $email_subject = 'New Contact Form Submission: ' . $subject;

        // Process form, like sending an email
        wp_mail('huda@jasminecode.com', $subject, $message, array('Reply-To' => $email));
        
        // Output a message or redirect
        echo '<div>Form submitted successfully!</div>';
        echo '<div>Please make another fill if you wish</div>';
        echo 'testing if this shit works';
    }
    
    
}
add_action('wp_head', 'my_contact_form_capture');

