<?php 

class User_Form_ChangePassword extends Zend_Form {
	
    public function __construct($regions = null, $options = null)
    {
    	parent::__construct($options); 
    	
		$this->setMethod('post');
		$this->setAttrib('id', 'change-password-form');
		
		$passwordMatch = new User_Form_Validate_PasswordMatch();
		
     	$password = new Zend_Form_Element_Password('password');
    	$password->setLabel("Geslo")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($passwordMatch)
    							));
    	$this->addElement($password);

     	$rePassword = new Zend_Form_Element_Password('re-password');
    	$rePassword->setLabel("Ponovi geslo")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($passwordMatch)
    							));
    	$this->addElement($rePassword);


        $login = $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Spremeni',
        	'id'		=> 'btnAppRegister'
        ));
        
        $this->addDisplayGroup(array('password', 'repassword', 'submit'), 'groups', array("legend" => "Spremeni geslo"));

	}
}