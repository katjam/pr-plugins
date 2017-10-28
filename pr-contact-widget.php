<?php
/*
Plugin Name: PR contact block
Version: 0.0.0
Description: PR contact with phones and email.
 */

add_action( 'widgets_init', 'pr_contact_widget_init' );

function pr_contact_widget_init() {
    register_widget( 'pr_contact_widget' );
}

class pr_contact_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'pr_contact_widget',
            'description' => 'WCR contact widget'
        );

        parent::__construct( 'pr_contact_widget', 'PR Contact Widget', $widget_details );

    }

    public function form( $instance ) {
        // Backend Form
        $title = '';
        if( !empty( $instance['title'] ) ) {
            $title = $instance['title'];
        }

        $text = '
     <div>
       <span class="mailto"><a href="mailto:pwiltshire@philipsrogers.co.uk" class="mailto"><i class="fa fa-envelope"></i></a> <a href="mailto:pwiltshire@philipsrogers.co.uk">pwiltshire@philipsrogers.co.uk</a></span>
        <div class="numbers">
           <span><i class="fa fa-phone"></i> 01208 812 812</span>
           <span><i class="fa fa-mobile"></i> 0790 700 7000</span>
       </div>
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
