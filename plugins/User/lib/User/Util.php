<?php

class User_Util {
    
    /**
     * 
     * Insert new user
     * @param Zend_Request $request
     * @return Object_User
     */
	public static function insert($request, $sendValidation = true) {
		$config = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/User/config.xml');
		
	    $user = new Object_User();
	   	$user->setCreationDate(new Zend_Date());	
		$user->setUserOwner(1);
		$user->setUserModification(1);
		$user->setPublished(true);
		
		$user->setName($request->getParam("name"));
		$user->setSurname($request->getParam("surname"));
		$user->setEmail($request->getParam("email"));
		$user->setPhone($request->getParam("phone"));
		
		if($request->getParam("visiblePassword")) {
			$user->setPassword(md5($request->getParam("visiblePassword")));
			$user->setVisiblePassword($request->getParam("visiblePassword"));
		} else {
			$user->setPassword(md5($request->getParam("password")));
		}
		
		$user->setAddress($request->getParam("address"));
		$user->setPost($request->getParam("post"));
		$user->setEntity($request->getParam("entity"));
		$user->setVat($request->getParam("vat"));
		
		if($sendValidation)
			$user->setValidation(uniqid());
						
		$list = new Object_User_List();
		$list->setUnpublished(true);
		    
		$key = strtolower(Website_Tool_Text::toUrl($user->getSurname()."-".$user->getName())."-".$list->count());
		$user->setKey($key);
		
		if($sendValidation)
			$user->setParentId($config->object->unvalidatedFolderId);
		else {
			if($request->getParam("visiblePassword")) {
				$user->setParentId($config->object->registeredFolderId);
				$user->setValidation(1);
			} else
				$user->setParentId($config->object->noEmailFolderId);
		}
			
		try {
			$user->save();
			error_log("user SAVED: ".$user->getEmail());
		} catch(Exception $e) {
			error_log("user NOT SAVED Error: .".$e->getMessage()." ");
			error_log($request->getParam("name")." ".$request->getParam("surname").",".$request->getParam("email"));
		}

        return $user;		
	}
	
	
	/**
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public static function autenticate($email, $password, $remember = 1) {
        $config = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/User/config.xml');
        $sysconfig = Pimcore_Config::getSystemConfig();

        $auth = Zend_Auth::getInstance();
        
        $db = new Zend_Db_Adapter_Pdo_Mysql(array(
            'host'     => $sysconfig->database->params->host,
            'username' => $sysconfig->database->params->username,
            'password' => $sysconfig->database->params->password,
            'dbname'   => $sysconfig->database->params->dbname
        ));
		
		
        $authAdapter = new Zend_Auth_Adapter_DbTable($db);
        $authAdapter->setTableName('object_'.$config->object->id)
        			->setIdentityColumn('email')
        			->setCredentialColumn('password')
        			->setCredentialTreatment('MD5(?)');

		$authAdapter->setIdentity($email);
 		$authAdapter->setCredential($password);
       
 		$result = $auth->authenticate($authAdapter);
 		
 		
 		if($result->isValid()) {
 			$identity = $authAdapter->getResultRowObject(null, 'password');
		 	if($remeber = 1) {
		 		// @TODO save to cookie
		    	$seconds = 60 * 60 * 24 * 7; // 7 days
		    	Zend_Session::rememberMe($seconds);
		   	} 
		   	
 		    $user = Object_User::getById($identity->o_id);

 		    if($user->isPublished()) {
		        $authStorage = $auth->getStorage();
		        $sessionData = new stdClass();
		        $sessionData->o_id = $user->getId();
		        $sessionData->email = $user->getEmail();
		        $sessionData->name = $user->getName();
		        $sessionData->surname = $user->getSurname();
		        $authStorage->write($sessionData);
	
		        // We're authenticated! 
		   		return 1;
	        } else {
                return -2;
            }
 		} else {
 			return -1;
 		}
	}
}
