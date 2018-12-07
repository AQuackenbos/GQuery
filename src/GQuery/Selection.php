<?php

namespace GQuery;

use GQuery\Fragment;
use GQuery\Input\Raw;

class Selection {
	protected $_identifier;
	
	protected $_selections	= [];
	protected $_arguments		= [];
	protected $_directives	= [];
	protected $_fragments		= [];
	
	public function __construct($identifier, $selections = null, $arguments = null) {
		$this->_identifier = $identifier;
		
		if($selections !== null)
			$this->selections($selections);
		
		if($arguments !== null)
			$this->arguments($arguments);
	}
	
	public function identifier($identifier = null) {
		if($identifier !== null)
			$this->_identifier = $identifier;
		
		return $this->_identifier;
	}
	
	public function selection($identifier, $selections = null, $arguments = null) {
		$selection = null;
		
		if($identifier instanceof Fragment || array_key_exists($identifier, $this->_fragments)) {
			return $this->fragment($identifier);
		} elseif(array_key_exists($identifier, $this->_selections)) {
			$selection = $this->_selections[$identifier];
		} else	{
			$selection = new Selection($identifier, $selections, $arguments);
			$this->_selections[$identifier] = $selection;
		}
		
		if($selections !== null)
			$selection->selections($selections);
		
		if($arguments !== null)
			$selection->arguments($arguments);
		
		return $selection;
	}
	
	public function selections($identifiers = null) {
		if($identifiers === null)
			return $this->_selections;
		
		if(!is_array($identifiers))
			$identifiers = [$identifiers];
		
		foreach($identifiers as $key => $value) {
			if($key === '_arguments') {
				$this->arguments($value);
				continue;
			}
			
			if($key === '_fields') {
				$this->selections($value);
				continue;
			}
			
			if(is_int($key)  && !is_array($value)) {
				$this->selection($value);
				continue;
			}
			
			if($value instanceof Fragment) {
				$this->fragment($value);
				continue;
			}
			
			$this->selection($key, $value);
		}
		
		return $this->selections();
	}
	
	public function fragments($identifiers = null) {
		if($identifiers === null) {
			return $this->_fragments;
		}
		
		if(!is_array($identifiers))
			$identifiers = [$identifiers];
		
		foreach($identifiers as $id => $data) {
			$this->fragment($id, $data['type'], $data['selections']);
		}
		
		return $this->_fragments;
	}
	
	public function fragment($identifier, $type = null, $selections = null) {
		$fragment = null;
		if($identifier instanceof Fragment) {
		  $fragment = $identifier;
		}
		
		if($fragment === null) {
  		if(array_key_exists($identifier, $this->_selections)) {
  		  return $this->_selection($identifier);
  		} elseif (array_key_exists($identifier, $this->_fragments)) {
  		  $fragment = $this->_fragments[$identifier];
  		} else {
  		  $fragment = new Fragment($identifier, $type, $selections);
  		  $this->_fragments[$identifier] = $fragment;
  		}
		}
  		
		if($type !== null)
		  $fragment->type($type);
		  
		if($selections !== null)
		  $fragment->selections($selections);
		  
		return $fragment;
	}
	
	public function arguments($arguments = null) {
		if($arguments === null)
			return $this->_arguments;
		
		foreach($arguments as $_arg => $_val) {
			$this->_arguments[$_arg] = $_val;
		}
		
		return $this;
	}
	
	public function argument($identifier, $value = null) {
		if($value === null && array_key_exists($identifier, $this->_arguments))
			return $this->_arguments[$identifier];
		
		$this->_arguments[$identifier] = $value;
		
		return $this;
	}
	
	public function render(&$fragmentExport, $preTabs = 0) {
		$output = '';
		$preTabString = $this->_preTabs($preTabs);
		$output .= $preTabString.$this->identifier();
		if(count($this->selections()) > 0 || count($this->fragments()) > 0) {
			$output .= ' ';
			if(count($this->arguments()) > 0) {
				//@todo
				$output .= '(';
				$argStrings = [];
				foreach($this->arguments() as $_arg => $_val) {
					$argStrings[] = "{$_arg}: {$this->_renderArg($_val)}";
				}
				$output .= implode(', ', $argStrings);
				$output .= ')';
			}
			$output .= " {\n";
			foreach($this->selections() as $_s) {
				$output .= "{$preTabString}{$_s->render($fragmentExport,($preTabs + 1))}";
			}
			foreach($this->fragments() as $_f) {
				$output .= "{$preTabString}{$_f->render($fragmentExport,($preTabs + 1))}";
			}
			$output .= "\n{$preTabString}}";
		} else {
			$output .= "\n";
		}
		
		return $output;
	}
	
	protected function _renderArg($value) {
		if(is_string($value))
			return '"'.$value.'"';
		
		if(is_int($value))
			return $value;
			
		if($value instanceof Raw)
			return $value->render();
			
		if(is_array($value)) {
			if(!$this->_isAssocArray($value)) {
				return '['.implode(',',array_map([$this,'_renderArg'],$value)).']';
			}
			
			$argStrings = [];
			$out = '{ ';
			foreach($value as $k => $v) {
				$argStrings[] = "{$k}: {$this->_renderArg($v)}";
			}
			$out .= implode(', ',$argStrings);
			$out .= '}';
			
			return $out;
		}
		
		return '';
	}
	
	protected function _preTabs($preTabCount) {
		$tabs = '';
		for($i = 0; $i < $preTabCount; $i++) {
			$tabs .= "\t";
		}
		return $tabs;
	}
	
	protected function _isAssocArray(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}