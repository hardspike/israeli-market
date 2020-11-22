<?php

class MENUINPOST_Help_Tabs {

	private $screen;

	public function __construct(WP_Screen $screen) {
		$this->screen = $screen;
	}

	public function set_help_tabs($type) {
		switch ($type) {
			case 'tools':
				$this->screen->add_help_tab(array(
					'id'=>'tools_shortcode_builder', 
					'title'=>__('Shortcode Builder', 'menu-in-post'), 
					'content'=>$this->content('shortcode_builder')));
			return;
		}
	}

	private function content($name) {
		$content = array();

		$content['shortcode_builder'] = '<p>' . __('Use the Shortcode Builder to create a menu shortcode. Copy-and-paste the shortcode into a Shortcode Block in a WordPress post or page. To add a Shortcode Block to post or page, in the WordPress Editor, go Add Block -> Widgets -> Shortcode.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Select Menu:</strong> Select the menu you want to use from the drop-down menu.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Include Container:</strong> By default, the menus created by Menu In Post are contained in div elements. Select &#34;no&#34; if you do not want a container.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Container ID:</strong> The ID that you would like the container div to be given. The ID must be unique within the HTML document, must contain at least one character, and must not contain any spaces.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Container Class(es):</strong> The class attribute(s) you want the container div to be given. Separate multiple classes with a space. Classes must begin with a letter A-Z or a-z, and can be followed by letters (A-Za-z), digits (0-9), hyphens (&#34;-&#34;), and underscores (&#34;_&#34;).') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Menu ID:</strong> The ID that you want assigned to the ul element of the menu list. The ID must be unique within the HTML document, must contain at least one character, and must not contain any spaces.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Menu Class(es):</strong> The class attribute(s) you want assigned to the ul element of the menu list. Separate multiple classes with a space. Classes must begin with a letter A-Z or a-z, and can be followed by letters (A-Za-z), digits (0-9), hyphens (&#34;-&#34;), and underscores (&#34;_&#34;). Not used for dropdown style menus.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Depth:</strong> Set the number of menu levels you would like to display. The default is &#34;all,&#34; which will display all top-level menus and submenus.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Style:</strong> Choose the type of menu, either an unordered list of links, or a dropdown box.') . '</p>';
		$content['shortcode_builder'] .= '<p>' . __('<strong>Placeholder Text:</strong> Optional for dropdown. Sets the text used for the first, placeholder option in a dropdown menu. Leave blank for the default, &#39;Select...&#39;.') . '</p>';

		if (!empty($content[$name])) {
			return $content[$name];
		}
	}
}
