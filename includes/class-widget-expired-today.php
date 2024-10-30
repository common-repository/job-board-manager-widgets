<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	

class WidgetExpiredToday extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'job_bm_widget_expired_today', 
			__('Job Board Manager - Expired Today', 'job-board-manager-widgets'),
			array( 'description' => __( 'Show Featured jobs.', 'job-board-manager-widgets' ), )
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$count = apply_filters( 'widget_title', $instance['count'] );

        wp_enqueue_style( 'job-bm-widgets' );


        echo $args['before_widget'];
		if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
		
		$wp_query = new WP_Query(
			array (
				'post_type' => 'job',
				'orderby' => 'Date',
				'order' => 'DESC',
				'posts_per_page' => $count,
				'meta_query' => array(
					array(
						'key'     => 'job_bm_expire_date',
						'value'   => date("Y-m-d"),
						'compare' => 'LIKE',
					),
				),
			) );
		
		echo '<ul class="job_bm_widget_expired_today">';
		
		if ( $wp_query->have_posts() ) :
			while ( $wp_query->have_posts() ) : $wp_query->the_post();	
		
			echo '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
			
			endwhile;
		endif;
		wp_reset_query();
		
		echo '</ul>';
		
		
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
		else $title = __( 'Job Expiring Today', 'job-board-manager-widgets' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
		
		if ( isset( $instance[ 'count' ] ) ) $count = $instance[ 'count' ];
		else $count = 5;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Job count:', 'job-board-manager-widgets' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
		</p>
		<?php 
		
		
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		return $instance;
	}
}