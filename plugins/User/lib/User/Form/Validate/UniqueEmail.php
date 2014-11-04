<?php

class User_Form_Validate_UniqueEmail extends Zend_Validate_Abstract {

	const EMAIL_EXISTS = 'exsists';
	const EMAIL_NOT_VALIDATED = 'not_confirmed';

	protected $_messageTemplates = array(
		self::EMAIL_EXISTS => '%value% že registriran',  // - <a href="/clan/pozabil-geslo">Pozabil geslo?</a>
		self::EMAIL_NOT_VALIDATED => '%value% ni potrjen <br/> <a href="/clan/resend-validation-email?email=%value%" id="getKey">Ponovno pošlji potrditveni e-mail?</a>'
	);

	public function isValid($value, $context = null) {	
		$value = (string) $value;
		$this->_setValue($value);

		$list = Object_User::getByEmail($value, 1);
		
		if(count($list)==0)
			return true;
		else {
			$this->_error(self::EMAIL_EXISTS);
			return false;
		}

		return true;
	}
	
}