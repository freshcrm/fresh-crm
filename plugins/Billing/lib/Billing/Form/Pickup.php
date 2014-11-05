<?php 

class Billing_Form_Pickup extends Zend_Form {
	
    public function __construct($options = null, $user = null) {
    	parent::__construct($options); 
		$this->setMethod('post');
		$this->setAttrib('id', 'pickup-form');

    	$name = new Zend_Form_Element_Text('name');
    	$name->setLabel("NAME *")
    		 ->setRequired("true");
    		 
     	$surname = new Zend_Form_Element_Text('surname');
    	$surname->setLabel("SURNAME *")
    			->setRequired("true");
    			    			
    	
    	if($user) {
    	    $name->setValue($user->getName());
    	    $surname->setValue($user->getSurname());
    	}
    	
    	$this->addElement($name);
        $this->addElement($surname);   	
    	
        $this->addDisplayGroup(array('name','surname', 'submit'), 'groups', array("legend" => "PICKUP_DATA"));
	}
}