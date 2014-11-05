<?php 

class Billing_Form_AddCredit extends Zend_Form {
	
    public function __construct($options = null)
    {
    	parent::__construct($options); 
    	
		$this->setMethod('post');
		$this->setAttrib('id', 'addcredit-form');
		
		$isentity = new Zend_Form_Element_Checkbox("isentity");
		$isentity->setLabel("ISENTITY");

		$entity = new Zend_Form_Element_Text("entity");
		$entity->setLabel("ENTITY")
			   ->setAttrib('disabled', 'disabled')
			   ->addValidators(array(new Billing_Form_Validate_FieldDepends('isentity', '1')))
			   ->setAllowEmpty(false);
		
		$vat = new Zend_Form_Element_Text('vat');
		$vat->setLabel("VAT")
			->setAttrib('disabled', 'disabled')
			   ->addValidators(array(new Billing_Form_Validate_FieldDepends('isentity', '1')))
			   ->setAllowEmpty(false);			;
		
		$amount = new Zend_Form_Element_Text('amount');
		$amount->setLabel("AMOUNT")
				->setRequired(true);

		
				
		$this->addElement($isentity);
		$this->addElement($vat);
		$this->addElement($entity);
		$this->addElement($amount);

		
        $submit = $this->addElement('submit', 'submit', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'SUBMIT'
        	
        ));
        
        $this->addDisplayGroup(array('isentity', 'entity', 'vat'), 'entitygroup', array("legend" => "ENTITY"));
        $this->addDisplayGroup(array('amount', 'submit'), 'amountgroup', array("legend" => "AMOUNT"));
        

	}
}