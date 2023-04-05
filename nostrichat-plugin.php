<?php
/*
Plugin Name: Nostrichat Plugin
Description: This plugin inserts <a href="https://nostri.chat/" target="_blank" rel="noreferrer">Nostrichat</a> by <a href="https://snort.social/p/npub1l2vyh47mk2p0qlsku7hg0vn29faehy9hy34ygaclpn66ukqp3afqutajft"target="_blank" rel="noreferrer">PABLOF7z</a> in your posts or pages trought the shortcode [nostrichat]. The shortcode will create a chat room based on the URL where it is inserted.
Version: 1.0
Author: <a href="https://snort.social/p/npub1gzuushllat7pet0ccv9yuhygvc8ldeyhrgxuwg744dn5khnpk3gs3ea5ds" target="_blank" rel="noreferrer">@Gzuuus</a>
*/

function insert_nostrichat_shortcode($atts) {
	$atts = shortcode_atts( array(
		'chat-type' => 'GLOBAL',
		'chat-tags' => get_permalink(),
	), $atts );

	$type = esc_attr($atts['chat-type']);
	$tags = esc_attr($atts['chat-tags']);

	$shortcode = '<div id="nostrichat-widget"></div>';

	wp_enqueue_script( 'nostrichat', 'https://nostri.chat/public/bundle.js', array(), null, true );
	wp_enqueue_style( 'nostrichat', 'https://nostri.chat/public/bundle.css' );

	add_filter(
		'script_loader_tag',
		function( $tag, $handle ) use ( $type, $tags ) {
			if ( 'nostrichat' !== $handle ) {
				return $tag;
			}

			$pubkey = esc_attr(get_option('nostrichat_pubkey'));
			$relays = esc_attr(get_option('nostrichat_relays'));

			return str_replace(
				' src',
				' data-website-owner-pubkey="'.$pubkey.'" data-chat-type="'.$type.'" data-chat-tags="'.$tags.'" data-relays="'.$relays.'" src',
				$tag
			);
		},
		10,
		2
	);

	return $shortcode;
}
add_shortcode( 'nostrichat', 'insert_nostrichat_shortcode' );

function nostrichat_add_settings_page() {
    add_options_page( 'Nostrichat Settings', '🦩Nostrichat', 'manage_options', 'nostrichat', 'nostrichat_settings_page' );
}
add_action( 'admin_menu', 'nostrichat_add_settings_page' );

function nostrichat_settings_page() {
    $pubkey = get_option('nostrichat_pubkey');
    $relays = get_option('nostrichat_relays');
    ?>
    
    <div class="wrap">
        <h1>Nostrichat Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'nostrichat_options_group' ); ?>
            <?php do_settings_sections( 'nostrichat_options_group' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="nostrichat_pubkey">Nostr Public Key (HEX)</label><p>Convert <a href="https://nostrcheck.me/converter/"target="_blank" rel="noreferrer">npub to HEX</a> </th>
                    <td><input type="text" id="nostrichat_pubkey" name="nostrichat_pubkey" value="<?php echo esc_attr($pubkey); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="nostrichat_relays">Relays (can be a comma-separated list of relays)</label><p>Default relay list: <code>'wss://relay.f7z.io,wss://nos.lol,<br>wss://relay.nostr.info,wss://nostr-pub.wellorder.net,<br>wss://relay.current.fyi,wss://relay.nostr.band'</code></p><p>💡Setting your own relay list will override the default relay list.</p></th>
                    <td><input type="text" id="nostrichat_relays" name="nostrichat_relays" value="<?php echo esc_attr(isset($relays) ? $relays : 'wss://relay.f7z.io,wss://nos.lol,wss://relay.nostr.info,wss://nostr-pub.wellorder.net,wss://relay.current.fyi,wss://relay.nostr.band'); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <div class="nostrichat-usage">
            <h3>Basic usage</he>
            <p> To use this plugin, you must first configure your public key fields and the list of relays(optional). </p>
            <p> Once these are filled in, you can simply use the following shortcode on any wordpress page: <code>[nostrichat]</code>.</p>
            <p> You can also use the following argument within the shortcode to specify the chat type 'chat-type=" "', supported values are 'GLOBAL' and 'DM'.</p>
            <p>💡<i> If you add the shortcode without specifying this argument, the default chat type will be 'GLOBAL'.</i></p>
            <p>Example shortcode for dm: <code>[nostrichat chat-type="DM"]</code>.</p>
        </div>
        <div class="nostrichat-donation">
            <h3>How to support</h3>
            <p>If you liked this plugin and found it valuable, please consider supporting the work of the developers.</p>
            <p>Gzuuus (Wordpress plugin) <a href="lightning:gzuuus@getalby.com">DONATE</a></p>
            <p>PABLOF7z (Nostrichat) <a href="lightning:pablof7z@ln.tips">DONATE</a></p>
            <h4>You can also contribute by developing this plugin, opening issues to fix bugs or suggest improvements, opening PRs to commit new changes, etc.</h4>
            <a href="https://github.com/gzuuus/nostrichat-plugin" target="_blank" rel="noreferrer">Github repo</a>
        </div>
    </div>
    <?php
}

function nostrichat_register_settings() {
    register_setting( 'nostrichat_options_group', 'nostrichat_pubkey' );
    register_setting( 'nostrichat_options_group', 'nostrichat_relays' );
}
add_action( 'admin_init', 'nostrichat_register_settings' );

function nostrichat_settings_link($links) {
    $settings_link = '<a href="admin.php?page=nostrichat">' . __('Settings') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter("plugin_action_links_" . plugin_basename(__FILE__), "nostrichat_settings_link");

function nostrichat_uninstall_plugin() {
    delete_option('nostrichat_pubkey');
    delete_option('nostrichat_relays');
}
register_uninstall_hook( __FILE__, 'nostrichat_uninstall_plugin' );
