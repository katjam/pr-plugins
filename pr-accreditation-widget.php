<?php
/*
Plugin Name: PR accreditation block
Version: 0.0.0
Description: PR accreditation.
 */

add_action( 'widgets_init', 'pr_acc_widget_init' );

function pr_acc_widget_init() {
    register_widget( 'pr_acc_widget' );
}

class pr_acc_widget extends WP_Widget
{

    public function __construct()
    {
        $widget_details = array(
            'classname' => 'pr_acc_widget',
            'description' => 'PR Accreditation widget'
        );

        parent::__construct( 'pr_acc_widget', 'PR Accreditation Widget', $widget_details );

    }

    public function form( $instance ) {
        // Backend Form
        $title = '';
        if( !empty( $instance['title'] ) ) {
            $title = $instance['title'];
        }

        $text = '
        <div class="col-md-3">
          <div class="rics-logo">Regulated by RICS</div>
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
