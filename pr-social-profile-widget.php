<?php
/*
Plugin Name: PR social profiles block
Version: 0.0.0
Description: PR social profile buttons.
*/

add_action( 'widgets_init', 'pr_social_profile_widget_init' );

function pr_social_profile_widget_init() {
    register_widget( 'pr_social_profile_widget' );
}

class pr_social_profile_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'pr_social_profile_widget',
            'description' => 'PR Social Profile widget'
        );

        parent::__construct( 'pr_social_profile_widget', 'PR Social Profile Widget', $widget_details );

    }

    public function form( $instance ) {
        // Backend Form
        $title = '';
        if( !empty( $instance['title'] ) ) {
            $title = $instance['title'];
        }

        $text = '
        <div>
           <a href="https://twitter.com/" title="Follow Philips Rogers on Twitter" target="_blank"><i class="fa fa-twitter-square fa-3x" aria-hidden="true"></i></a>
           <a href="https://uk.linkedin.com/in/" title="View Philips Rogers\'s Linkedin Profile" target="_blank"><i class="fa fa-linkedin-square fa-3x" aria-hidden="true"></i></a>
           <a href="https://www.facebook.com/" title="Philips Rogers Facebook page" target="_blank"><i class="fa fa-facebook-square fa-3x" aria-hidden="true"></i></a>
       </div>
        ';
        if( !empty( $instance['text'] ) ) {
            $text = $instance['text'];
        }

        ?>

        <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_name( 'text' ); ?>"><?php _e( 'Text:' ); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" ><?php echo esc_attr( $text ); ?></textarea>
        </p>

        <div class='mfc-text'>

        </div>

        <?php

    }

    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }

    public function widget( $args, $instance ) {
        // Frontend display HTML
        echo $instance['text'];
    }

}
