<?php

class Billing_Mail {
    
  	protected $_namespace;
    protected $_session;

    
    function __construct() {
        $this->_namespace = "plugin_billing";
    }
    
    
    /**
     * Get the session namespace we're using
     *
     * @return Zend_Session_Namespace
     */
    public function getSessionNamespace() {
        if (null === $this->_session) {
            $this->_session = new Zend_Session_Namespace($this->_namespace);
        }
        return $this->_session;
    }
      
    /**
     * 
     * Send order details for upn payment to member
     * @param int $orderId
     * 
     */
    public function send($orderId) {
        $debug = false;


    	$billingConfig = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/Billing/config.xml');
        
        $order = Resource_Order::getById($orderId);

        if($debug) {
            echo $order->getPriceTotal()."\n";
            echo PIMCORE_PLUGINS_PATH.'/Billing/config.xml'."\n";
            echo $billingConfig->email->smtp."\n";
            echo $billingConfig->email->username."\n";
            echo $billingConfig->email->password."\n";
        }

        $user = $order->getUser();
        $smtpServer = $billingConfig->email->smtp;
        $username = $billingConfig->email->username;
        $password = $billingConfig->email->password;
        $port = 587;
        			        				
        $config = array(
            'ssl' => 'tls',
            'auth' => 'login',
            'username' => $username,
            'password' => $password,
            'port' => $port
        );
        		
        $tr = new Zend_Mail_Transport_Smtp($smtpServer, $config);
        Zend_Mail::setDefaultTransport($tr);			
        
    	$mail = new Zend_Mail("utf-8");
    	$mail->addTo($user->getEmail());
    	
    	$mail->setFrom($billingConfig->email->from,$_SERVER['HTTP_HOST']);
    	$mail->setReplyTo($billingConfig->email->replyto);
    	$mail->setSubject("Naročilo ".$order->getId()." ".$_SERVER['HTTP_HOST']);
    		
    	//$mail->setBodyHtml($this->createCartContentMailBody());
    	
    	$bodyText = "
Na spletni strani ".$_SERVER[HTTP_HOST]." ste izvedli naročilo.";
    	
    	if($order->getPaymentMethod() == "UPN") {
    		$bodyText .= "
V priponki najdete račun in primer položnice. 
";	
		} else {
    		$bodyText .= "
Podrobnosti najdete v priponki. 
";			
		}


    	if($order->getType() == "PICKUP") {
			$bodyText .= "
Svoje naročilo lahko prevzamete v sredo med 10. in 17. uro, na Valvasorjevi ulici 42, na Studencih, v Mariboru.
";
    	}
    	
    	
    	$bodyText .= "

Hvala za zaupanje,
Zadruga Dobrina
    	



";
    	
    	$mail->setBodyText($bodyText);
    	
    	if($order->getPaymentMethod() == "UPN") {
	    	// attach upn image
			$file_name = 'upn'.$orderId.'.jpg';
			$file_type = "image/jpg"; 
			$file_body = file_get_contents(PIMCORE_PLUGINS_PATH.'/Billing/upn/'.$file_name);
			
			$at = $mail->createAttachment($file_body);
			$at->type = $file_type;
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding    = Zend_Mime::ENCODING_BASE64;
			$at->filename    = $file_name;
    	} 	
		
    	// attach pdf
		$file_name = $orderId.'.pdf';
		$file_type = "application/pdf"; 
		$file_body = file_get_contents(PIMCORE_PLUGINS_PATH.'/Billing/pdf/'.$file_name);
		
		$at1 = $mail->createAttachment($file_body);
		$at1->type = $file_type;
		$at1->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at1->encoding    = Zend_Mime::ENCODING_BASE64;
		$at1->filename    = $file_name; 			
		    	 
    	try {
    	    $mail->send();
    	} catch(Exception $e) {
    		error_log("upn not sent: " + $e->getMessage());
    	}    		     
    }
}