<?php
/**
 * Backend class for creating administrative interfaces and handling options.
 * Version 0.1
 */

/**
 * Copyright 2011  Eric Mann  (email : eric@eamann.com)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if ( !class_exists('JDM_Admin') ) :

class JDM_Admin {

	var $hook 		= '';
	var $pluginurl  = '';
	var $pluginversion = '';
	var $filename	= '';
	var $domain = '';
	var $longname	= '';
	var $shortname	= '';
	var $menuicon	= '';
	var $optionname = '';
	var $homepage	= '';
	var $feed		= '';
	var $accesslvl	= '';
	var $adminpages = array();

	function __construct( $pluginurl='', $version= '', $feed = '', $accesslvl = '', $adminpages = array() ) {
		$this->pluginurl=$pluginurl;
		$this->pluginversion=$version;
		$this->feed = $feed;
		$this->accesslvl=$accesslvl;
		$this->adminpages=$adminpages;
	}

	function add_adminmenu_icon( $hook ){
		if($hook==$this->hook)
			return $this->pluginurl . '/' . $this->menuicon;
		return $hook;
	}

	function config_page_styles(){
		global $pagenow;
		if( $pagenow == 'admin.php' && isset($_GET['page']) && in_array($_GET['page'], $this->adminpages) ){
			wp_enqueue_style('dashboard');
			wp_enqueue_style('thickbox');
			wp_enqueue_style('global');
			wp_enqueue_style('wp-admin');
			wp_enqueue_style($this->shortname . '-admin-css', $this->pluginurl . '/css/admin.css', $this->pluginversion );
		}
	}

	function register_network_settings_page(){
		add_menu_page($this->longname, $this->shortname, 'delete_users', $this->shortname . '_dashboard', array(&$this, 'network_config_page'), $this->pluginurl . '/images/network-admin.png');
	}

	function register_settings_page(){}

	function plugin_options_url(){}

	/**
	 * Create a Checkbox input field
	 */
	function checkbox($id, $label, $label_left = false, $option = '') {
		if ( $option == '') {
//			$options = get_wpseo_options();
			$option = !empty($option) ? $option : $this->currentoption;
		} else {
			if ( function_exists('is_network_admin') && is_network_admin() ) {
				$options = get_site_option($option);
			} else {
				$options = get_option($option);
			}
		}

		if (!isset($options[$id]))
			$options[$id] = false;

		$output_label = '<label for="'.$id.'">'.$label.'</label>';
		$output_input = '<input class="checkbox" type="checkbox" id="'.$id.'" name="'.$option.'['.$id.']"'. checked($options[$id],'on',false).'/> ';

		if( $label_left ) {
			$output = $output_label . $output_input;
		} else {
			$output = $output_input . $output_label;
		}
		return $output . '<br class="clear" />';
	}

	/**
	 * Create a Text input field
	 */
	function textinput($id, $label, $option = '') {
		if ( $option == '') {
//			$options = get_wpseo_options();
			$option = !empty($option) ? $option : $this->currentoption;
		} else {
			if ( function_exists('is_network_admin') && is_network_admin() ) {
				$options = get_site_option($option);
			} else {
				$options = get_option($option);
			}
		}

		$val = '';
		if (isset($options[$id]))
			$val = htmlspecialchars($options[$id]);

		return '<label class="textinput" for="'.$id.'">'.$label.':</label><input class="textinput" type="text" id="'.$id.'" name="'.$option.'['.$id.']" value="'.$val.'"/>' . '<br class="clear" />';
	}

	/**
	 * Create a Hidden input field
	 */
	function hiddeninput($id, $option = '') {
		if ( $option == '') {
//			$options = get_wpseo_options();
			$option = !empty($option) ? $option : $this->currentoption;
		} else {
			if ( function_exists('is_network_admin') && is_network_admin() ) {
				$options = get_site_option($option);
			} else {
				$options = get_option($option);
			}
		}

		$val = '';
		if (isset($options[$id]))
			$val = htmlspecialchars($options[$id]);
		return '<input class="hidden" type="hidden" id="'.$id.'" name="'.$option.'['.$id.']" value="'.$val.'"/>';
	}

	/**
	 * Create a Select Box
	 */
	function select($id, $label, $values, $option = '') {
		if ( $option == '') {
//			$options = get_wpseo_options();
			$option = !empty($option) ? $option : $this->currentoption;
		} else {
				if ( function_exists('is_network_admin') && is_network_admin() ) {
				$options = get_site_option($option);
			} else {
				$options = get_option($option);
			}
		}

		$output = '<label class="select" for="'.$id.'">'.$label.':</label>';
		$output .= '<select class="select" name="'.$option.'['.$id.']" id="'.$id.'">';

		foreach($values as $value => $label) {
			$sel = '';
			if (isset($options[$id]) && $options[$id] == $value)
				$sel = 'selected="selected" ';

			if (!empty($label))
				$output .= '<option '.$sel.'value="'.$value.'">'.$label.'</option>';
		}
		$output .= '</select>';
		return $output . '<br class="clear"/>';
	}

	/**
	 * Create a File upload
	 */
	function file_upload($id, $label, $option = '') {
		$option = !empty($option) ? $option : $this->currentoption;
//		$options = get_wpseo_options();

		$val = '';
		if (isset($options[$id]) && strtolower(gettype($options[$id])) == 'array') {
			$val = $options[$id]['url'];
		}
		$output = '<label class="select" for="'.$id.'">'.$label.':</label>';
		$output .= '<input type="file" value="' . $val . '" class="textinput" name="'.$option.'['.$id.']" id="'.$id.'"/>';

		// Need to save separate array items in hidden inputs, because empty file inputs type will be deleted by settings API.
		if(!empty($options[$id])) {
			$output .= '<input class="hidden" type="hidden" id="' . $id . '_file" name="wpseo_local[' . $id . '][file]" value="' . $options[$id]['file'] . '"/>';
			$output .= '<input class="hidden" type="hidden" id="' . $id . '_url" name="wpseo_local[' . $id . '][url]" value="' . $options[$id]['url'] . '"/>';
			$output .= '<input class="hidden" type="hidden" id="' . $id . '_type" name="wpseo_local[' . $id . '][type]" value="' . $options[$id]['type'] . '"/>';
		}
		$output .= '<br class="clear"/>';

		return $output;
	}

	/**
	 * Create a Radio input field
	 */
	function radio($id, $values, $label, $option = '') {
		if ( $option == '') {
//			$options = get_wpseo_options();
			$option = !empty($option) ? $option : $this->currentoption;
		} else {
			if ( function_exists('is_network_admin') && is_network_admin() ) {
				$options = get_site_option($option);
			} else {
				$options = get_option($option);
			}
		}

		if (!isset($options[$id]))
			$options[$id] = false;
			$output = '<br/><label class="select">'.$label.':</label>';
		foreach($values as $key => $value) {
			$output .= '<input type="radio" class="radio" id="'.$id.'-' . $key . '" name="'.$option.'['.$id.']" value="'. $key.'" ' . ($options[$id] == $key ? ' checked="checked"' : '') . ' /> <label class="radio" for="'.$id.'-' . $key . '">'.$value.'</label>';
		}
		$output .= '<br/>';

		return $output;
	}

	/**
	 * Create a hidden input field
	 */
	function hidden($id, $option = '') {
		if ( $option == '') {
//			$options = get_wpseo_options();
			$option = !empty($option) ? $option : $this->currentoption;
		} else {
			if ( function_exists('is_network_admin') && is_network_admin() ) {
				$options = get_site_option($option);
			} else {
				$options = get_option($option);
			}
		}

		if (!isset($options[$id]))
			$options[$id] = '';

		return '<input type="hidden" id="hidden_'.$id.'" name="'.$option.'['.$id.']" value="'.$options[$id].'"/>';
	}

	/**
	 * Create a postbox widget
	 */
	function postbox($id, $title, $content) {
	?>
		<div id="<?php echo $id; ?>" class="postbox">
			<div class="handlediv" title="Click to toggle"><br /></div>
			<h3 class="hndle"><span><?php echo $title; ?></span></h3>
			<div class="inside">
				<?php echo $content; ?>
			</div>
		</div>
	<?php
	}


	/**
	 * Create a form table from an array of rows
	 */
	function form_table($rows) {
		$content = '<table class="form-table">';
		foreach ($rows as $row) {
			$content .= '<tr><th valign="top" scrope="row">';
			if (isset($row['id']) && $row['id'] != '')
				$content .= '<label for="'.$row['id'].'">'.$row['label'].':</label>';
			else
				$content .= $row['label'];
			if (isset($row['desc']) && $row['desc'] != '')
				$content .= '<br/><small>'.$row['desc'].'</small>';
			$content .= '</th><td valign="top">';
			$content .= $row['content'];
			$content .= '</td></tr>';
		}
		$content .= '</table>';
		return $content;
	}

	/**
	 * Create a "plugin like" box.
	 */
	function plugin_like() {
		$content = '<p>'.__('Why not do any or all of the following:','ystplugin').'</p>';
		$content .= '<ul>';
		$content .= '<li><a href="'.$this->homepage.'">'.__('Link to it so other folks can find out about it.','ystplugin').'</a></li>';
		$content .= '<li><a href="http://wordpress.org/extend/plugins/'.$this->hook.'/">'.__('Give it a 5 star rating on WordPress.org.','ystplugin').'</a></li>';
		$content .= '<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=2017947">'.__('Donate a token of your appreciation.','ystplugin').'</a></li>';
		$content .= '</ul>';
		$this->postbox($this->hook.'like', 'Like this plugin?', $content);
	}

	/**
	 * Info box with link to the support forums.
	 */
	function plugin_support() {
		$content = '<p>'.__('If you have any problems with this plugin or good ideas for improvements or new features, please talk about them in the', $this->domain).' <a href="http://wordpress.org/tags/'.$this->hook.'">'.__("Support forums", $this->domain).'</a>.</p>';
		$this->postbox($this->hook.'support', 'Need support?', $content);
	}

	function text_limit( $text, $limit, $finish = '&hellip;') {
		if( strlen( $text ) > $limit ) {
	    	$text = substr( $text, 0, $limit );
			$text = substr( $text, 0, - ( strlen( strrchr( $text,' ') ) ) );
			$text .= $finish;
		}
		return $text;
	}
}

endif;