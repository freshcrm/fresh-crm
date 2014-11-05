<?php 

class Billing_CreditController extends Website_Controller_Plugin {
	
	public function init() {
		parent::init();
		
		if(!Zend_Registry::isRegistered("user")) {
			$this->_redirect('/login', array('err' => 3));
		}	
	}
	
	
	public function addAction() {
		$this->enableLayout();
		$form = new Billing_Form_AddCredit();		
		$request = $this->getRequest();
		$user = Object_User::getById($this->view->user->o_id);
		
		$this->view->user = $user;
		$this->view->billingConfig = Billing_Tool::getConfig();
		
		if($request->isPost() && $form->isValid($request->getParams())) {
			$order = new Resource_Order();
			$order->setPaymentMethod('UPN');
			$order->setType("CREDIT");
			
			$order->setUser($user);
			$order->setPriceTotal($this->_getParam("amount"));
			
			if($request->getParam('isentity')) {
				$order->setEntity(1);
				
				// update users entity
				$user->setEntity($request->getParam("entity"));
				$user->setVat($request->getParam("vat"));
				
				try {
					$user->save();
				} catch (Exception $e) {
					error_log("Billing_Credit/add (update users entity): ".$e->getMessage());
				}
				
			}
			
			try {
				$order->save();
				// create UPN image
				Billing_Tool::createUPN($order->getId());
				Billing_Mail::sendUPN($order->getId());
				
				$this->_redirect('/plugin/Billing/Payment/success/id/'.$order->getId());
			} catch (Exception $e) {
				error_log("Credit/Add: ".$e->getMessage());
			}
		}
		
		$this->view->form = $form;
	}
}