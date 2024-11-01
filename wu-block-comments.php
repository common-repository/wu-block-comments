<?php
/*
Plugin Name: WU - Block comments
Plugin URI: http://wolf-u.li/4477/
Description: Blocks comments if problematic words are posted
Version: 1.2
Author: Uli Wolf
Author URI: http://wolf-u.li
*/
// Check if called by Wordpress, otherwise exit
! defined( 'ABSPATH' ) and exit;
/******************************************************************************\
|                              Check Section                                   |
\******************************************************************************/
function wubc_check( $approved , $commentdata ){
	$wu_comment_is_trash = false;
	$wubc_check[] = $commentdata['comment_author'];
	$wubc_check[] = $commentdata['comment_author_email'];
	$wubc_check[] = $commentdata['comment_author_url'];
	$wubc_check[] = $commentdata['comment_content'];
	$wu_needles = wubc_get_wordlist();
	foreach($wubc_check as $wu_block_comment) {
		foreach($wu_needles as $wu_needle) {
			$pos = stripos($wu_block_comment, $wu_needle);
		    if($pos !== false) {
	        	// string needle found in haystack
				// This comment is spam then!
				// Get the options to get the response and the counter
				$wubc_options = wubc_get_options();
				// Update the counter
				$wubc_options['counter']++;
				// Update the counter
				update_option( 'wu_block_comments_options', $wubc_options );
				// Now stop the user comment
	        	if ( defined('DOING_AJAX') ) {
					die(__('Your comment contains spam-words :(', 'wu-block-comments'));
				} else {
					wp_die(__('Your comment contains spam-words :(', 'wu-block-comments'));
				}
			}
		}
	}
	return $approved;
}
add_filter( 'pre_comment_approved' , 'wubc_check' , '99', 2 );

/******************************************************************************\
|                              General Functions                               |
\******************************************************************************/
// get the wu_wordlist from the wordpress options table
function wubc_get_wordlist() {
    $wubc_check_wordlist = get_option('wu_block_comments_wordlist');
    // if no values have been set, revert to the defaults
    if (!$wubc_check_wordlist) {
    	// Set default values
    	$wu_words = array('health insurance','pharmacy', 'roulette');
    } else {
    	// Explode the values to have an array
    	$wu_words = explode("\n",$wubc_check_wordlist);
    }
    
    // clean out any blank values
    foreach ($wu_words as $key => $value) {
        if (is_null($value) || $value=="") {
            unset($wu_words[$key]);
        } else {
        	// Trim all line carrige attempts
            $wu_words[$key] = trim(trim($wu_words[$key],"\n"),"\r");
        }
    }
    return $wu_words;
}

// Get the response in case of SPAM (with Spam-Counter)
function wubc_get_options() {
    $wubc_check_options = get_option('wu_block_comments_options');
    // if no values have been set, revert to the defaults
    if (!$wubc_check_options) {
    	// Set default values
    	$wubc_check_options['counter'] = 0;
    }
    return $wubc_check_options;
}
/******************************************************************************\
|                                  Admin Section                               |
\******************************************************************************/
// Register and define the settings
add_action('admin_init', 'wubc_admin_init');
function wubc_admin_init(){
	// Load the language file
	load_plugin_textdomain( 'wu-block-comments', false, basename(dirname(__FILE__)));
	// wubc_check_wordlist
	register_setting(
		'discussion', // settings page
		'wubc_check_wordlist', // option name
		'wubc_check_validate_wordlist' // validation callback
	);
	add_settings_field(
		'wu-block-comments', // id
		__('Comment Blocklist', 'wu-block-comments'), // setting title
		'wubc_check_setting_input', // display callback
		'discussion', // settings page
		'default' // settings section
	);
}

// add a settings link next to deactive / edit
function wu_add_settings_link( $links, $file ) {
 	if( $file == 'wu-block-comments/wu-block-comments.php' && function_exists( "admin_url" ) ) {
		$settings_link = '<a href="' . admin_url( 'options-discussion.php' ) . '">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}
add_filter('plugin_action_links', 'wu_add_settings_link', 10, 2);

// Display and fill the form field
function wubc_check_setting_input() {
	// get option 'wordlist' value from the database
	$wu_words = trim(implode("\n",wubc_get_wordlist()),"\n");
	// echo the field
	?>
<fieldset><legend class="screen-reader-text"><span><?php _e('Comment Blocklist', 'wu-block-comments'); ?></span></legend>
<p><label for="wubc_check_wordlist"><?php _e('When a comment contains any of these words in its content, name, URL, e-mail, or IP, it will be blocked by the plugin &#8220;<em>WU - Block Comments</em>&#8221; and will not be written to the database. One word or IP per line. It will match inside words, so &#8220;press&#8221; will match &#8220;WordPress&#8221;.', 'wu-block-comments') ?></label></p>
<div style='background-color:#FFFEEB;border:1px solid #CCCCCC;padding:12px'>
    <strong><?php _e('Thanks for using <a href="http://wolf-u.li/4477/"><em>WU - Block Comments</em></a> by <a href="http://wolf-u.li">Uli Wolf</a>!', 'wu-block-comments') ?></strong><br />
</div>
<p><textarea name="wubc_check_wordlist" rows="10" cols="50" id="wubc_check_wordlist" class="large-text code"><?php echo esc_textarea($wu_words); ?></textarea></p>
</fieldset>
	<?php
}

// Validate user input
function wubc_check_validate_wordlist( $input ) {
	$valid = array();
	$wu_words = explode("\n",$input);
    // clean out any blank values
    foreach ($wu_words as $key => $value) {
        if (is_null($value) || $value=="") {
            unset($wu_words[$key]);
        } else {
            $valid[$key] = esc_attr($wu_words[$key]);
        }
    }
    $valid = trim(trim(implode("\n",$valid),"\n"),"\r");;
	return $valid;
}

/******************************************************************************\
|                     Block Comments in right now section                      |
\******************************************************************************/
// WP 2.5+
function wubc_rightnow() {
	global $submenu;

	$wubc_options = wubc_get_options();
	if ( $wubc_options['counter'] > 0 ) {
		$intro = sprintf( _n(
			'<a href="%1$s">WUBC</a> has blocked %2$s spam comment on your site already. ',
			'<a href="%1$s">WUBC</a> has blocked %2$s spam comments on your site already. ',
			$wubc_options['counter'],
			'wu-block-comments'
		), 'http://wolf-u.li/4477', number_format_i18n( $wubc_options['counter'] ) );
	} else {
		$intro = sprintf( __('<a href="%1$s">WUBC</a> blocks spam from getting to your blog. ', 'wu-block-comments'), 'http://wolf-u.li/4477' );
	}
	echo "<p class='wubc-right-now'>$intro</p>\n";
}
	
add_action('rightnow_end', 'wubc_rightnow');

?>