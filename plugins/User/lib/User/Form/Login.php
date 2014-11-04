<?php

class User_Form_Login extends Zend_Form
{

    public function __construct($options = null)
    {
    	parent::__construct($options);     	
    	
    	$this->setMethod('post');
    	$this->setAttrib("id", "login-form");

    	$username = $this->addElement('text', 'memail', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'required'   => true,
            'label'      => 'E-naslov',
            'validators' => (array(array('NotEmpty', true, array('messages' => array('isEmpty' => 'obligatory')))))
        ));

        $password = $this->addElement('password', 'mpassword', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                //'Alnum',
                //array('StringLength', false, array(6, 20)),
            ),
            'required'   => true,
            'label'      => 'Geslo',
            'validators' => (array(array('NotEmpty', true, array('messages' => array('isEmpty' => 'obvezno')))))
        ));

        
		$rememberMe = new Zend_Form_Element_Checkbox('remember');
		$rememberMe->setLabel("Zapomni si me")
	        ->setUncheckedValue(0)
	        ->setCheckedValue(1)
	        ->setValue(1);
	    
	        
	    $this->addElement($rememberMe);
	   
        $submit = $this->addElement('submit', 'submit', array(
            'required' => false,
        	'value'	=> "Pošlji",
        	'label'	=> 'Pošlji',
        	'id'		=> 'btnContact'
        ));	   
        
        $this->addDisplayGroup(array('memail','mpassword', 'remember', 'submit'), 'groups', array("legend" => "Prijava"));

    }

}