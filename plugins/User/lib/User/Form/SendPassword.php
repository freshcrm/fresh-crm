<?php 

class User_Form_SendPassword extends Zend_Form {
	
    public function __construct($regions = null, $options = null)
    {
    	parent::__construct($options); 
    	
		$this->setMethod('post');
		$this->setAttrib('id', 'send-password-form');
		
		
     	$email = new Zend_Form_Element_Text('email');
    	$email->setLabel("E-mail: *")
    			->setAttrib("class", "fw r")
    			  //  		 ->setDecorators(array('FormElement'))
    			->setRequired("true")
    			->addValidators(array(
    									array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno'))),
    									array('EmailAddress', true, array('messages' => array('emailAddressInvalidFormat' => '%value% ni email naslov'))),
    									//array($emailMatch),
    									//array($uniqueEMail)
    							));
    	$this->addElement($email);   	
		
       	$captcha = $this->createElement('captcha', 'captcha',
							array(	'required' => true,
									'captcha' => array('captcha' => 'Image',
									'font' => PIMCORE_PLUGINS_PATH.'/User/static/fonts/FreeSans.ttf',
									'fontSize' => 32,
									'wordLen' => 3,
									'height' => 70,
									'width' => 460,
									'imgDir' => PIMCORE_PLUGINS_PATH.'/User/static/captcha',
									'imgUrl' => '/plugins/User/static/captcha',
									'dotNoiseLevel' => 50,
									'lineNoiseLevel' => 5,
							      	'messages' => array(
        											'badCaptcha' => 'niste pravilno prepisali znakov',
													'missingValue'	=> 'obvezno'
      											)
      								)));
      								
		$captcha->setDescription("Polja označena z * so obvezna");
		$captcha->setLabel('Prepišite znake na sliki: *');
        $this->addElement($captcha);    	

        
        $login = $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'SEND_PASSWORD',
        ));
        
        $this->addDisplayGroup(array('email', 'captcha', 'submit'), 'groups', array("legend" => "Pošlji geslo"));

	}
}