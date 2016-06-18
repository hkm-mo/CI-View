<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '/View_menu.php';

class View {
	protected $js_files = array();
	protected $css_files = array();
	protected $placement = array();
	protected $meta = array();
	protected $header_links = array();
	protected $vars = array();
	protected $layout = null;
	protected $js_debug;
	protected $css_debug;
	protected $minify_js_ext;
	protected $minify_css_ext;
	protected $output_collapse;
	protected $magic_replace;
	protected $title_separator;
	protected $base_path_symbol;
	protected $keyword = array();
	protected $title = array();
	protected $menu = NULL;
	
	function __construct($config) 
	{
		$this->load->helper('url');
		$this->js_debug = $config['js_debug'];
		$this->css_debug = $config['css_debug'];
		$this->output_collapse = $config['output_collapse'];
		$this->magic_replace = $config['magic_replace'];
		$this->title_separator = $config['title_separator'];
		$this->minify_js_ext = $config['minify_js_ext'];
		$this->minify_css_ext = $config['minify_css_ext'];
		$this->base_path_symbol = $config['base_path_symbol'];
	}
	
	public function render($view = NULL, $vars = array())
	{
		if ($view === NULL) {
			$router = $this->router;
			$view = strtolower($router->class) .'/'. $router->method;
			if ( $router->directory !== NULL ) {
				$view = strtolower($router->directory) . $view;
			}
		}
		
		$content = $this->load($view, $vars, TRUE);
		
		if ($this->layout) {
			do
			{
				$layout = $this->layout;
				$this->layout = null;
				$layout_content = $this->load($layout['name'], $layout['vars'], true);
				$this->placement['body'] = $layout_content;
			}
			while ($this->layout);
			
			$body = $content;
			$content = $this->placement['body'];
			$this->placement['body'] = $body;
		}
		
		foreach ($this->placement as $replace_key => $replace_value) {
			//Assure all callable is array
			if(is_array($replace_value)) {
				if(isset($replace_value['params'])) {
					$replace_value = call_user_func_array($replace_value['callable'], $replace_value['params']);
				} else if(isset($replace_value['callable'])){
					$replace_value = call_user_func($replace_value['callable']);
				} else {
					$replace_value = call_user_func($replace_value);
				}
			}
			$content = str_replace($this->get_placement_token($replace_key), $replace_value, $content);
		}
		
		foreach ($this->magic_replace as $search => $replace) {
			$content = str_replace($search, $replace, $content);
		}
		
		if ($this->base_path_symbol !== NULL){
			$content = str_replace($this->base_path_symbol, base_url(), $content);
		}
		
		if( $this->output_collapse ) {
			$content = str_replace("\t", '', $content);
			$content = str_replace(array(">\r\n", ">\n", ">\r"), '>', $content);
		}
		
		$this->output->set_output($content);
	}
	
	public function set_layout($layout_name, $vars = array())
	{
		
		$this->layout = array('name' => $layout_name, 'vars' => $vars);
	}
	
	public function add_js_file($file_name, $attr = array(), $has_min_version = TRUE, $group = 'default', $conditional = NULL)
	{
		if ( !isset($this->js_files[$group]) ) {
			$this->js_files[$group] = array();
		}
		
		if ( !isset($attr['src']) ) {
			$attr['src'] = $file_name;
		}
		
		if( !$this->js_debug && $has_min_version ) {
			$js_ext = '.js';
			$ext_pos = strrpos($attr['src'], $js_ext);
			if ($ext_pos !== FALSE) {
				$attr['src'] = substr_replace($attr['src'], $this->minify_js_ext, $ext_pos, strlen($js_ext));
			}
		}
		
		$this->js_files[$group][$file_name] = array(
			'tag_name' => 'script',
			'attr' => $attr,
			'has_close_tag' => TRUE,
			'wrap_conditional' => $conditional
		);
	}
	
	public function add_css_file($file_name, $attr = array(), $has_min_version = TRUE, $group = 'default', $conditional = NULL)
	{
		if ( !isset($this->css_files[$group]) ) {
			$this->css_files[$group] = array();
		}
		
		if ( !isset($attr['href']) ) {
			$attr['href'] = $file_name;
		}
		
		if( !$this->css_debug && $has_min_version ) {
			$css_ext = '.css';
			$ext_pos = strrpos($attr['href'], $css_ext);
			if ($ext_pos !== FALSE) {
				$attr['href'] = substr_replace($attr['href'], $this->minify_css_ext, $ext_pos, strlen($css_ext));
			}
		}
		
		if ( !isset($attr['rel']) ) {
			$attr['rel'] = 'stylesheet';
		}
		
		$this->css_files[$group][$file_name] = array(
			'tag_name' => 'link',
			'attr' => $attr,
			'has_close_tag' => FALSE,
			'wrap_conditional' => $conditional
		);
	}
	
	private function assign($name, $value)
	{
		$this->placement[$name] = $value;
	}
	
	public function get_placement_token($name)
	{
		return "<!--View::placement_{$name}-->";
	}
	
