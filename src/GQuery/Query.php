<?php

namespace GQuery;

use GQuery\Fragment;
use GQuery\Selection;
use GQuery\Variable;

class Query extends Selection {
	protected $_variables;

	public function __construct($identifier, $selections = null, $variables = null) {
		$this->_identifier = $identifier;
		
		if($selections !== null)
			$this->selections($selections);
		
		if($variables !== null)
			$this->variables($variables);
	}
	
	public function variables($variables = null) {
		if($variables === null)
			return $this->_variables;
		
		foreach($variables as $identifier => $data) {
			$this->variable($identifier, $data['type'], array_key_exists('default', $data) ? $data['default'] : null);
		}
		
		return $this->_variables;
	}
	
	public function variable($identifier, $type = null, $default = null) {
		$variable = null;
		if(array_key_exists($identifier, $this->_variables)) {
			$variable = $this->_variables[$identifier];
		} else {
			$variable = new Variable($identifier);
			$this->_variables[$identifier] = $variable;
		}
		
		if($type !== null)
			$variable->type($type);
		
		if($default !== null)
			$variable->defaultValue($default);
		
		return $variable;
	}
	
	final public function arguments($arguments = null) {
		return $this;
	}
	
	final public function argument($identifier, $value = null) {
		return $this;
	}
	
	public function renderQuery($variables = null, $defaults = true) {
		
	}
	
	public function render(&$fragmentExport, $preTabs = 0) {
		return $this->renderQuery();
	}
	
	public function __toString() {
		return $this->renderQuery();
	}
}