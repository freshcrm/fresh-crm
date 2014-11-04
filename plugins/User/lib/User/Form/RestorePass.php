<?php 

class User_Form_RestorePass extends Zend_Form {
	
    public function __construct($user, $hash, $options = null)
    {
    	parent::__construct($options); 
    	
		$this->setMethod('post');
		$this->setAttrib('id', 'restore-pass-form');

		
		$hashInput = new Zend_Form_Element_Hidden('hash');
		$hashInput->setValue($hash);
		$this->addElement($hashInput);
		
		
		$passwordMatch = new User_Form_Validate_PasswordMatch();

     	$password = new Zend_Form_Element_Password('password');
    	$password->setLabel("Geslo: *")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($passwordMatch)
    							));
    	$this->addElement($password);

     	$rePassword = new Zend_Form_Element_Password('repassword');
    	$rePassword->setLabel("Ponovi geslo: *")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($passwordMatch)
    							));
    	$this->addElement($rePassword);	
    		
        $this->addDisplayGroup(array('password','repassword'), 'group', array("legend" => "Spremeni geslo"));
        
        $login = $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'POSODOBI',
        ));        

	}
}