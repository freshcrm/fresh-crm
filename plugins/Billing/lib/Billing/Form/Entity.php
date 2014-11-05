<?php 

class Billing_Form_Entity extends Zend_Form {
	
    public function __construct($options = null, $user = null) {
    	parent::__construct($options); 
		$this->setMethod('post');
		$this->setAttrib('id', 'entity-form');

    	$entity = new Zend_Form_Element_Text('entity');
    	$entity->setLabel("ENTITY *")
    		   ->setRequired("true");
    		 
     	$vat = new Zend_Form_Element_Text('vat');
    	$vat->setLabel("VAT *")
    			->setRequired("true");
    			    			
    	
    	if($user) {
    	    $entity->setValue($user->getEntity());
    	    $vat->setValue($user->getVat());
    	}
    	
    	if(Zend_Registry::isRegistered("user")) {
    		$user = Zend_Registry::get("user");
    		
    		$entity->setValue($user->getEntity());
    		$vat->setValue($user->getVat());
    	}
    	
    	$this->addElement($entity);
        $this->addElement($vat);   	

    	
        $this->addDisplayGroup(array('entity','vat', 'submit'), 'groups', array("legend" => "ENTITY_DATA"));
	}
}