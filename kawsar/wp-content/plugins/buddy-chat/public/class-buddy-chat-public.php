<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       none
 * @since      1.0.0
 *
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/public
 * @author     Nono <none>
 */
class Buddy_Chat_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $db;
	private $table_name;
	private $table_online;

	private $last_id = 0;
	private $online_timeout_buffer = 15;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		global $wpdb;

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->db         = $wpdb;
		$this->table_name = $wpdb->prefix . 'bpc_message';
		$this->table_online = $wpdb->prefix . 'bpc_online';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddy_Chat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddy_Chat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$chat_enabled = apply_filters('bpc_enable_chat', true);
		if ($chat_enabled) {
			wp_enqueue_style($this->plugin_name . '_main', plugin_dir_url(__FILE__) . 'css/buddychat.css', array(), $this->version, 'all');
			// remove_action('wp_head', 'print_emoji_detection_script', 7);
			// remZove_action('wp_print_styles', 'print_emoji_styles');
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddy_Chat_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddy_Chat_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$chat_enabled = apply_filters('bpc_enable_chat', true);
		if ($chat_enabled) {


			wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/buddy-chat-public.js', array(), $this->version, true);
			// wp_register_script($this->plugin_name, 'http://localhost:8080/dist/buddy-chat-public.js', array(), $this->version, true);
			global $buddychat_options;

			wp_localize_script(
				$this->plugin_name,
				'bpc_data',
				array(
					'title'                  => __('Messenger', 'buddy-chat'),
					'perpage'                => $buddychat_options['perpage'],
					'active_tab'             => $buddychat_options['active-tab'] ? $buddychat_options['active-tab'] : 'all-members',
					'messages'               => array(
						'write_your_message'	=> __('Write your message', 'buddy-chat'),
						'online'   => __('Online', 'buddy-chat'),
						'offline'   => __('Offline', 'buddy-chat'),
						'loading'   => __('Loading', 'buddy-chat'),
						'no_user'   => __('No user found', 'buddy-chat'),
						'no_friend' => __('No friend found', 'buddy-chat'),
						'no_group'  => __('No group found', 'buddy-chat'),
					),
					'emoji' => array(
						'search'   => __('Search', 'buddy-chat'),
						'notfound'   => __('No Emoji Found', 'buddy-chat'),
						'category_search'   => __('Search Results', 'buddy-chat'),
						'category_recent'   => __('Frequently Used', 'buddy-chat'),
						'category_smileys'   => __('Smileys & Emoticon', 'buddy-chat'),
						'category_people'   => __('People & Body', 'buddy-chat'),
						'category_nature'   => __('Animals & Nature', 'buddy-chat'),
						'category_foods'   => __('Food & Drink', 'buddy-chat'),
						'category_activity'   => __('Activity', 'buddy-chat'),
						'category_places'   => __('Travel & Places', 'buddy-chat'),
						'category_objects'   => __('Objects', 'buddy-chat'),
						'category_symbols'   => __('Symbols', 'buddy-chat'),
						'category_flags'   => __('Flags', 'buddy-chat')
					),
					'me'                     => get_current_user_id(),
					'image_dir_url'          => plugin_dir_url(__FILE__) . 'images',
					'notification_sound_url' => $buddychat_options['notification']['url'],
					'event_source_url'       => admin_url('admin-post.php?action=bpc_event_source'),
					'online_users_url'       => admin_url('admin-ajax.php?action=bpc_online_users'),
					'chat_buddies_url'       => admin_url('admin-ajax.php?action=bpc_chat_buddies'),
					'all_users_url'          => admin_url('admin-ajax.php?action=bpc_all_users'),
					'all_friends_url'        => admin_url('admin-ajax.php?action=bpc_all_friends'),
					'send_message_url'       => admin_url('admin-ajax.php?action=bpc_send_message'),
					'get_message_url'        => admin_url('admin-ajax.php?action=bpc_get_message'),
				)
			);
			wp_enqueue_script($this->plugin_name);
		}
	}

	public function bpc_template()
	{
		$chat_enabled = apply_filters('bpc_enable_chat', true);
		if ($chat_enabled) {
			$template = apply_filters('bpc_template', 'default');
			require_once dirname(__FILE__) . "/partials/buddy-chat-public-$template.php";
		}
	}

	public function bpc_chat_buddies()
	{
		$result            = array();
		$result['users']   = $this->get_buddypress_users_without_me();
		$result['friends'] = $this->get_my_friends();
		$result['groups']  = $this->get_my_groups();

		$this->_send_json_response($result);
	}
	public function bpc_all_users()
	{
		$query = $_GET['query'] ? $_GET['query'] : false;
		$page  = $_GET['page'] ? $_GET['page'] : 1;
		// var_dump($query, $page);
		$this->_send_json_response($this->get_buddypress_users_without_me($query, $page));
	}
	public function bpc_all_friends()
	{
		$query = $_GET['query'] ? $_GET['query'] : false;
		$page  = $_GET['page'] ? $_GET['page'] : 1;
		// var_dump($query, $page);
		$this->_send_json_response($this->get_my_friends($query, $page));
	}

	public function bpc_online_users()
	{
		global $wpdb;
		$user_id = get_current_user_id();
		$wpdb->query("INSERT INTO {$this->table_online} (user_id, online_count, uAt) VALUES ({$user_id}, 0, UNIX_TIMESTAMP()) ON DUPLICATE KEY UPDATE uAt = UNIX_TIMESTAMP()");
		$query_results = $wpdb->get_results("SELECT user_id FROM {$this->table_online} WHERE uAt > " . (time() - $this->online_timeout_buffer));

		$results = array();
		foreach ($query_results as $userData) {

			$results[] = (int)$userData->user_id;
		}
		$this->_send_json_response($results);
	}

	public function bpc_send_message()
	{

		switch ($_POST['type']) {
			case 'one2one':
				$this->db->insert(
					$this->table_name,
					array(
						'to_id'        => $_POST['to_id'],
						'from_id'      => get_current_user_id(),
						'message'      => $_POST['message'],
						'message_type' => 'text',
						'createdAt'    => time(),
					)
				);
				break;
			case 'group':
				$this->db->insert(
					$this->table_name,
					array(
						'tog_id'       => $_POST['to_id'],
						'from_id'      => get_current_user_id(),
						'message'      => $_POST['message'],
						'message_type' => 'text',
						'createdAt'    => time(),
					)
				);
				break;
		}
		$_POST['createdAt'] = time();
		$this->_send_json_response($_POST);
	}

	public function bpc_get_message()
	{
		if (!isset($_GET['page']) || $_GET['page'] < 1) {
			$page = 1;
		} else {
			$page = $_GET['page'];
		}
		$limit = (($page - 1) * 10) . ', 10';
		$me    = get_current_user_id();
		$buddy = $_GET['buddy'];

		if (isset($_GET['chat_type'])) {
			$chat_type = $_GET['chat_type'];
		} else {
			$chat_type = 'one2one';
		}

		$skip_ids = "''";
		if (isset($_GET['skip_ids']) && $_GET['skip_ids']) {
			$skip_ids = $_GET['skip_ids'];
		}

		switch ($chat_type) {
			case 'group':
				$ms = $this->db->get_results("SELECT * FROM {$this->table_name} WHERE tog_id=$buddy AND id NOT IN ($skip_ids) ORDER BY createdAt DESC LIMIT $limit");
				foreach ($ms as $m) {
					if ($m->to_id) {
						$m->to_avatar = bp_core_fetch_avatar(
							array(
								'item_id' => $m->to_id,
								'object'  => 'user',
								'type'    => 'thumb',
								'html'    => false,
							)
						);
					}
					$m->from_avatar = bp_core_fetch_avatar(
						array(
							'item_id' => $m->from_id,
							'object'  => 'user',
							'type'    => 'thumb',
							'html'    => false,
						)
					);
				}
				$this->_send_json_response($ms);
				break;

			default:
				// echo "SELECT * FROM {$this->table_name} WHERE ((from_id=$me AND to_id=$buddy) OR (from_id=$buddy AND to_id=$me)) AND id NOT IN ($skip_ids) ORDER BY createdAt DESC LIMIT $limit";
				$ms = $this->db->get_results("SELECT * FROM {$this->table_name} WHERE ((from_id=$me AND to_id=$buddy) OR (from_id=$buddy AND to_id=$me)) AND id NOT IN ($skip_ids) ORDER BY createdAt DESC LIMIT $limit");
				foreach ($ms as $m) {
					if ($m->to_id) {
						$m->to_avatar = bp_core_fetch_avatar(
							array(
								'item_id' => $m->to_id,
								'object'  => 'user',
								'type'    => 'thumb',
								'html'    => false,
							)
						);
					}
					$m->from_avatar = bp_core_fetch_avatar(
						array(
							'item_id' => $m->from_id,
							'object'  => 'user',
							'type'    => 'thumb',
							'html'    => false,
						)
					);
				}
				$this->_send_json_response($ms);
				break;
		}
	}

	public function bpc_event_source()
	{

		/* make sure the script does not timeout */
		set_time_limit(0);
		ini_set('max_execution_time', '0');
		session_write_close();

		global $buddychat_options;

		$connect_time = time();
		$user_id      = get_current_user_id();
		$groups       = $this->get_my_groups();
		$gids         = array();
		foreach ($groups as $group) {
			$gids[] = $group['id'];
		}
		$groups = implode(',', $gids);
		if (!$groups) {
			$groups = "''";
		}

		$last_event_id = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : False);
		if ($last_event_id == 0) {
			$last_event_id = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : False);
		}
		if($last_event_id) {
			// error_log("user_id: {$user_id} last_ev_id: {$last_event_id}", 4);
			$this->last_id = $last_event_id;
		}

		while (true) {
			$current_time = time();
			//$this->sendEventSourceData($user_id, $groups, $buddychat_options['forced-flush']);
			$updates = $this->get_message_updates($user_id, $groups);
			if($updates) {
				$resArr = array();
				foreach ($updates as $key => $data) {
					$message = stripslashes($data->message);
					$res = array(
						'id' => $data->id,
						'message' => $message,
						'message_type' => $data->message_type,
						'file_mime' => $data->file_mime
					);
					if ($data->to_id) {
						$res['to_id'] = $data->to_id;
						$res['to_avatar'] = bp_core_fetch_avatar(
							array(
								'item_id' => $data->to_id,
								'object'  => 'user',
								'type'    => 'thumb',
								'html'    => false,
							)
						);
					}
					if ($data->tog_id) {
						$res['tog_id'] = $data->tog_id;
						$res['group_name'] = groups_get_group(array('group_id' => $data->tog_id))->name;
						$res['group_avatar'] = bp_core_fetch_avatar(
							array(
								'item_id' => $data->tog_id,
								'object'  => 'group',
								'type'    => 'thumb',
								'html'    => false,
							)
						);
					}
					$res['from_id'] = $data->from_id;
					$res['from_name'] = get_userdata($data->from_id)->display_name;
					$res['from_avatar'] = bp_core_fetch_avatar(
						array(
							'item_id' => $data->from_id,
							'object'  => 'user',
							'type'    => 'thumb',
							'html'    => false,
						)
					);
					$res['createdAt'] = $data->createdAt;
					
					$resArr[] = $res;
				}
				wp_send_json_success($resArr);
				die();
			}
			if (($current_time - $connect_time) > 30) {
				wp_send_json_success(null, 204);
				die();
			}
			sleep(1);
		}
	}

	private function sendEventSourceData($user_id, $groups, $forcedFlush = false)
	{
		$updated_datas = $this->get_message_updates($user_id, $groups);
		// return;
		if ($updated_datas) {
			foreach ($updated_datas as $key => $data) {
				$message = json_encode(stripslashes($data->message));
				echo "id: {$data->id}\n";
				echo "data: {\n";
				echo "data: \"id\": \"{$data->id}\",\n";
				echo "data: \"message\": {$message},\n";
				echo "data: \"message_type\": \"{$data->message_type}\",\n";
				echo "data: \"file_mime\": \"{$data->file_mime}\",\n";
				if ($data->to_id) {
					echo "data: \"to_id\": {$data->to_id},\n";
					echo 'data: "to_avatar": "' . bp_core_fetch_avatar(
						array(
							'item_id' => $data->to_id,
							'object'  => 'user',
							'type'    => 'thumb',
							'html'    => false,
						)
					) . "\",\n";
				}
				if ($data->tog_id) {
					echo "data: \"tog_id\": {$data->tog_id},\n";
					echo 'data: "group_name": "' . groups_get_group(array('group_id' => $data->tog_id))->name . "\",\n";
					echo 'data: "group_avatar": "' . bp_core_fetch_avatar(
						array(
							'item_id' => $data->tog_id,
							'object'  => 'group',
							'type'    => 'thumb',
							'html'    => false,
						)
					) . "\",\n";
				}
				echo "data: \"from_id\": {$data->from_id},\n";
				echo 'data: "from_name": "' . get_userdata($data->from_id)->display_name . "\",\n";
				echo 'data: "from_avatar": "' . bp_core_fetch_avatar(
					array(
						'item_id' => $data->from_id,
						'object'  => 'user',
						'type'    => 'thumb',
						'html'    => false,
					)
				) . "\",\n";
				echo "data: \"createdAt\": \"{$data->createdAt}\"\n";
				echo "data: }\n\n";
				$this->forcedFlush($forcedFlush);
				//ob_flush();
				//flush();
			}
		} else {
			echo "event: ping\n",
				"data: live\n\n";
			$this->forcedFlush($forcedFlush);
			//ob_flush();
			//flush();
		}
	}

	private function forcedFlush($forced = true)
	{
		//@ini_set('zlib.output_compression', 'Off');
		//header('X-Accel-Buffering: no');

		ob_implicit_flush(true);
		$levels = ob_get_level();
		for ($i = 0; $i < $levels; $i++) {
			ob_end_flush();
		}
		flush();

		if ($forced) {
			// generate a random whitespace string with entropy to avoid gzip reduce
			static $chars = array(" ", "\r\n", "\n", "\t");
			$stuff = '';
			$m = count($chars) - 1;
			for ($i = 0; $i < (1024 * 5); $i++) { // 4KiB is minimum
				$stuff .= $chars[rand(0, $m)];
			}
			echo "$stuff\n";
		}
	}

	private function get_message_updates($user_id, $group_ids)
	{
		// echo "SELECT * from {$this->table_name} WHERE (to_id=$user_id OR (tog_id IN ($group_ids) AND from_id != $user_id)) AND (FIND_IN_SET($user_id, seen_by) < 1 OR FIND_IN_SET($user_id, seen_by) IS NULL) ORDER BY createdAt DESC";
		// return;
		if ($this->last_id) {
			$results = $this->db->get_results("SELECT * from {$this->table_name} WHERE (to_id=$user_id OR (tog_id IN ($group_ids) AND from_id != $user_id)) AND id > {$this->last_id} ORDER BY id DESC");
		} else {
			$results = $this->db->get_results("SELECT * from {$this->table_name} WHERE (to_id=$user_id OR (tog_id IN ($group_ids) AND from_id != $user_id)) AND (FIND_IN_SET($user_id, seen_by) < 1 OR FIND_IN_SET($user_id, seen_by) IS NULL) ORDER BY id DESC");
		}
		foreach ($results as $res) {
			if ($res->id > $this->last_id) {
				$this->last_id = $res->id;
			}
			$seen_by = $res->seen_by ? explode(",", $res->seen_by) : array();
			$seen_by[] = $user_id;
			$seen_by = array_unique($seen_by);

			$this->db->update(
				$this->table_name,
				array(
					'seen_by' => implode(",", $seen_by),
				),
				array(
					'id' => $res->id,
				)
			);
		}

		return $results;
	}



	private function get_buddypress_users_without_me($query = false, $page = 1)
	{
		global $buddychat_options;

		if ($buddychat_options['available-tabs']['all-members']) {
			add_filter('bp_user_query_uid_clauses', array($this, 'filter_online_first'));
			$userQuery = new BP_User_Query(
				array(
					'type'		   => '',
					'per_page'     => $buddychat_options['perpage'],
					'page'         => $page,
					'search_terms' => $query,
					'exclude'      => get_current_user_id(),
				)
			);
			remove_filter('bp_user_query_uid_clauses', array($this, 'filter_online_first'));

			$results = array();
			foreach ($userQuery->results as $userData) {
				// print_r($userData);
				$user                 = array();
				$user['id']           = $userData->id;
				$user['display_name'] = $userData->display_name;
				$user['avatar']       = bp_core_fetch_avatar(
					array(
						'item_id' => $userData->id,
						'object'  => 'user',
						'type'    => 'thumb',
						'html'    => false,
					)
				);

				$results[] = $user;
			}

			return array(
				'users' => $results,
				'count' => (int) $userQuery->total_users,
			);
		} else {
			return array(
				'users' => array(),
				'count' => 0,
			);
		}
	}

	private function get_my_friends($query = false, $page = 1)
	{
		global $buddychat_options;

		if (bp_is_active('friends') && $buddychat_options['available-tabs']['friends']) {

			add_filter('bp_user_query_uid_clauses', array($this, 'filter_online_first'));
			$userQuery = new BP_User_Query(
				array(
					'type'         => '',
					'user_id'     => get_current_user_id(),
					'search_terms' => $query,
					'per_page'     => $buddychat_options['perpage'],
					'page'         => $page,
				)
			);
			remove_filter('bp_user_query_uid_clauses', array($this, 'filter_online_first'));

			$results = array();
			foreach ($userQuery->results as $userData) {
				// print_r($userData);
				$user                 = array();
				$user['id']           = $userData->id;
				$user['display_name'] = $userData->display_name;
				$user['avatar']       = bp_core_fetch_avatar(
					array(
						'item_id' => $userData->id,
						'object'  => 'user',
						'type'    => 'thumb',
						'html'    => false,
					)
				);

				$results[] = $user;
			}

			return array(
				'friends' => $results,
				'count'   => (int) $userQuery->total_users,
			);
		} else {
			return array(
				'friends' => array(),
				'count'   => 0,
			);
		}
	}
	private function get_my_groups()
	{
		global $buddychat_options;
		if (bp_is_active('groups') && $buddychat_options['available-tabs']['groups']) {
			$groups = groups_get_groups(
				array(
					'user_id'  => get_current_user_id(),
					'per_page' => -1,
				)
			)['groups'];

			$result = array();

			foreach ($groups as $group) {
				$gp           = array();
				$gp['id']     = $group->id;
				$gp['name']   = $group->name;
				$gp['avatar'] = bp_core_fetch_avatar(
					array(
						'item_id' => $group->id,
						'object'  => 'group',
						'type'    => 'thumb',
						'html'    => false,
					)
				);

				$result[] = $gp;
			}
			return $result;
		} else {
			return array();
		}
	}

	private function _send_json_response($data)
	{
		$data = stripslashes_deep($data);
		echo json_encode($data);
		die();
	}

	public function filter_online_first($sql)
	{
		$sql['select'] .= " LEFT JOIN {$this->table_online} u1 ON (u1.user_id = u.ID AND u1.uAt > " . (time() - $this->online_timeout_buffer) . ")";
		$sql['orderby'] = "ORDER BY u1.uAt DESC, u.display_name ASC";
		return $sql;
	}
}
if (!function_exists('stripslashes_deep')) {
	function stripslashes_deep($value)
	{
		$value = is_array($value) ?
			array_map('stripslashes_deep', $value) :
			stripslashes($value);

		return $value;
	}
}
