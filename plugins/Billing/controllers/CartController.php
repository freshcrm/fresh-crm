<?php

class Billing_CartController extends Website_Controller_Plugin {
	
    protected $_form;
    protected $_user;
    protected $_offerDb;  

    public function init() {
    	parent::init();
		if(Zend_Registry::isRegistered("user"))
			$this->_user = Zend_Registry::get("user");
		else if($this->getRequest()->getActionName() != 'price-total')
			$this->_redirect('/prijava');    	
    }

    public function getOfferDb() {
    	if(null == $this->_offerDb)
    		$this->_offerDb = new Db_Offer();
    	return $this->_offerDb;
    }

    /*
     * cart form (index - step1)
     */
    public function getForm() {
        if (null === $this->_form) {
        	$kosarica = $this->getSessionNamespace()->kosarica;        	
        	$user = Zend_Registry::get('user');
            $this->_form = new Website_Form_Cart($cart, $user);
        }
        return $this->_form;
    }

	public function indexAction() {
		$this->enableLayout();
		$this->view->cart = $this->getSessionNamespace()->cart;
	}

	public function addAction() {
		$this->enableLayout();		
		$id = intval($this->_getParam("id"));
		
		$object = null;		
    	$object = Object_Abstract::getById($id);
		$cart = $this->getSessionNamespace()->cart;
		
		
		$i = 0;
		$inserted = false;
		if(count($cart) > 0) {
			foreach($cart as $product) {
				if($product['object']->getId() == $object->getId()) {
					$cart[$i]['quantity']++;
					$inserted = true;
				}
				$i++;							
			}
		}

		$i = count($cart);
		if(!$inserted) {
			$cart[$i]['quantity'] = 1;
			$cart[$i]['object'] = $object;
			$cart[$i]['type'] = $this->_getParam("type");
		}

		$this->getSessionNamespace()->cart = $cart;
		$this->view->cart = $cart;
		$this->render('index');	
	}
	

	public function contentAction() {
	    $id = $this->_getParam("id");
	    $quantity = $this->_getParam("quantity");
	    $cart = $this->getSessionNamespace()->cart;
	    
	    $i = 0;
	    foreach($cart as $product) {
	        if($product['object']->getId() == $id) {
	            $cart[$i]['quantity'] = $quantity;
	        }
	        
	        $i++;
	    }    
	    
		$this->getSessionNamespace()->cart = $cart;
		$this->view->cart = $cart;	    
	} 


	public function contentNoeditAction() {
	    $this->view->cart = $this->getSessionNamespace()->cart;
	    $this->view->noedit = true;
	    $this->render("content");
	} 


	public function changeTypeAction() {
	    $id = $this->_getParam("id");
	    $type = $this->_getParam("type");
	    $cart = $this->getSessionNamespace()->cart;
	    
	    $i = 0;
	    foreach($cart as $product) {
	        
	        if($product['object']->getId() == $id) {
	            $cart[$i]['type'] = $type;
	        }
	        
	        $i++;
	    }    
	    
		$this->getSessionNamespace()->cart = $cart;
		$this->view->cart = $cart;
		$this->render("content");
	}	
	
	
	public function removeAction() {
		$this->enableLayout();	
		
		$i = $this->_getParam('index');
		$cart = $this->getSessionNamespace()->cart;

		
		$tmpCart = array();
		
		$j = 0;
		$incr = 0;
		foreach($cart as $product) {
			
			if($j != $i) {
				$tmpCart[$incr]['quantity'] = 1; //$product['quantity'];
				$tmpCart[$incr]['object'] = $product['object'];
				$tmpCart[$incr]['dimension'] = $product['dimension'];
				$incr++;
			}
			
			$j++;
		}
		
		
		$this->getSessionNamespace()->cart = $tmpCart;
		$this->view->cart = $this->getSessionNamespace()->cart;

		$this->render('index');
	}


    public function changeQuantityAction() {
		$cart = $this->getSessionNamespace()->cart;
		$i = $this->_getParam('index');
		$this->getSessionNamespace()->cart[$i]['quantity'] = $this->_getParam("quantity");
		$this->view->cart=$this->getSessionNamespace()->cart;
		
		$this->render('index');
	}
	
	
	/*
	 * Step 2 : if delivery else redirect to step 3
	 * if param back, go to cart index
	 */
	public function step2Action() {
		$this->enableLayout();
		
        $delivery = $this->isDelivery();		
		
		if(!$delivery && !$this->_getParam('back')) 
		    $this->_redirect('/nakup/korak3');	    
		    
		if(!$delivery && $this->_getParam('back'))
		    $this->_redirect('/cart');
		    
		$request = $this->getRequest();
		$user = Object_User::getById($this->view->user->o_id);
		
		// if delivery validate form
		if($request->isPost())
            $form = new Billing_Form_Delivery();
        else 
            $form = new Billing_Form_Delivery(null, $user);
            
		if($request->isPost() && $form->isValid($request->getParams())) {
		    $user->setName($request->getParam("name"));
		    $user->setSurname($request->getParam("surname"));
			$user->setAddress($request->getParam("address"));
			$user->setPost($request->getParam("post"));
			$user->save();
			
			$this->_redirect('/nakup/korak3');
		}		
				    
		$this->view->form = $form;
	}
	
	
	public function step3Action() {
		$this->enableLayout();
		
		
		$this->view->cart = $this->getSessionNamespace()->cart;

		$priceTotal = 0.0;
		
		foreach($this->view->cart as $item) { 
			if($item['object'] instanceof Object_Item) {			        
	        
				$j = 0;
				$price = 0.0;
				
				
				if($item['type'] == 'delivery') {
				    $price = $item['object']->getPrice() * $item['quantity'];
				} else {
				    $price = $item['object']->getPricePreorder();
				}
    				
	            $priceTotal += $price;			
			}
		}
		
		$this->view->priceTotal = $priceTotal;
		
	}

	public function setPaymentAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->getSessionNamespace()->payment = $this->_getParam("payment");

	}
	
    public function monetaAction() {
        //$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        
       //  $paymentMethod = 'MONETA';
       // $this->view->sTarifficationE = Model_App::conf()->payment->moneta->URL  . "&ConfirmationID=" . $idOrder;
    }
	
	
	private function isDelivery() {
		$delivery = false;
		if($this->getSessionNamespace()->cart) {
    		foreach ($this->getSessionNamespace()->cart as $product) {
    		    if($product['type'] == 'delivery') {
    		        $delivery = true;
    		        break;
    		    }
    		}
		}

		return $delivery;
	}


    public function priceTotalAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		
		$priceTotal = 0.0;
		foreach ($this->getSession()->cart as $item) {
			$price = $item->object->getPrice() * $item->quantity;
			
			if($this->view->hasContract) {
				if($item->object instanceof Object_Item)
					$price = 80 * $price / 100;
				else if($item->object instanceof Object_Box)
					$price -= 1;
			}

			$priceTotal += $price;
		}

		echo number_format($priceTotal,2).' €';	
	}
}