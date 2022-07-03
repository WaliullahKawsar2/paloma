<div class="buddy-chat-buddies__content">
  <ul class="buddy-chat-nav-tabs">
  <?php if ( $buddychat_options['available-tabs']['all-members'] ) : ?>
    <li class="item">
      <a class="item-link" :class="{active: current_tab=='all-members'}" @click="switch_tab('all-members')"><?php esc_html_e( 'All users', 'buddy-chat' ); ?></a>
    </li>
  <?php endif; ?>
  <?php if ( bp_is_active( 'friends' ) && $buddychat_options['available-tabs']['friends'] ) : ?>
    <li class="item">
      <a class="item-link" :class="{active: current_tab=='friends'}" @click="switch_tab('friends')"><?php esc_html_e( 'Friends', 'buddy-chat' ); ?></a>
    </li>
  <?php endif; ?>
  <?php if ( bp_is_active( 'groups' ) && $buddychat_options['available-tabs']['groups'] ) : ?>
    <li class="item">
      <a class="item-link" :class="{active: current_tab=='groups'}" @click="switch_tab('groups')"><?php esc_html_e( 'Groups', 'buddy-chat' ); ?></a>
    </li>
  <?php endif; ?>
  </ul>
  <div class="bpc-tab-content" id="buddy-list">
    <?php if ( $buddychat_options['available-tabs']['all-members'] ) : ?>
    <div v-if="current_tab == 'all-members'">
      <div class="bpc-igroup">
        <div class="bpc-igroup-prepend">
          <span class="bpc-igroup-text"><span class="dashicons dashicons-search"></span></span>
        </div>
        <input v-model="filter.user" type="text" class="bpc-form-control" aria-label="<?php esc_attr_e('Find users', 'buddy-chat');?>" placeholder="<?php esc_attr_e('Find users', 'buddy-chat');?>">
      </div>
      <recycle-scroller ref="usersScroller" :items="users" :item-size="46" key-field="id" v-slot="{ item }" class="bpc-buddy-list members" page-mode>
        <div class="bpc-item" @click="chat_with(item, item.display_name, 'one2one')">
          <div class="avatar-container">
            <img :src="item.avatar" class="avatar" :class="{'has-unread': item.unread_msg_count > 0}" :alt="item.display_name">
            <span class="status" :class="{online: is_user_online(item.id)}"></span>
          </div>
          <div class="bpc-item-body">
            <div class="flex-r">
              <div class="buddy">
                <div class="chat-buddy anchor ellipsis">{{$decodeEntities(item.display_name)}}</div>
              </div>
            </div>
          </div>
        </div>
      </recycle-scroller>
      <div class="bpc-item" v-if="users.length < 1"><span class="bpc-notice">{{ messages.emptyUser }}</span></div>
      <div class="bpc-item" v-if="fetching.users"><span class="bpc-notice">{{ messages.loading }}...</span></div>
    </div>
    <?php endif; ?>
    <?php if ( bp_is_active( 'friends' ) && $buddychat_options['available-tabs']['friends'] ) : ?>
    <div v-if="current_tab == 'friends'">
      <div class="bpc-igroup">
        <div class="bpc-igroup-prepend">
          <span class="bpc-igroup-text"><span class="dashicons dashicons-search"></span></span>
        </div>
        <input v-model="filter.friend" type="text" class="bpc-form-control" aria-label="<?php esc_attr_e('Find friends', 'buddy-chat');?>" placeholder="<?php esc_attr_e('Find friends', 'buddy-chat');?>">
      </div>
      <recycle-scroller ref="friendsScroller" :items="friends" :item-size="46" key-field="id" v-slot="{ item }" class="bpc-buddy-list friends">
        <div class="bpc-item" @click="chat_with(item, item.display_name, 'one2one')">
          <div class="avatar-container">
            <img :src="item.avatar" class="avatar" :alt="item.display_name">
            <span class="status" :class="{online: is_user_online(item.id)}"></span>
          </div>
        <div class="bpc-item-body">
          <div class="flex-r">
            <div class="buddy">
              <div class="chat-buddy anchor ellipsis">{{$decodeEntities(item.display_name)}}</div>
            </div>
          </div>
        </div>
        </div>
      </recycle-scroller>
      <div class="bpc-item" v-if="friends.length < 1"><span class="bpc-notice">{{ messages.emptyFriend }}</span></div>
      <div class="bpc-item" v-if="fetching.friends"><span class="bpc-notice">{{ messages.loading }}...</span></div>
    </div>
    <?php endif; ?>
    <?php if ( bp_is_active( 'groups' ) && $buddychat_options['available-tabs']['groups'] ) : ?>
    <div v-if="current_tab == 'groups'">
      <div class="bpc-igroup">
        <div class="bpc-igroup-prepend">
          <span class="bpc-igroup-text"><span class="dashicons dashicons-search"></span></span>
        </div>
        <input v-model="filter.group" type="text" class="bpc-form-control" aria-label="<?php esc_attr_e('Find groups', 'buddy-chat');?>" placeholder="<?php esc_attr_e('Find groups', 'buddy-chat');?>">
      </div>
      <recycle-scroller :items="groups" :item-size="46" key-field="id" v-slot="{ item }" class="bpc-buddy-list groups">
        <div class="bpc-item" @click="chat_with(item, item.name, 'group')">
          <img :src="item.avatar" class="avatar" :alt="item.name">
          <div class="bpc-item-body">
            <div class="flex-r">
              <div class="buddy">
                <div class="chat-buddy anchor ellipsis">{{$decodeEntities(item.name)}}</div>
              </div>
            </div>
          </div>
        </div>
      </recycle-scroller>
      <div class="bpc-item" v-if="groups.length < 1"><span class="bpc-notice">{{ messages.emptyGroup }}</span></div>
      <div class="bpc-item" v-if="fetching.groups"><span class="bpc-notice">{{ messages.loading }}...</span></div>
    </div>
    <?php endif; ?>
  </div>
</div>
