<?php 

class User_Form_Registration extends Zend_Form {
	
    public function __construct($options = null)
    {
    	parent::__construct($options); 
    	
		$this->setMethod('post');
		$this->setAttrib('id', 'registration-form');

		
		$passwordMatch = new User_Form_Validate_PasswordMatch();
		$emailMatch = new User_Form_Validate_EmailMatch();
		$uniqueEMail = new User_Form_Validate_UniqueEmail();
		$uniqueUsername = new User_Form_Validate_UniqueUsername();
		
		$emailAddress = new Zend_Validate_EmailAddress();
		$emailAddress->setMessages(array(
    			Zend_Validate_EmailAddress::INVALID_FORMAT => '%value% ni email naslov',
    			Zend_Validate_Hostname::INVALID_HOSTNAME => '%value% ni veljavna domena',
    			Zend_Validate_Hostname::LOCAL_NAME_NOT_ALLOWED => '%value% ni veljavna domena'
		));
				
     	$email = new Zend_Form_Element_Text('email');
    	$email->setLabel("E-pošta: *")
    			->setAttrib("class", "fw r")
    			  //  		 ->setDecorators(array('FormElement'))
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($emailAddress),
    									array($uniqueEMail)
    							));
    	$this->addElement($email);
        
    	$name = new Zend_Form_Element_Text('name');
    	$name->setLabel("Ime: *")
    		 ->setAttrib("class", "fw r")
    		 ->setRequired("true")
    		 ->addValidators(array(array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno')))));
    		 
    	$this->addElement($name);

    	
 	    $surname = new Zend_Form_Element_Text('surname');
    	$surname->setLabel("Priimek: *")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno')))));
    	$this->addElement($surname);   	
    
 	    $phone = new Zend_Form_Element_Text('phone');
    	$phone->setLabel("Telefon: *")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno')))));
    	$this->addElement($phone);   

     	$password = new Zend_Form_Element_Password('password');
    	$password->setLabel("Geslo: *")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($passwordMatch)
    							));
    	$this->addElement($password);

     	$rePassword = new Zend_Form_Element_Password('re-password');
    	$rePassword->setLabel("Ponovi geslo: *")
    			->setAttrib("class", "fw r")
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array($passwordMatch)
    							));
    	$this->addElement($rePassword);		
        $this->addDisplayGroup(array('name','surname', 'phone'), 'profile', array("legend" => "Osebni podatki"));
        $this->addDisplayGroup(array('email','password', 'repassword'), 'login', array("legend" => "Podatki za prijavo"));
        
        if($options && $options['deliveryType'] == 'delivery') {
 	        $address = new Zend_Form_Element_Text("address");
	        $address->setLabel("ADDRESS*")
	        	->setRequired(true);	
	        $this->addElement($address);

	        $post = new Zend_Form_Element_Text("post");
	        $post->setLabel("POST*")
	        	->setRequired(true);	
	        $this->addElement($post);	        
	        
	        $this->addDisplayGroup(array('address', 'post'), 'delivery', array("legend" => "Podatki za dostavo"));       	
        }
        
        $login = $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Registriraj se',
        	'id'		=> 'btnAppRegister'
        ));
	}
}