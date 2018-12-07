<?php

namespace GQuery\Input;

class Raw {
  protected $_value;
  
  public function __construct($value) {
    $this->_value = $value;
  }
  
  public function render() {
  	return $this->_value;
  }
  
  public function __toString() {
  	return $this->render();
  }
  
}