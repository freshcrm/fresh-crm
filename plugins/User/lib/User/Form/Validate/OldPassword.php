<?php

class User_Form_Validate_OldPassword extends Zend_Validate_Abstract {

	const NOT_MATCH = 'not match';
	

	protected $_messageTemplates = array(
		self::NOT_MATCH => 'geslo ni pravilno',
	);

	public function isValid($value, $context = null) {
		$value = (string) $value;
		$this->_setValue($value);

		$userDb = new Model_User();
        
        $auth = Zend_Auth::getInstance();
        $user = $auth->getIdentity();
		
		$retVal = $userDb->checkPassword($value, $user->email);
		
		if($retVal)
			return true;
		else {
			$this->_error(self::NOT_MATCH);
			return false;
		}
					
	}
	
}