<?php
namespace DummyPress;

/**
 * Class for handling CMB data
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class MetaboxValues{

	/**
	 * Assigns the proper testing data to a custom metabox.
	 *
	 * Swaps through the possible types of CMB2 supported fields and
	 * insert the appropriate data based on type & id.
	 * Some types are not yet supported due to low frequency of use.
	 *
	 * @see TestContent, add_post_meta
	 *
	 * @param int $post_id Single post ID.
	 * @param array $cmb custom metabox array from CMB2.
	 */
	public function get_values( $post_id, $cmb, $connected ){
		$value = '';

		// First check that our post ID & cmb array aren't empty
		if ( empty( $cmb ) || empty( $post_id ) || ! is_user_logged_in() ){
			return;
		}

		// Fetch the appropriate type of data and return
		switch( $cmb['type'] ){

			case 'text':
			case 'text_small':
			case 'text_medium':

				$value = $this->text( $cmb );

				break;

			case 'text_url':

				$value = $this->url( $cmb );

				break;

			case 'text_email' :
			case 'email':

				$value = $this->email( $cmb );

				break;

			case 'number' :
			case 'text_money':

				$value = $this->number( $cmb );

				break;

			case 'text_time':
			case 'time':

				$value = $this->time( $cmb );

				break;

			case 'select_timezone':

				$value = $this->timezone( $cmb );

				break;

			case 'text_date':
			case 'date':

				$value = $this->date( $cmb );

				break;

			case 'text_date_timestamp':
			case 'text_datetime_timestamp':
			case 'date_unix':
			case 'datetime_unix':

				$value = $this->timestamp( $cmb );

				break;

			// case 'text_datetime_timestamp_timezone': break;

			case 'test_colorpicker':

				$value = $this->color( $cmb );

				break;

			case 'textarea':
			case 'textarea_small':
			case 'textarea_code':

				$value = $this->textarea( $cmb );

				break;

			case 'select':
			case 'radio_inline':
			case 'radio':

				$value = $this->radio( $cmb );

				break;

			// case 'taxonomy_radio': break;
			// case 'taxonomy_select': break;
			// case 'taxonomy_multicheck': break;

			case 'checkbox':

				if ( isset( $cmb['source'] ) && 'acf' === $cmb['source'] ){
					$value = $this->multicheck( $cmb );
				} else {
					$value = $this->checkbox( $cmb );
				}

				break;

			case 'multicheck':

				$value = $this->multicheck( $cmb );

				break;

			case 'wysiwyg':

				$value = $this->wysiwyg( $cmb );

				break;

			case 'file':
			case 'image':

				$value = $this->file( $cmb, $post_id, $connected );

				break;

			// case 'file_list': break;

			case 'oembed':

				$value = $this->oembed( $cmb );

				break;

		}

		// Value must exist to attempt to insert
		if ( ! empty( $value ) && ! is_wp_error( $value ) ){

			$this->update_meta( $post_id, $value, $cmb );

		// If we're dealing with a WP Error object, just return the message for debugging
		} elseif ( is_wp_error( $value ) ){

			return $value->get_error_message();

		}

	} // end get_values


	/**
	 * Pulls a text string for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function text( $cmb ){

		// If phone is in the id, fetch a phone #
		if ( stripos( $cmb['id'], 'phone' ) ){
			$value = TestContent::phone();

		// If email is in the id, fetch an email address
		} elseif ( stripos( $cmb['id'], 'email' ) ){
			$value = TestContent::email();

		// If time is in the id, fetch a time string
		} elseif ( stripos( $cmb['id'], 'time' ) ){
			$value = TestContent::time();

		// Otherwise, just a random text string
		} else {
			$value = TestContent::title( rand( 10, 50 ) );
		}

		if ( 'acf' === $cmb['source'] && !empty( $cmb['extras']->chars ) ){
			$value = substr( $value, 0, $cmb['extras']->chars );
		}

		return $value;

	}


	/**
	 * Pulls a URL value CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function url( $cmb ){

		return TestContent::link();

	}


	/**
	 * Pulls an email address for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function email( $cmb ){

		return TestContent::email();;

	}


	/**
	 * Pulls a random valnumberue for CMB field.
	 *
	 * @param array $cmb Metabox data
	 * @return int cmb value
	 */
	private function number( $cmb ){

		$min = 1;
		$max = 10000000;

		if ( 'acf' == $cmb['source'] && !empty( $cmb['extras']->min ) ){
			$min = $cmb['extras']->min;
		}

		if ( 'acf' == $cmb['source'] && !empty( $cmb['extras']->max ) ){
			$max = $cmb['extras']->max;
		}

		return rand( $min, $max );

	}


	/**
	 * Pulls a time of day for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function time( $cmb ){

		return TestContent::time();

	}


	/**
	 * Pulls a timezone for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function timezone( $cmb ){

		return TestContent::timezone();

	}


	/**
	 * Pulls a date for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function date( $cmb ){

		return TestContent::date( 'm/d/Y' );

	}


	/**
	 * Pulls a timestamp for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function timestamp( $cmb ){

		return TestContent::date( 'U' );

	}


	/**
	 * Pulls a random hexadecimal color code for CMB field.
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function color( $cmb ){

		return '#' . str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );

	}


	/**
	 * Pulls a long text string for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function textarea( $cmb ){

		$value = TestContent::plain_text();

		if ( 'acf' == $cmb['source'] && !empty( $cmb['extras']->chars ) ){
			$value = substr( $value, 0,  $cmb['extras']->chars );
		}

		return $value;

	}


	/**
	 * Pulls a random radio field value for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function radio( $cmb ){

		// Grab a random item out of the array and return the key
		$new_val = array_slice( $cmb['options'], rand( 0, count( $cmb['options'] ) ), 1 );
		$value = key( $new_val );

		return $value;

	}


	/**
	 * Pulls a random checkbox field value for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function checkbox( $cmb ){
		$value = '';

		// 50/50 odds of being turned on
		if ( rand( 0, 1 ) == 1 ){
			$value = 'on';
		}

		return $value;

	}


	/**
	 * Pulls a random multicheck field value for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return array cmb value
	 */
	private function multicheck( $cmb ){

		$new_option = array();

		// Loop through each of our options
		foreach ( $cmb['options'] as $key => $value ){

			// 50/50 chance of being included
			if ( rand( 0, 1 ) ){
				$new_option[] = $key;
			}

		}

		return $new_option;

	}


	/**
	 * Pulls an HTML paragraph string for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function wysiwyg( $cmb ){

		return TestContent::paragraphs();

	}


	/**
	 * Pulls an image URL for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @param int $post_id Post ID
	 * @param bool $connected Whether we're connected to the Internets or not
	 * @return mixed string|object cmb value or WP_Error object
	 */
	private function file( $cmb, $post_id, $connected ){
		$value = '';

		if ( true === $connected ){
			$value = TestContent::image( $post_id );
		}

		return $value;

	}


	/**
	 * Pulls an Oembed URL for CMB field.
	 *
	 * @see TestContent
	 *
	 * @param array $cmb Metabox data
	 * @return string cmb value
	 */
	private function oembed( $cmb ){

		return TestContent::oembed();

	}


	/**
	 * Update the metabox with new data.
	 *
	 * @access private
	 *
	 * @see add_post_meta
	 *
	 * @param int $post_id Post ID.
	 * @param string $value Value to add into the database.
	 * @param array $cmb SMB data.
	 */
	private function update_meta( $post_id, $value, $cmb ){

		$type 	= $cmb['type'];
		$id		= $cmb['id'];
		$value = apply_filters( "tc_{$type}_metabox", $value );	// Filter by metabox type
		$value = apply_filters( "tc_{$id}_metabox", $value ); // Filter by metabox ID

		// Files must be treated separately - they use the attachment ID
		// & url of media for separate cmb values. (only in cmb1 & cmb2 though)
		if ( 'file'!== $cmb['type'] || ( 'file' === $cmb['type'] && 'cmb_hm' === $cmb['source'] ) ){
			add_post_meta( $post_id, $cmb['id'], $value, true );
		} else {
			add_post_meta( $post_id, $cmb['id'].'_id', $value, true );
			add_post_meta( $post_id, $cmb['id'], wp_get_attachment_url( $value ), true );
		}

		// Add extra, redundant meta. Because, why not have two rows for the price of one?
		if ( isset( $cmb['source'] ) && 'acf' === $cmb['source'] ){
			add_post_meta( $post_id, '_' . $cmb['id'], $cmb['key'], true );
		}

	}

}
