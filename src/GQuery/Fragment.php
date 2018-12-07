<?php

namespace GQuery;

use GQuery\Selection;

class Fragment extends Selection {
	protected $_anonymous = false;
	protected $_type = '';
	
	public function __construct($identifier, $type = null, $selections = null) {
		$this->_identifier = $identifier;
		
		if($this->_identifier === '...')
			$this->_anonymous = true;
		
		if($type !== null)
			$this->type($type);
			
		if($selections !== null)
			$this->selections($selections);
	}
	
	public function type($type = null) {
		if($type === null)
			return $this->_type;
			
		$this->_type = $type;
		
		return $this;
	}
	
	public function render(&$fragmentExport, $preTabs = 0) {
		$output = '';
		$preTabString = $this->_preTabs($preTabs);
		
		
		if($this->_anonymous) {
			$output .= "{$preTabString}... on {$this->type()} {\n";
			foreach($this->selections() as $_s) {
				$output .= "{$preTabString}{$_s->render($fragmentExport,($preTabs + 1))}";
			}
			foreach($this->fragments() as $_f) {
				$output .= "{$preTabString}{$_f->render($fragmentExport,($preTabs + 1))}";
			}
			$output .= "{$preTabString}\n}";
		} else {
			$output .= "{$preTabString}...{$this->identifier()}";
			
			if(!array_key_exists($this->identifier(), $fragmentExport)) {
				$fragmentQuery = "fragment {$this->identifier()} on {$this->type()} {\n";
				foreach($this->selections() as $_s) {
					$fragmentQuery .= "{$_s->render($fragmentExport,1)}";
				}
				foreach($this->fragments() as $_f) {
					$fragmentQuery .= "{$_f->render($fragmentExport,1)}";
				}
				$fragmentQuery .= "\n}\n";
				
				$fragmentExport[$this->identifier()] = $fragmentQuery;
			}
		}
		$output .= "\n";
		return $output;
	}
	
	final public function arguments($arguments = null) {
		return $this;
	}
	
	final public function argument($identifier, $value = null) {
		return $this;
	}
	
}