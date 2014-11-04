<?php 

class User_Form_Profil extends Zend_Form {
	
    public function __construct($options = null, $user = null)
    {
    	parent::__construct($options); 
    	
		$this->setMethod('post');
		$this->setAttrib('id', 'profil-form');
		
		
    	$name = new Zend_Form_Element_Text('name');
    	$name->setLabel("NAME *")
    		 ->setRequired("true");
    	
     	$surname = new Zend_Form_Element_Text('surname');
    	$surname->setLabel("SURNAME *")
    			->setRequired("true");
    	
    	
    	$address = new Zend_Form_Element_Text('address');
    	$address->setLabel("ADDRESS");
    	
    	
    	$post = new Zend_Form_Element_Text('post');
    	$post->setLabel("POST_NUMBER_AND_POST");
    	
    	$phone = new Zend_Form_Element_Text("phone");
    	$phone->setLabel("PHONE");
    	    	
    	$gsm = new Zend_Form_Element_Text("gsm");
    	$gsm->setLabel("GSM");
    	
    	
    	if($user) {
    	     $name->setValue($user->getName());
    	     $surname->setValue($user->getSurname());

    	     if($user->getAddress())    
    	         $address->setValue($user->getAddress());
    	     if($user->getPost())
    	         $post->setValue($user->getPost());
    	}
    	
    	
    	$this->addElement($name);
    	$this->addElement($surname);
    	
    	$this->addElement($address);
    	$this->addElement($post);

    	
    	/*
    	$this->addElement($birthDate);

    	$this->addElement($phone);
    	$this->addElement($gsm);
    	*/

        
        $this->addDisplayGroup(array('name','surname'), 'personal-data', array("legend" => "PERSONAL_DATA"));
        $this->addDisplayGroup(array('address', 'post'), 'delivery-data', array("legend" => "DELIVERY_DATA"));
        
        $submit = $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'UPDATE',
        	'id'		=> 'btnAppUpdateProfilData'
        ));        

	}
}