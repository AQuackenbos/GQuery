<?php 

namespace GQuery;

use GQuery\Selection;

class Fragment extends Selection {
	protected $_anonymous = false;
	
	final public function arguments($arguments) {
		return $this;
	}
	
	final public function argument($identifier, $value = null) {
		return $this;
	}
	
}