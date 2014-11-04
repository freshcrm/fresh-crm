<?php 

class User_ProfilController extends Website_Controller_Plugin {
	
    /*
	protected function enableLayout() {
		parent::enableLayout();
		Zend_Layout::getMvcInstance()->setLayoutPath(PIMCORE_DOCUMENT_ROOT.'/website/views/layouts');
		$this->view->document = Document_Page::getById(1);
	}
	*/
    
	public function init() {
		parent::init();
		
		$action = $this->getRequest()->getActionName();
		
		if(!Zend_Registry::isRegistered('user') && $action != 'registration' && $action != 'forgotten-password' && $action != 'resend-validation' && $action != 'validate' && $action != 'restore-pass') {
			
			if($this->getRequest()->getActionName() != 'login')
				$this->view->err = 3;
				
			$this->_forward('login');	
		}
	}


	/**
	 * Login
	 */
	public function loginAction() {
	     $this->enableLayout();
         $request = $this->getRequest();
         $form = new User_Form_Login();
         
         if($request->isPost() && $form->isValid($request->getParams())) {
         	 // check if user is validated
             $this->getSession()->lastLoginEmail = $this->_getParam('memail');
         	 $users = Object_User::getByEmail($this->_getParam('memail'));

         	 foreach ($users as $user)
         	 	break;
         	 		
         	 if ($user && $user->getValidation() != 1) {
         	 	$this->view->err = 4;
         	 } else {
    	         if(User_Util::autenticate($this->_getParam('memail'), $this->_getParam('mpassword'), $this->_getParam('remember')) == 1) {
    	         	if($this->getSession()->loginRedirect) {
		         		$this->_redirect($this->getSession()->loginRedirect);
		         		$this->getSession()->loginRedirect = null;
    	         	} else {
    	         		$this->_redirect('/profil');
    	         	}
		         } else {
		         	$this->view->err = 1;
		         }
         	 } // user validated              
         } 
         
	     $this->view->form = $form;
	}	
	
	
	public function indexAction() {
	    $this->enableLayout();
	    
	    $user = $this->view->user;

	    
	    // check if billing module installed
	    // @TODO
	    
	    $orders = new Resource_Order_List();
	    $orders->setCondition("userId=".$user->getId()." AND (paid IS NULL OR status IS NULL OR status = 0)");
	    $orders->load();
	    $this->view->orders = $orders->getOrders();
	    
	    	    
	}
	
    /**
     * Registration:
     * Object_User is saved to database.
     * Validation e-mail is send to user. User has to clik on link in the message, to finnish registration.
     */
	public function registrationAction () {
		$this->enableLayout();
		$request = $this->getRequest();
		
		$options = array();
		if($_GET['deliveryType']) {
			$options['deliveryType'] = $_GET['deliveryType'];
			$this->view->deliveryType = $_GET['delivery_type'];
			
			switch($_GET['deliveryType']) {
				case 'delivery':
					$this->getSession()->loginRedirect = '/nakup/dostava';
					$this->getSession()->registrationRedirect = '/nakup/dostava';					
					break;
				case 'pickup':
					$this->getSession()->loginRedirect = '/nakup/prevzem';
					$this->getSession()->registrationRedirect = '/nakup/prevzem';
					break;					
			}

		} 
		
	    $form = new User_Form_Registration($options);
	    if($request->isPost() && $form->isValid($request->getParams())) {
	    	
	        $user = User_Util::insert($request);
            $userEMail = new User_EMail();
	        $userEMail->sendValidation($user);
	        
	        $this->view->MSG = 1;
	        $this->view->email = $user->getEmail();
	        
        	// post 
        	if($this->getSession()->registrationRedirect) {
        		User_Util::autenticate($request->getParam("email"), $request->getParam("password"));
        		$this->_redirect($this->getSession()->registrationRedirect);
        		$this->getSession()->registrationRedirect = null;
        	}
	        	
	    } else {
	    	
	    	$this->view->mailErr = $form->getMessages("email");
	    	
	    	// check if validated
	    	$users = Object_User::getByEmail($request->getParam("email"));
	    	$messages = $form->getMessages("email"); 
	    	if($messages['exsists']) {
	    		foreach ($users as $user)
	    			break;
	    			
	    		if($user && $user->getValidation() != 1) 
	    			$this->view->err = 1;
	    		else if($user && $user->getValidation() == 1) 
	    			$this->view->err = 2;
	    		
	    	}
	    	
	    	$this->view->form = $form;
	    }
	    

	}
	
