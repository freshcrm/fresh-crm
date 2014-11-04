<?php

class User_Form_Validate_RegisteredEmail extends Zend_Validate_Abstract {

	const DONT_EXISTS = 'exsists';
	

	protected $_messageTemplates = array(
		self::DONT_EXISTS => 'Uporabnik %value% še ni registriran'
	);

	public function isValid($value, $context = null) {
		$value = (string) $value;
		$this->_setValue($value);

		/*
		$user = new Model_User();

		if(!$user->emailTaken($value)) {
			$this->_error(self::DONT_EXISTS);
			return false;
		} else {
			return true;
		}
		*/

		return true;
	}
	
}