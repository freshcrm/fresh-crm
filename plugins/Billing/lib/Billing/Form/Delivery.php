<?php 

class Billing_Form_Delivery extends Zend_Form {
	
    public function __construct($options = null, $user = null) {
    	parent::__construct($options); 
		$this->setMethod('post');
		$this->setAttrib('id', 'delivery-form');

    	$name = new Zend_Form_Element_Text('name');
    	$name->setLabel("NAME *")
    		 ->setRequired("true");
    		 
     	$surname = new Zend_Form_Element_Text('surname');
    	$surname->setLabel("SURNAME *")
    			->setRequired("true");
    			    			
     	$address = new Zend_Form_Element_Text('address');
    	$address->setLabel("ADDRESS *")
    	        ->setRequired(true);
    			
     	$post = new Zend_Form_Element_Text('post');
    	$post->setLabel("POST *")
    	    ->setRequired(true);
    	
    	if($user) {
    	    $name->setValue($user->getName());
    	    $surname->setValue($user->getSurname());
    	    $address->setValue($user->getAddress());
    	    $post->setValue($user->getPost());
    	}
    	
    	$this->addElement($name);
        $this->addElement($surname);   	
    	$this->addElement($address);
    	$this->addElement($post);    	
    	
        $this->addDisplayGroup(array('name','surname', 'address', 'post', 'submit'), 'groups', array("legend" => "DELIVERY_DATA"));
	}
}