	/**
	 * Send validation email
	 */
	public function resendValidationAction() {
		
		
	    $email = $this->_getParam("email");
	    // check if user with email inserted
	    
	    if(!$email)
	    	$email = $this->getSession()->lastLoginEmail;
	    	
	    if(!$email)
	    	$this->view->err = 1;
	    	
	    if($email) {
	    
			$userList = Object_User::getByEmail($email);
		    foreach ($userList as $user) 
		        break;
		    
		    if($user)
		        User_Mail::sendValidation($user);
		    else 
		    	$this->view->err = 2;

		    $this->view->email = $email;
	    }
	}
	
	
	/**
	 * Validate user by key.
	 * Object_User
	 * @param GET key
	 * 
	 */
	public function validateAction() {
	    $this->enableLayout();
	    $key = $this->_getParam("key");
	    
	    // 
		$userList = Object_User::getByValidation($key);


	    foreach ($userList as $user) {
	        break;
	    }
	    
	    
	    if($user) {
	    	$config = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/User/config.xml');
            $user->setValidation(1);
            $user->setParentId($config->object->registeredFolderId);
            
            try {
                $user->save();
                $this->view->user = $user;
                Zend_Registry::set("user", $user);
            } catch (Exception $e) {
                error_log("ERROR validation: ");
            }
            
            $this->view->MSG = 1;
        }  else {
            $this->view->MSG = 0;
        }
        
        
	}
	
	

	
	/**
	 * Forgoten password
	 */
	public function forgottenPasswordAction() {
		$this->enableLayout();
		
		
		$request = $this->getRequest();
		$form = new User_Form_SendPassword();
		
		
		if($request->isPost() && $form->isValid($request->getParams())) {
			
			$list = Object_User::getByEmail($request->getParam("email"));

			if(count($list) > 0) {
				foreach ($list as $user)
					break;
				
				$email = $request->getParam("email");
				
				if($user->getEmail() == $email) {
					$hash = substr(md5(time()), 0,10);
					$user->setRestorePassHash($hash);
					$user->save();
					
					
					User_Mail::sendForgottenPassword($user);				
					$this->view->MSG = 1;
					$this->view->email = $email;
				} else {
					// this shouldt happen
					$this->view->MSG = 3; 
				} 
			} else {
				$this->view->MSG = 2;
			}
		} else {
			$this->view->form = $form;
		}
				
	}
	
	/**
	 * Change forgotter password
	 */
	public function changePasswordAction() {
		$this->enableLayout();
		$form = new User_Form_ChangePassword();
		$request = $this->getRequest();
		
		if($request->isPost() && $form->isValid($request->getParams())) {
			$user = Zend_Registry::get("user");
			$user->setPassword($request->getParam("password"));
			$user->save();
			
			$this->view->msg = 1;
		}
		
		$this->view->form = $form;
	}
	
	
	/**
	 * 
	 * Logout
	 */
    public function logoutAction() 
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        $this->_redirect('/');    
    }
    
    
    
    public function editAction() {
        $this->enableLayout();
        $request = $this->getRequest();
        $user = Zend_Registry::get("user");
        
        if($request->isPost())
            $form = new User_Form_Profil();
        else {
            $form = new User_Form_Profil(null, $user);
        } 

        if($request->isPost() && $form->isValid($request->getParams())) {
            
            $user->setName($request->getParam("name"));
            $user->setSurname($request->getParam("surname"));

            $user->setAddress($request->getParam("address"));
            $user->setPost($request->getParam("post"));
            
            // $user->setPhone($request->getParam("phone"));
            // $user->setGsm($request->getParam("gsm"));
            
            try {
            	$user->save();
            	
            } catch(Exception $e) {
            	error_log("ERROR User_ProfilController/edit: ".$e->getMessage());
            }
            
            $this->view->msg = 1;
        }        

        $this->view->user = $user;
        $this->view->form = $form;
    }
    
    public function boxAction() {
    	$this->enableLayout();
    	$request = $this->getRequest();
    	
    	
    	if($request->isPost()) {
    		$contract = Resource_Contract::getById($request->getParam("id"));
    		$contract->setPeriod($request->getParam("period"));
    		$contract->setBoxId($request->getParam("boxId"));
    		$contract->setDefaultPayment($request->getParam("defaultPayment"));
    		$contract->setDeliveryType($request->getParam("deliveryType"));
    		$contract->setNote($request->getParam("note"));
    		$contract->save();
    		
    		$this->view->MSG = 1;
    	}
    	
    	
    	$boxList = new Object_Box_List();
    	$this->view->boxList = $boxList;
    	
    	
    	$list = new Resource_Contract_List();
    	$contracts = $list->getContractsByUserId($this->view->user->getId());
    	$bc = array();
    	foreach ($contracts as $contract) {
    		$boxContract = new stdClass();
    		$boxContract->contract = $contract;
    		$boxContract->box = Object_Box::getById($contract->getBoxId());
    		
    		$bc[] = $boxContract;
    	}
    	
    	$this->view->boxContracts = $bc;
    }
    
    
    public function ordersAction() {
    	$this->enableLayout();
	    $orders = new Resource_Order_List();
	    $orders->setCondition("userId=".$this->view->user->getId()." AND paid IS NOT NULL AND status > 0");
	    $orders->load();
	    $this->view->orders = $orders->getOrders();
    }
    
    public function testAction() {
        $this->_helper->viewRenderer->setNoRender(true);
        User_Util::getAuthAdapter();    
    }
    
    
    public function restorePassAction() {
    	$this->enableLayout();
		$request = $this->getRequest();
		
    	$hash = $request->getParam("hash");
    	$users = Object_User::getByRestorePassHash($hash);
    	
    	foreach ($users as $user)
    		break;
    		
    	if($user) {
    		$form = new User_Form_RestorePass($user, $hash);
    		
    		if($request->isPost() && $form->isValid($request->getParams())) {
    			$user->setPassword(md5($request->getParam("password")));
    			$user->save();
    			
    			$this->view->MSG = 1;
    		}
    		
    		$this->view->form = $form;
    		
    	} else {
    		$this->view->err = 1;
    	}
    	
    }
    
}