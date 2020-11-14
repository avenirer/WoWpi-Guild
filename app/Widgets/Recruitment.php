<?php

namespace WowpiGuild\Widgets;


use WowpiGuild\Config\Settings;

class Recruitment extends \WP_Widget {

	private $classes = array();
	private $strings = array();

	function __construct() {

		parent::__construct(
			'wowpi_guild_recruitment_widget',
			__('WoWpi Guild Recruitment Widget', 'wowpi-guild'),
			array(
				'description' => __( 'This widget allows you to create a recruitment zone in your sidebar', 'wowpi-guild' ),
			)
		);

		if(!get_option('wowpi_guild_guild')) {
			return false;
		}

		$playableClasses = Settings::getClasses();
		foreach($playableClasses as $class) {
			$class = Settings::getClass($class['id']);
			$class['specializations'] = Settings::getClassSpecs($class['id']);
			$this->classes[] = $class;
		}

		$this->strings = array(
			'-' => __('No need', 'wowpi-guild'),
			'needed' => __('Needed', 'wowpi-guild'),
			'important' => __('Dire need', 'wowpi-guild'),
		);
	}

	// Creating widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$intro = wp_kses_post($instance['intro']);
		$outro = wp_kses_post($instance['outro']);
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if(strlen(trim($intro)) > 0) {
			echo '<div class="wowpi-guild-widget-intro">';
			echo $intro;
			echo '</div>';
		}

		$neededClasses = array();
		foreach($this->classes as $class) {
			$classSpecs = array();
			$showClass = false;
			foreach($class['specializations'] as $spec) {
				$key = 'spec_'.$class['id'].'_'.$spec['id'];
				if($instance['show'] != 'specs' || ($instance['show'] == 'specs' && $instance[$key] != '-')) {
					$classSpecs[] = array(
						'spec_class' => 'spec-'.sanitize_title($spec['name']) . ($instance[$key] !== '-' ? ' '.$instance[$key] : ''),
						'spec_name' => $spec['name'],
						'spec_icon' => $spec['images']['icon'],
						'spec_title' => $this->strings[$instance[$key]].' - ' . $class['name'] . ' ' .$spec['name'],
					);
				}
				if($instance[$key] != '-') {
					$showClass = true;
				}
			}

			if($instance['show'] == 'all' || ($instance['show'] == 'classes' && $showClass == true) || ($instance['show'] == 'specs' && !empty($classSpecs))) {
				$neededClasses[] = array(
					'name' => $class['name'],
					'specs' => $classSpecs,
				);
			}

		}

		echo '<div class="wowpi-guild-widget-classes-container">';
		foreach($neededClasses as $class) {
			if($class['specs']) {
				if($instance['show'] != 'specs') {
					echo '<div class="wowpi-guild-widget-class class-' . sanitize_title( $class['name'] ) . '">';
					if($instance['class_names']) {
						echo '<h3 class="widget-class-name">'.$class['name'].'</h3>';
					}
				}

				foreach($class['specs'] as $spec) {
					echo '<div class="wowpi-guild-widget-spec '. $spec['spec_class'] .'" style="background-image: url(' . $spec['spec_icon'] . ');" title="'. $spec['spec_title'] . '">';
					echo '&nbsp;</div>';
				}

				if($instance['show'] != 'specs') {
					echo '</div>';
				}
			}
		}
		echo '</div>';

