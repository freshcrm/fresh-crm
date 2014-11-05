<?php

class Billing_Tool {
	
	public static function createUPN($orderId) {
		$billingConfig = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/Billing/config.xml');
		
		$im = ImageCreateFromJpeg(PIMCORE_PLUGINS_PATH."/Billing/static/img/sepa.jpg");		
		$order = Resource_Order::getById($orderId);
		$price = $order->getPriceTotal();
        $user = $order->getUser();
        
        error_log("USR: ".$user);
		
        $orderDate = new Zend_Date();	 
        $dateStr = $orderDate->toString("ddMMyy");

		$black = ImageColorAllocate($im, 0, 0, 0);
		
		$start_x = 25;
		$start_y = 95;
		
		$fontPath =  PIMCORE_PLUGINS_PATH.'/Billing/static/fonts/FreeSans.ttf';
		
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $user->getName().' '.$user->getSurname());
		
		$start_y += 14;
		
		$address = "";
		
		if($user->getAddress());
		    $address = $user->getAddress();
		    
		if($user->getPost()) {
		    $address .= ", ".$user->getPost();
		}
		
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $address);
		
		$start_y += 34;
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $billingConfig->payment->code);
		
        
		Imagettftext($im, 12, 0, 100, $start_y, $black, $fontPath, "naročilo #".$orderId);
		
		$length = strlen(number_format($price,2));
		
		$start_y += 31;
		Imagettftext($im, 12, 0, 205 - ($length * 7), $start_y, $black, $fontPath, number_format($price,2));
		
		Imagettftext($im, 12, 0, 360, $start_y, $black, $fontPath, $billingConfig->payment->bic);
		
        $start_y += 31;
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $billingConfig->payment->trr);		
		
		
		$start_y += 32;
		Imagettftext($im, 12, 0, 26, $start_y, $black, $fontPath, "SI00");
        Imagettftext($im, 12, 0, 88, $start_y, $black, $fontPath, $orderId);
		
		$start_y += 30;
		Imagettftext($im, 12, 0, 24, $start_y, $black, $fontPath, $billingConfig->payment->to);
		
		$start_y += 14;
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $billingConfig->payment->address." ".$billingConfig->payment->post);	    		
		
		$path = PIMCORE_PLUGINS_PATH.'/Billing/upn/upn'.$orderId.'.jpg';
				
		Imagejpeg($im, $path, 100);
		ImageDestroy($im);		
	}
	
	
	public static function getConfig() {
	    return new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/Billing/config.xml');
	}
	
}