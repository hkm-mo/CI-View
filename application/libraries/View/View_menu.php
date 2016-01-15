<?php

class View_menu {
	private $menu_data = array();
	private $active_items = null;
	
	function __construct($menu_data){
		$this->menu_data = $menu_data;
	}
	
	public function to_array(){
		return $this->menu_data;
	}
	
	public function get_active_items() {
		if( !empty($this->active_items) ) {
			return $this->active_items;
		} else {
			return null;
		}
	}
	
	public function set_active($item_id){
		$items = $this->find_item_tree($item_id);
		if ( !empty($items) ) {
			$this->active_items = $items;
			foreach ( $items as &$item ) {
				$item['is_active'] = TRUE;
			}
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	private function &find_item($item_id){
		if(isset( $this->menu_data[$item_id] ) ){
			return $this->menu_data[$item_id];
		} else {
			foreach ( $this->menu_data as &$menu ) {
				if ( isset($menu['submenu'][ $item_id ]) ) {
					return $menu['submenu'][ $item_id ];
				}
			}
		}
		return FALSE;
	}
	
	private function find_item_tree($item_id){
		if(isset( $this->menu_data[$item_id] ) ){
			return array( &$this->menu_data[$item_id] );
		} else {
			foreach( $this->menu_data as &$menu ){
				if ( isset($menu['submenu'][ $item_id ]) ) {
					return array( &$menu, &$menu['submenu'][ $item_id ] );
				}
			}
		}
		return FALSE;
	}
}