	public function assign_view($name, $view_name, $var = array())
	{
		$this->assign($name, array( 'callable' => array($this, 'load'), 'params' => array($view_name, $var, TRUE) ));
	}
	
	public function place($name)
	{
		echo $this->get_placement_token($name);
		
		if( !isset($this->placement[$name]) )
		{
			$this->placement[$name] = '';
		}
	}
	
	public function place_header_links()
	{
		$this->assign('header_links', array( 'callable' => array($this, 'get_header_links_html') ));
		echo $this->get_placement_token('header_links');
	}
	public function place_meta()
	{
		$this->assign('meta', array( 'callable' => array($this, 'get_meta_html') ));
		echo $this->get_placement_token('meta');
	}
	
	public function place_body()
	{
		if(isset($this->placement['body']) && is_string($this->placement['body'])){
			echo $this->placement['body'];
		} else {
			$this->place('body', '');
		}
	}
	
	public function place_js_files($group = 'default')
	{
		$this->assign('js_files_' . $group, array( 'callable' => array($this, 'print_files'), 'params' => array(&$this->js_files, $group) ));
		$this->place('js_files_' . $group);
	}
	
	public function place_css_files($group = 'default')
	{
		$this->assign('css_files_' . $group, array( 'callable' => array($this, 'print_files'), 'params' => array(&$this->css_files, $group) ) );
		$this->place('css_files_' . $group);
	}
	
	private function print_files(&$files, $group = 'default')
	{
		$js_inc_str = '';
		if(isset($files[$group])) {
			foreach ($files[$group] as $tag_prop) {
				if($tag_prop['wrap_conditional']){
					$js_inc_str .= "<!--[if {$tag_prop['wrap_conditional']}]>";
				}
				$js_inc_str .= $this->tag($tag_prop['tag_name'], $tag_prop['attr'], $tag_prop['has_close_tag']);
				if($tag_prop['wrap_conditional']){
					$js_inc_str .= '<![endif]-->';
				}
			}
		}
		
		return $js_inc_str;
	}
	
	public function tag($tag_name, $attr, $has_close_tag)
	{
		$attr_str = '';
		foreach ($attr as $attr_name => $attr_value) {
			$attr_str .= ' ' . $attr_name . '="'. htmlspecialchars($attr_value) . '"';
		}
		if ($has_close_tag) {
			return "<{$tag_name}{$attr_str}></{$tag_name}>";
		} else {
			return "<{$tag_name}{$attr_str} />";
		}
	}
	
	public function load($view_name, $var = array(), $is_return = FALSE)
	{
		$view_path = VIEWPATH . $view_name . '.php';
		
		if ( file_exists( $view_path )) {
			
			if($is_return) {
				ob_start();
			}
			
			if (is_array($var))
			{
				extract($var);
			}
			
			include $view_path;
			
			if ($is_return) {
				$buffer = ob_get_contents();
				@ob_end_clean();
				return $buffer;
			} else {
				return TRUE;
			}
			
		} else {
			show_error('Unable to load the requested file: ' . $view_name);
		}
		
	}
	
	public function set_var($name, $value)
	{
		$this->vars[$name] = $value;
	}
	
	public function get_var($name)
	{
		if( isset($this->vars[$name]) ) {
			return $this->vars[$name];
		}
		return null;
	}
	public function set_header_link($rel, $href)
	{
		$this->header_links[$rel] = $href;
	}
	
	public function get_header_links_html()
	{
		$link_html = '';
		foreach ($this->header_links as $rel => $href) {
			$link_html .= $this->tag('link', array('rel' => $rel, 'href' => $href), FALSE);
		}
		return $link_html;
	}
	
	public function has_meta($name)
	{
		return isset( $this->meta[$name] );
	}
	
	public function set_meta($name, $content)
	{
		$this->meta[$name] = $content;
	}
	
	public function add_keyword($keyword)
	{
		$this->keyword[$keyword] = true;
	}
	
	public function get_meta_html()
	{
		if( !empty($this->keyword) ){
			$this->set_meta('keywords', implode(',', array_keys($this->keyword)));
		}
		$meta_html = '';
		foreach ($this->meta as $name => $content) {
			if(is_array($content)) {
				$meta_html .= $this->tag('meta', $content, FALSE);
			} else {
				$meta_html .= $this->tag('meta', array('name' => $name, 'content' => $content), FALSE);
			}
		}
		return $meta_html;
	}
	
	public function add_title_segment($segment)
	{
		if(func_num_args() > 1){
			$segments = func_get_args();
			foreach($segments as $seg){
				array_unshift($this->title, $seg);
			}
		} else {
			array_unshift($this->title, $segment);
		}
	}
	
	public function set_title($title)
	{
		$this->title = array($title);
	}
	
	public function get_title(){
		return implode($this->title_separator, $this->title);
	}
	
	public function set_menu($menu){
		$this->menu = $menu;
	}
	
	public function __get($key)
	{
		return get_instance()->$key;
	}
}