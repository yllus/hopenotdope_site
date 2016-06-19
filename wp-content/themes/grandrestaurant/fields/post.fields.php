<?php
function post_add_meta_boxes() {
    add_meta_box( 'add-event-information-metabox', 'Event Information', 'post_display_event_information_callback', 'post', 'normal', 'high' );
}
add_action( 'add_meta_boxes_post', 'post_add_meta_boxes' );

// The function that provides the form fields for the Event Information metabox.
function post_display_event_information_callback( $post ) {
    $values = get_post_custom( $post->ID );
    $event_price = isset( $values['event_price'] ) ? $values['event_price'][0] : '';
    $event_date = isset( $values['event_date'] ) ? $values['event_date'][0] : '';
    wp_nonce_field( 'my_display_event_meta_box_nonce', 'display_event_meta_box_nonce' );
    ?>
    <p>
        <label for="event_cost">Event Cost</label>
        <br>
        <input type="text" name="event_cost" id="event_cost" value="<?php echo esc_attr($event_price); ?>" style="width: 97%">
    </p>
    <p>
        <label for="event_date">Event Date</label>
        <br>
        <input type="text" name="event_date" id="event_date" value="<?php echo esc_attr($event_date); ?>" style="width: 97%">
    </p>
    <?php  
}

// Actually save the value of the two custom fields as post metadata.
function post_event_fields_save( $post_id ) {
    // Bail if we're doing an auto save.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // If our nonce isn't there, or we can't verify it, bail.
    if ( !isset( $_POST['display_event_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['display_event_meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // If our current user can't edit this post, bail.
    if ( !current_user_can( 'edit_posts' ) ) return;

    if ( isset( $_POST['event_cost'] ) ) {
        update_post_meta( $post_id, 'event_cost', $_POST['event_cost'] );
    }
    if ( isset( $_POST['event_date'] ) ) {
        update_post_meta( $post_id, 'event_date', $_POST['event_date'] );
    }
}
add_action( 'save_post', 'post_event_fields_save' );
?>
