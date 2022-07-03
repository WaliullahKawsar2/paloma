<?php

/**
 * Fired during plugin activation
 *
 * @link       none
 * @since      1.0.0
 *
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/includes
 * @author     Nono <none>
 */
class Buddy_Chat_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$message_table = $wpdb->prefix . 'bpc_message';
		$online_table = $wpdb->prefix . 'bpc_online';
		
		$charset_collate = $wpdb->get_charset_collate();
		$current_ver = '1.1.3';
		$installed_ver = get_option( "bpc_db_version" );

		$tables[] = "CREATE TABLE $message_table (
			id SERIAL,
			from_id bigint NOT NULL,
			to_id bigint,
			tog_id bigint,
			message text NOT NULL,
			message_type ENUM('file', 'text') DEFAULT 'text' NOT NULL,
			file_mime varchar(64),
			seen boolean DEFAULT FALSE,
			seen_by text,
			createdAt int(11) UNSIGNED,
			updatedAt int(11) UNSIGNED,
			PRIMARY KEY  (id),
			FULLTEXT (seen_by)
		) $charset_collate;";

		$tables[] = "CREATE TABLE $online_table (
			id SERIAL,
			user_id bigint NOT NULL,
			online_count int(11) DEFAULT 0,
			uAt int(11) UNSIGNED,
			PRIMARY KEY  (id),
			UNIQUE (user_id)
		) $charset_collate;";

		if($current_ver != $installed_ver) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $tables );
			update_option("bpc_db_version", $current_ver);
		}else if(!$installed_ver){
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $tables );
			add_option( 'bpc_db_version', $current_ver );
		}
	}

}
