<?php

class User_Form_Validate_UniqueUsername extends Zend_Validate_Abstract {

	const EMAIL_EXISTS = 'exsists';
	protected $_messageTemplates = array(
		self::EMAIL_EXISTS => '%value% zasedeno',
	);

	
	public function isValid($value, $context = null) {	
		$value = (string) $value;
		$this->_setValue($value);

		$list = Object_User::getByUsername($value, 1);

		if(count($list)==0)
			return true;
		else {
			$this->_error(self::EMAIL_EXISTS);
			return false;
		}
		
		return true;
	}
	
}