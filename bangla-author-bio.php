<?php
/*
Plugin Name: Bangla Author Bio
Version: 1.1
Description: Adds a cool Author bio after every post automatically in Bangla.
Author: Arif nezami
Author URI: http://nezami.in
Plugin URI: http://wp-adm.in
License: GNU GPL v3 or later

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

Class bangla_Author_box_red {

	public static function init() {
		global $wp_version;
		// Bangla Author Bio requires Wordpress 2.9 or grater
		if (version_compare($wp_version, "2.9", "<")) {
			return false;
		}
		self::addFilters();
		self::addActions();
		load_plugin_textdomain('bangla-author-bio-div', false, dirname(plugin_basename(__FILE__ )));
		return true;
	}

	public static function filterContactMethods($contactmethods) {
		//add
		$contactmethods['twitter'] = 'Twitter';
		$contactmethods['facebook'] = 'Facebook';
		// remove
		unset($contactmethods['yim']);
		unset($contactmethods['aim']);
		return $contactmethods;
	}

	public static function filterContent($content = '') {
		if( is_single() ) {
			$author = array();
			$author['name'] = get_the_author();
			$author['twitter'] = get_the_author_meta('twitter');
			$author['facebook'] = get_the_author_meta('facebook');
			$author['posts'] = (int)get_the_author_posts();
			ob_start();
			?>
			<div id="bangla-author-bio-div">
				<div class="bangla-author-bio-div-info">
					<?php echo get_avatar( get_the_author_email(), '60' ); ?>
					<h4><?php printf( esc_attr__( 'পোষ্টটি লিখেছেন:  %s'), get_the_author() ); ?></h4>
					<p class="bangla-author-bio-div-text"><?php echo esc_attr(sprintf(__ngettext('এই ব্লগে এটাই %s এর প্রথম পোষ্ট', '%s এই ব্লগে %d টি পোষ্ট লিখেছেন ', $author['posts'], 'bangla-author-bio-div'), get_the_author_firstname().' '.get_the_author_lastname(), $author['posts'])); ?>.</p>
					<p class="bangla-author-bio-div-meta"><?php echo get_the_author_meta('description'); ?></p>
					<ul>
						<li class="first"><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
									<?php printf( __( '%s এর সকল পোষ্ট <span class="meta-nav">&rarr;</span>' ), get_the_author() ); ?>
								</a></li>
						<li><a href="<?php echo get_the_author_meta('url'); ?>" title="<?php echo esc_attr(sprintf(__('Read %s&#8217;s blog', 'bangla-author-bio-div'), $author['name'])); ?>"><?php echo __("ব্লগ"); ?></a></li>
						<?php if(!empty($author['twitter'])): ?>
						<li><a href="<?php echo $author['twitter']; ?>" title="<?php echo esc_attr(sprintf(__('Follow %s on Twitter', 'bangla-author-bio-div'), $author['name'])); ?>" rel="external">টুইটার পাতা</a></li>
						<?php endif; ?>
						<?php if(!empty($author['facebook'])): ?>
						<li><a href="<?php echo $author['facebook']; ?>" title="<?php echo esc_attr(sprintf(__('Be %s&#8217;s friend on Facebook', 'bangla-author-bio-div'), $author['name'])); ?>" rel="external">ফেসবুক একাউন্ট</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<?php
			$content .= ob_get_clean();
		}
		return $content;
	}

	public static function pluginCss() {
		if(file_exists(self::getPluginDir() . '/bangla-author-bio-div.css')) {
			wp_register_style('bangla-author-bio-div', self::getPluginUrl().'/bangla-author-bio-div.css');
			wp_enqueue_style('bangla-author-bio-div');
		}
	}

	private static function getPluginDir() {
		return WP_PLUGIN_DIR .'/'. dirname(plugin_basename(__FILE__));
	}

	private static function getPluginUrl() {
		return WP_PLUGIN_URL .'/'. dirname(plugin_basename(__FILE__));
	}

	private static function addFilters() {
		add_filter('user_contactmethods', array('bangla_Author_box_red', 'filterContactMethods'));
		add_filter('the_content', array('bangla_Author_box_red', 'filterContent'));
	}

	private static function addActions() {
		add_action('wp_print_styles', array('bangla_Author_box_red', 'pluginCss'));
	}
}

if(!bangla_Author_box_red::init()) {
	echo 'bangla-author-bio-div plugin requires WordPress 2.9 or higher. Please upgrade!';
}