		if(strlen(trim($outro)) > 0) {
			echo '<div class="wowpi-guild-widget-outro">';
			echo $outro;
			echo '</div>';
		}

		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		$title = isset($instance[ 'title' ]) ? $instance[ 'title' ] : __( 'New title', 'wowpi-guild' );
		$intro = isset($instance['intro']) ? $instance['intro'] : '';
		$outro = isset($instance['outro']) ? $instance['outro'] : '';
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'wowpi-guild' ); ?>:</label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'intro' ); ?>"><?php _e( 'Intro text', 'wowpi-guild' ); ?>:</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'intro' ); ?>" name="<?php echo $this->get_field_name( 'intro' ); ?>"><?php echo esc_attr( $intro ); ?></textarea>
        </p>
		<?php
		if($this->classes) {
			foreach($this->classes as $class) {
				echo '<div class="wowpi-guild-recruitment-select">';
				echo '<p class="class-name">' .  $class['name'] . '</p>';
				if($class['specializations']) {
					echo '<ul>';
					foreach($class['specializations'] as $spec) {
						echo '<li style="background-image:url(' . $spec['images']['icon'] . ')">';
						$key = 'spec_' . $class['id'] . '_' . $spec['id'];
						echo '<strong>' .  $spec['name'] . '</strong>';
						echo '<select class="widefat" name="' . $this->get_field_name($key) . '"  id="' . $this->get_field_id($key) . '">';
						foreach($this->strings as $stringKey => $stringValue) {
							echo '<option value="' . $stringKey . '" ' . ( ( array_key_exists( $key, $instance ) && $instance[ $key ] == $stringKey ) ? 'selected="selected"' : '' ) . '>' . $stringValue . '</option>';
						}
						echo '</select>';
						echo '</li>';
					}
					echo '</ul>';
				}
				echo '</div>';
			}
		}
		?>
        <p>
			<?php
			if(empty($this->classes)) {
				echo '<p style="color: red; font-weight: bold;">You did not set up the WoWpi Guild plugin. Please do so before activating this widget.</p>';
			}
			?>
            <input class="widefat" type="checkbox" name="<?php echo $this->get_field_name('class_names');?>" id="<?php echo $this->get_field_id('class_names');?>"<?php echo ($instance['class_names'] ? ' checked="checked"' : '');?>>
            <label for="<?php echo $this->get_field_id('class_names');?>"><?php _e('With class names', 'wowpi-guild');?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('show');?>"><?php _e('Show', 'wowpi-guild');?></label>
            <select class="widefat" name="<?php echo $this->get_field_name('show');?>" id="<?php echo $this->get_field_id('show');?>">
                <option value="all"<?php echo (array_key_exists('show', $instance) && $instance['show'] == 'all' ? ' selected="selected"' : '') ;?>><?php _e('All classes and specs', 'wowpi-guild');?></option>
                <option value="classes"<?php echo (array_key_exists('show', $instance) && $instance['show'] == 'classes' ? ' selected="selected"' : '') ;?>><?php _e('Only needed classes', 'wowpi-guild');?></option>
                <option value="specs"<?php echo (array_key_exists('show', $instance) && $instance['show'] == 'specs' ? ' selected="selected"' : '') ;?>><?php _e('Only needed specializations', 'wowpi-guild');?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'outro' ); ?>"><?php _e( 'Outro text', 'wowpi-guild' ); ?>:</label>
            <textarea class="widefat" id="<?php echo $this->get_field_id( 'outro' ); ?>" name="<?php echo $this->get_field_name( 'outro' ); ?>"><?php echo esc_attr( $outro ); ?></textarea>
        </p>
		<?php
	}

// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_kses_post( $new_instance['title'] ) : '';
		$instance['intro'] = ( ! empty( $new_instance['intro'] ) ) ? wp_kses_post( $new_instance['intro'] ) : '';
		$instance['outro'] = ( ! empty( $new_instance['outro'] ) ) ? wp_kses_post( $new_instance['outro'] ) : '';
		$instance['class_names'] = $new_instance['class_names'] ? true : false;

		foreach($this->classes as $class)
		{
			foreach($class['specializations'] as $spec) {
				$key = $class['id'] . '_' . $spec['id'];
				$instance['spec_' . $key] = $new_instance['spec_' . $key];
			}
		}

		$instance['show'] = ( ! empty( $new_instance['show'] ) ) ? sanitize_text_field( $new_instance['show']) : 'all';

		return $instance;
	}

}