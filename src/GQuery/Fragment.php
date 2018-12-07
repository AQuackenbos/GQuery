<?php

namespace GQuery;

use GQuery\Selection;

class Fragment extends Selection {
	protected $_anonymous = false;
	protected $_type = '';
	
	public function __construct($identifier, $type = null, $selections = null) {
	  $this->_identifier = $identifier;
	  
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
	
	final public function arguments($arguments) {
		return $this;
	}
	
	final public function argument($identifier, $value = null) {
		return $this;
	}
	
}