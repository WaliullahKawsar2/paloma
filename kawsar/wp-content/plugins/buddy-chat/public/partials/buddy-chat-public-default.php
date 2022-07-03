<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       none
 * @since      1.0.0
 *
 * @package    Buddy_Chat
 * @subpackage Buddy_Chat/public/partials
 */

?>

<?php
global $buddychat_options;
ob_start();
include 'buddies.php';
$buddies = ob_get_contents();
ob_end_clean();
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div id="buddy-chat-app" :class="[active_users_list]" v-cloak>
	<div id="buddy-chat-windows">
		<ul class="bpc-chat-windows-list">
			<li class="user-list_inactive" v-if="inactive_chat_windows.length > 0">
				<div class="dropd-group" :class="{active: show_inactive}">
					<a class="dropd-control" @click="toggle_inactive_list">
						<span class="dashicons dashicons-format-chat"></span>
					</a>
					<div class="dropd-menu">
						<a class="dropd-item" v-for="chat_window in inactive_chat_windows" @click="chat_with(chat_window, chat_window.title, chat_window.type)">{{chat_window.title}}</a>
					</div>
				</div>
			</li>
			<chat-window v-for="chat_window in active_chat_windows"
				:key="chat_window.id+chat_window.type"
				:buddy="chat_window"
				:users="buddies.users"
				:online_users="online_users"
				@close="close"
				@on_window_click="on_window_click"
			>
			</chat-window>
			<li class="chat-window__buddies" :class="{minimized: is_minimized && active_users_list != 'popped'}">
				<div v-show="active_users_list=='popped'" class="dropd-group dropd-tr popped-buddies" :class="{active: show_buddies}">
					<a class="dropd-control" @click.stop="show_popped_buddies">
						<span class="dashicons dashicons-format-chat"></span>
					</a>
					<div class="dropd-menu">
						<div id="buddy-chat-buddies__default">
							<div class="buddy-chat-buddies__container">
								<div class="header-container">
									<div class="window-title">
										<h5><?php echo ( isset( $buddychat_options['title'] ) && ! empty( $buddychat_options['title'] ) ) ? esc_html( $buddychat_options['title'] ) : esc_html__( 'Messenger', 'buddy-chat' ); ?></h5>
									</div>
									<div class="dropd-group" :class="{active: show_settings}">
										<a class="dropd-control" @click.stop="show_settings = !show_settings"><span class="dashicons dashicons-admin-generic"></span></a>
										<div class="dropd-menu">
											<a class="dropd-item" @click.stop="toggle_notification_sound">{{ is_notification_sound_off ? '<?php esc_html_e('Unmute', 'buddy-chat');?>' : '<?php esc_html_e('Mute', 'buddy-chat');?>' }}</a>
										</div>
									</div>
                </div>
                <div ref="popped-buddies" v-bar="{preventParentScroll: true}">
                  <?php echo $buddies; // WPCS: XSS ok. ?>
                </div>
							</div>
						</div>
					</div>
				</div>
				<div v-show="active_users_list!='popped'" id="buddy-chat-buddies__default" :class="{collapsed: is_collapsed}">
					<div class="buddy-chat-buddies__container">
						<div class="header-container" @click.stop="on_header_click">
							<div class="window-title">
								<h5><?php echo ( isset( $buddychat_options['title'] ) && ! empty( $buddychat_options['title'] ) ) ? esc_html( $buddychat_options['title'] ) : esc_html__( 'Messenger', 'buddy-chat' ); ?></h5>
							</div>
							<div class="dropd-group" :class="{active: show_settings}">
								<a class="dropd-control" @click.stop="show_settings = !show_settings"><span class="dashicons dashicons-admin-generic"></span></a>
								<div class="dropd-menu">
									<a class="dropd-item" @click.stop="toggle_notification_sound">{{ is_notification_sound_off ? '<?php esc_html_e('Unmute', 'buddy-chat');?>' : '<?php esc_html_e('Mute', 'buddy-chat');?>' }}</a>
								</div>
							</div>
            </div>
            <div ref="unpopped-buddies" v-bar="{preventParentScroll: true}">
              <?php echo $buddies; // WPCS: XSS ok. ?>
            </div>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
