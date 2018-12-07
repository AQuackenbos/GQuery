<?php

use GQuery\Query;
use GQuery\Fragment;
use GQuery\Input\Raw;

class GQuery {
	protected static $_queries		= [];
	protected static $_fragments	= [];
	
	public static function fragment($identifier, $type = null, $selections = null) {
		$fragment = null;
		if(array_key_exists($identifier,self::$_fragments)) {
			$fragment = self::$_fragments[$identifier];
		} else {
			$fragment = new Fragment($identifier);
			self::$_fragments[$identifier] = $fragment;
		}
		
		if($type !== null)
			$fragment->type($type);
		
		if($selections !== null)
			$fragment->selections($selections);
		
		return $fragment;
	}
	
	public static function query($identifier, $selections = null) {
		$query = null;
		if(array_key_exists($identifier, self::$_queries)) {
			$query = self::$_queries[$identifier];
		} else {
			$query = new Query($identifier);
			self::$_queries[$identifier] = $query;
		}
		
		if($selections !== null)
			$query->selections($selections);
		
		return $query;
	}
	
	public static function raw($value) {
	  $raw = new Raw($value);
	  
	  return $raw;
	}
}