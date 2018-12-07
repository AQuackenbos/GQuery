<?php

namespace GQuery;

class Variable {
	protected $_identifier;
	protected $_type;
	protected $_defaultValue;
	
	public function __construct($identifier, $type = null, $default = null) {
		$this->_identifier = $identifier;
		
		if($type !== null)
			$this->type($type);
			
		if($default !== null)
			$this->defaultValue($default);
	}
	
	public function identifier($identifier = null) {
		if($identifier !== null)
			$this->_identifier = $identifier;
			
		return $this->_identifier;
	}
	
	public function type($type = null) {
		if($type === null)
			return $this->_type;
			
		$this->_type = $type;
		return $this;
	}
	
	public function defaultValue($default = null) {
		if($default === null)
			return $this->_defaultValue;
			
		$this->_defaultValue = $default;
		return $this;
	}
	
	public function render() {
		$out = '';
		$out .= "\${$this->identifier()}: {$this->type()}";
		if($this->defaultValue() !== null) {
			$out .= " = {$this->_renderDefault($this->defaultValue())}";
		}
		return $out;
	}
	
	protected function _renderDefault($value) {
		if(is_string($value))
			return '"'.$value.'"';
		
		if(is_int($value))
			return $value;
			
		if($value instanceof Raw)
			return $value->render();
			
		if(is_array($value)) {
			if(!$this->_isAssocArray($value)) {
				return '['.implode(',',$value).']';
			}
			
			$argStrings = [];
			$out = '{ ';
			foreach($value as $k => $v) {
				$argStrings[] = "{$k}: {$this->_renderDefault($v)}";
			}
			$out .= implode(', ',$argStrings);
			$out .= '}';
			
			return $out;
		}
		
		return '';
	}
	
	protected function _isAssocArray(array $arr)
	{
		if (array() === $arr) return false;
		return array_keys($arr) !== range(0, count($arr) - 1);
	}
}