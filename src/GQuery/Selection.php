<?php 

namespace GQuery;

use GQuery\Fragment;

class Selection {
	protected $_identifier;
	
	protected $_selections;
	protected $_arguments;
	
	protected $_fragments;
	
	public function __construct($identifier, $selections = null, $arguments = null) {
		
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
		
	}
	
	public function arguments($arguments) {
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
}