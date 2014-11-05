<?php 

class Billing_PaymentController extends Website_Controller_Plugin {
	
	protected $_config;
	
	public function init() {
		parent::init();
		$this->_config = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/Billing/config.xml');
	}
	
	/*
	public function upnAction() {
		$this->enableLayout();
		$order = new Resource_Order();
	}
	*/
	
	/*
	 * insert order UPN
	 */
	public function upnAction() {
	    $this->enableLayout();
	    $request = $this->getRequest();
	    // insert order / dump cart
		$order = new Resource_Order();
		$paymantMethod = 'UPN';
		$order->setPaymentMethod($paymantMethod);
		$user = Zend_Registry::get("user");
		$order->setUser($user);
		$order->setType(strtoupper($this->getSession()->delivery_type));
		$order->setPriceTotal($this->getCartPriceTotal());
		
		if($request->getParam('isentity')) {
			$order->setEntity(1);
		}
		
		try {
			$order->save();
			error_log("ORDERS SAVED");
			// create UPN image
			
			// 
			foreach($this->getSession()->cart as $product) {
			    $data = array(
			        "order_id"	    => $order->getId(),
			        "product_id"	=> $product->object->getId(),
			        "quantity"		=> $product->quantity
			    );
			    
			    if($this->view->hasContract)
			    	$data['discount'] = 20.0;
			    else 
					$data['discount'] = 0.0;
			    
			    $order->insertItem($data);    
			}

			
			Billing_Tool::createUPN($order->getId());
			$this->createBillPdfAction($order->getId());
			Billing_Mail::send($order->getId());
			
			$this->_redirect('/success/'.$order->getId());
		} catch (Exception $e) {
		    
		    error_log($e->getMessage());
		    
		    $this->_helper->viewRenderer->setNoRender(true);
			error_log("Billing_Payment/UPN: ".$e->getMessage());
		}
	    
	}

	
	public function onDeliveryAction() {
		$this->enableLayout();
	    $request = $this->getRequest();
	    // insert order / dump cart
		$order = new Resource_Order();
		$paymantMethod = 'CASH';
		$order->setPaymentMethod($paymantMethod);
		$user = Zend_Registry::get("user");
		$order->setUser($user);
		$order->setType(strtoupper($this->getSession()->delivery_type));
		$order->setPriceTotal($this->getCartPriceTotal());
		
		if($request->getParam('isentity')) {
			$order->setEntity(1);
		}
		
		try {
			$order->save();
			error_log("ORDERS SAVED");
			// create UPN image
			
			// 
			foreach($this->getSession()->cart as $product) {
			    $data = array(
			        "order_id"	    => $order->getId(),
			        "product_id"	=> $product->object->getId(),
			        "quantity"		=> $product->quantity
			    );
			    
			    if($this->view->hasContract)
			    	$data['discount'] = 20.0;
			    else 
					$data['discount'] = 0.0;
			    
			    $order->insertItem($data);    
			}

			
			
			$this->createBillPdfAction($order->getId());
			Billing_Mail::send($order->getId());
			
			$this->_redirect('/success/'.$order->getId());
		} catch (Exception $e) {
		    
		    error_log($e->getMessage());
		    
		    $this->_helper->viewRenderer->setNoRender(true);
			error_log("Billing_Payment/UPN: ".$e->getMessage());
		}		
	}

	
	private function getCartPriceTotal() {
	    $priceTotal = 0.0;
		foreach ($this->getSession()->cart as $product) {
			$price = $product->object->getPrice() * $product->quantity;
			
			if($this->view->hasContract) {
				if($product->object instanceof Object_Item)
					$price = 80 * $price / 100;
				else 
					$price -= 1;
			}
			
	        $priceTotal += $price;
		}
		
		if(strtoupper($this->getSession()->delivery_type) == 'DELIVERY') {
	    	$priceTotal += floatval($this->_config->delivery_price);
	    }		

		return $priceTotal;
	}
	
	public function monetaAction() {
        // $this->_helper->layout->disableLayout();
        // $this->_helper->viewRenderer->setNoRender(true); 

	    /*
        $paymentMethod = 'MONETA';
        $orderDb = new Webiste_Billing_Data();
        $idOrder = $orderDb->insertOrder($paymentMethod);
        $this->view->sTarifficationE = Model_App::conf()->payment->moneta->URL  . "&ConfirmationID=" . $idOrder;
        */
        
		$this->enableLayout();
	    $request = $this->getRequest();
		$order = new Resource_Order();
		$paymantMethod = 'MONETA';
		$order->setPaymentMethod($paymantMethod);
		$userSession = Zend_Registry::get("user");
		$user = Object_User::getById($userSession->o_id);
		
		$order->setUser($user);
		$order->setPriceTotal($this->getCartPriceTotal());
		$order->setType("BUY");
		
		$date = new Zend_Date();
		
		// $order->setPaid($date->get(Zend_Date::TIMESTAMP));
		
		
		if($request->getParam('isentity')) {
			$order->setEntity(1);
			
			// update users entity
			
			$user->setEntity($request->getParam("entity"));
			$user->setVat($request->getParam("vat"));
			
			try {
				$user->save();
			} catch (Exception $e) {
				error_log("Billing_Payment/moneta (update users entity): ".$e->getMessage());
			}
		}
		
		try {
			$order->save();
            $this->view->sTarifficationE = "http://test.moneta.mobitel.si/placevanje/TarifficationE.dll?TARIFFICATIONID=1748&ConfirmationID=". $order->getId();
		} catch (Exception $e) {
			error_log("Credit/Add: ".$e->getMessage());
		}

		
	}
	
	
	
	public function buyMonetaAction() {
	    // zgeneriraj ko klices 1. html z meta podatki cena pa to in se mora cena 
	    // status
	    // ce ne pa amount
	    
	    
	}
	
	
	public function confirmMonetaAction() {
	    
	    /*
	     * 
			// 
			foreach($this->getSession()->cart as $product) {
			    $data = array(
			        "order_id"	    => $order->getId(),
			        "product_id"	=> $product['object']->getId(),
			        "discount"		=> 0.0,
			        "type"			=> $product['type'],
			        "quantity"		=> $product['quantity']
			    );

			    $order->insertItem($data);    
			}
			
			 
	     */
	    
	    
	    
	
	
	
	}
	
	
	
		
	/**
	 * 
	 * Confirm order - paid
	 */
	public function confirmAction() {
		$response = array();
		
		$id = $this->_getParam('id');
		$order = Resource_Order::getById($id);
		
		$date = new Zend_Date();
		$order->setPaid($date->get(Zend_Date::TIMESTAMP));
		
		try {
			$order->save();
			$response['success'] 	= true;
			$response['date']		= $order->getPaid();
			
			// update user balance
			$user = $order->getUser();
			
			// create bill
			$this->createBillPdfAction($order->getId());
			
			try {
				$user->save();
			} catch(Exception $e) {
				error_log('Billing_Payment/confirm: '.$e->getMessage());
			}
			
		} catch(Exception $e) {
			$response['success'] = false;
			error_log("Billing_Payment/confirm: ".$e->getMessage());
		}
		
		return $this->_helper->json($response);
	}


    public function cancelAction() {
        $response = array();
        $response['test'] = "test";


        $id = $this->_getParam('id');
        $order = Resource_Order::getById($id);

        $order->setStatus(-1);
        $order->setPaid(NULL);


        try {
            $order->save();
            $response['success'] 	    = true;
            $response['order_status']   = $order->getStatus();

            // update user balance
            // $user = $order->getUser();

            // create bill
            // TODO: SET STORNO ON PDF
            // $this->createBillPdfAction($order->getId());


        } catch(Exception $e) {
            $response['success'] = false;
            error_log("Billing_Payment/cancel: ".$e->getMessage());
        }


        return $this->_helper->json($response);
    }



	public function getBillAction() {
	    error_log("CREATE BILL");
		$folder = Asset_Folder::getById($this->_config->bill_folder->getId());
		$fileName = $this->_getParam("id").'.pdf';
		$asset = Asset_Document::getByPath($folder.'/'.$fileName);
        $this->createBillPdfAction($this->_getParam("id"));
        $asset = Asset_Document::getByPath($folder.'/'.$fileName);

		if($asset instanceof Asset_Document) {
			$response['success'] = true;
			$response['asset'] = $asset->getId();
		} else {
			$response['success'] = false;
		}
		
		return $this->_helper->json($response);
	}
	
	
	

	public function successAction() {
		$this->enableLayout();
	    
	    if($this->getSession()->cart) {
	        $this->view->cart = $this->getSession()->cart;
	        $this->view->order = $this->getSession()->order;
	        $this->view->paymentMethod = $this->getSession()->paymentMethod;
	        unset($this->getSession()->cart);
	    } 
	        	    
		// order id
		$id = $this->_getParam("id");
		$order = Resource_Order::getById($this->_getParam("id"));
		$this->view->order = $order;
		$list = new Resource_Order_List();
		$this->view->items = $list->getItems($this->_getParam("id"));
		
		
		
	}


	
	/**
	 * 
	 * Creates pdf bill of order data
	 * @param integer $orderId
	 */	
	public function createBillPdfAction($orderId) {



		$this->_helper->viewRenderer->setNoRender(true);
        $locale = Zend_Registry::get("Zend_Locale");
        $translator = new Pimcore_Translate($locale);

        // get user and order from resources
        $order = Resource_Order::getById($orderId);
        $user = $order->getUser();

        // output
        $filename  = $orderId.'.pdf';
        $file = PIMCORE_PLUGINS_PATH."/Billing/pdf/".$filename;



        $logoPath = PIMCORE_DOCUMENT_ROOT."/static/img/logo.png";

        $color1 = new Zend_Pdf_Color_Html('#3366FF');
        $color2 = new Zend_Pdf_Color_Html('#9C764E');

        try {
            Zend_Loader::loadClass('Zend_Pdf');                                          // load pdf library
            $pdf = Zend_Pdf::load(PIMCORE_PLUGINS_PATH."/Billing/static/pdf/bg.pdf");    // load bg

            $y = 750;                                                                    // initial top corner
            $x = 58;

            // @todo make more pages ...
            $page = $pdf->pages[0];

            // define font resource
            // @TODO: not working on local ????
            $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
            $font = Zend_Pdf_Font::fontWithPath(PIMCORE_PLUGINS_PATH.'/Billing/static/fonts/FreeSans.ttf');
            $page->setFillColor(new Zend_Pdf_Color_Rgb(71/255, 0, 0));

            $x2 = $x + 300;

            ///////////////////////////////////////////////////////////////////////////////////////////
            // BILL ID
            ///////////////////////////////////////////////////////////////////////////////////////////
            $date = new Zend_Date();
            $page->setFont($font,14)
                ->drawText($translator->translate("RAČUN ŠT.").': ST-'.$orderId.'/'.$date->toString('yyyy'), $x2, $y-30,"utf-8");
            $y -= 16;

            ///////////////////////////////////////////////////////////////////////////////////////////
            // DATE
            ///////////////////////////////////////////////////////////////////////////////////////////
            $page->setFont($font, 12);
            $date = new Zend_Date($order->getPaid());
            $page->drawText($translator->translate("Datum izdaje").': '.$date->toString("dd.MM.yyyy"), $x2, $y-30,"utf-8");
            $y -= 14;

            /*
            $imageInfo = getimagesize($logoPath);
            $image = Zend_Pdf_Image::imageWithPath($logoPath);
            $page->drawImage($image, $x, $y - $imageInfo[1], $x+$imageInfo[0], $y);
            $y -= $imageInfo[1] + 90;
            */
            ///////////////////////////////////////////////////////////////////////////////////////////
            // entity or user
            ///////////////////////////////////////////////////////////////////////////////////////////
            $y1 = $y;

            $page->drawText($translator->translate('Naročnik').':', $x, $y1, "utf-8");
            $y1 -= 14;

            if($order->getEntity()) {
                $page->drawText($user->getEntity(), $x, $y1,"utf-8");
                $y1 -= 14;
                $page->drawText($user->getVat(), $x, $y1,"utf-8");
                $y1 -= 14;
            } else {
                $page->drawText($user->getName().' '.$user->getSurname(), $x, $y1,"utf-8");
                $y1 -= 14;
            }

            if($user->getAddress()) {
                $page->drawText($user->getAddress(), $x, $y1,"utf-8");
                $y1 -= 14;
            }

            if($user->getPost()) {
                $page->drawText($user->getPost(), $x, $y1,"utf-8");
                $y1 -= 14;
            }


            ///////////////////////////////////////////////////////////////////////////////////////////
            // table header
            ///////////////////////////////////////////////////////////////////////////////////////////
            $y = $y1;
            $y -= 20;

            $page->setFont($font,10);
            $page->drawText("ZŠ", $x, $y, 'utf-8');
            $page->drawText($translator->translate("Vrsta blaga"), $x + 30, $y,"utf-8");
            $page->drawText($translator->translate("Kol."), $x + 180, $y,"utf-8");
            $page->drawText($translator->translate("Cena brez"), $x+210, $y,"utf-8");
            $page->drawText("DDV", $x+210, $y-12,"utf-8");
            $page->drawText($translator->translate("Stopnja"), $x+270, $y,"utf-8");
            $page->drawText("DDV", $x+270, $y-12,"utf-8");
            $page->drawText($translator->translate("Cena z"), $x+310, $y,"utf-8");
            $page->drawText("DDV", $x+310, $y-12,"utf-8");

            if($this->view->hasContract) {
                $page->drawText($translator->translate("Cena s"), $x+370, $y,"utf-8");
                $page->drawText("popustom", $x+370, $y-12,"utf-8");
            }

            $page->drawText($translator->translate("Cena v €"), $x+430, $y,"utf-8");
            $page->drawText("Skupaj", $x+430, $y-12,"utf-8");

            $y -= 14;
            $page->setLineWidth(0.5);
            $page->drawLine($x, $y, $x + 480, $y);

            $list = new Resource_Order_List();
            $items = $list->getItems($orderId);

            $totalTaxRate1 = 0.0;
            $totalBaseRate1 = 0.0;

            $totalTaxRate2 = 0.0;
            $totalBaseRate2 = 0.0;

            $totalTax = 0.0;
            $priceTotal = 0.0;

            $discountTotal = 0.0;

            $i = 0;
            foreach ($items as $item) {
                $i++;
                $y -= 12;

                // number
                $page->drawText($i, $x, $y,"utf-8");

                // title
                $page->drawText(substr($item['object']->getTitle(), 0, 25), $x+30, $y,"utf-8");

                //quantity
                $page->drawText($item['quantity'], $x+180, $y,"utf-8");

                // price no tax
                $priceNoTax = ($item['object']->getPrice() * (100 - $item['object']->getTaxRate())) / 100;
                $page->drawText(number_format($priceNoTax,2)." ".$this->_config->currency, $x+210, $y,"utf-8");

                // tax rate
                $page->drawText($item['object']->getTaxRate()." %", $x+270, $y,"utf-8");

                // price
                $page->drawText(number_format($item['object']->getPrice(), 2)." €", $x+310, $y,"utf-8");

                if($this->view->hasContract) {
                    // price discount
                    if($item['object'] instanceof Object_Item)
                    {
                        $page->drawText(number_format(80 * $item['object']->getPrice() / 100, 2)." €", $x+370, $y,"utf-8");
                        $discountTotal += 20 * $item['object']->getPrice() / 100;
                    }
                    else
                    {
                        $page->drawText(number_format($item['object']->getPrice() - 1, 2)." €", $x+370, $y,"utf-8");
                        $discountTotal += 1;
                    }
                }

				$price = $item['object']->getPrice() * $item['quantity'];

				if($this->view->hasContract) {
					if($item['object'] instanceof Object_Item)
						$price = 80 * $price / 100;
					else
						$price -= 1;
				}

				$priceTotal += $price;

				// priceTotal
				$page->drawText(number_format($price,2)." ".$this->_config->currency, $x+430, $y,"utf-8");

				$tax = 0.0;
                $tax = ($item['object']->getTaxRate() * $price) / 100;

                // @todo: change to array ... key index
				switch($item['object']->getTaxRate()) {
					case $this->billigconf->tax->rate1:
						$totalBaseRate1 += $price;
						$totalTaxRate1 += $tax;
						break;
					case $this->billigconf->tax->rate2:
						$totalBaseRate2 += $price;
						$totalTaxRate2 += $tax;
						break;
				}

				$totalTax += $tax;

				$y -= 2;
				$page->drawLine($x, $y, $x + 480, $y);

			}


			///////////////////////////////////////////////////////////////////////////////////////////////////////
            // DELIVERY
            // additional price
            ///////////////////////////////////////////////////////////////////////////////////////////////////////
			if($order->getType() == 'DELIVERY') {

				$i++;
				$y -= 12;

				// number
				$page->drawText($i, $x, $y,"utf-8");

				// title
				$page->drawText($translator->translate("DELIVERY"), $x+30, $y,"utf-8");

				//quantity
				$page->drawText(1, $x+180, $y,"utf-8");

				// price no tax
				$priceNoTax = 1;
				$page->drawText(number_format($priceNoTax,2)." ".$this->_config->currency, $x+210, $y,"utf-8");

				// tax rate
				$page->drawText("20 %", $x+270, $y,"utf-8");

				// price
				$page->drawText(number_format($this->_config->delivery_price, 2)." €", $x+310, $y,"utf-8");

				$price = floatval($this->_config->delivery_price);

				$priceTotal += $price;

				// priceTotal
				$page->drawText(number_format($price,2)." ".$this->_config->currency, $x+430, $y,"utf-8");


				$tax = 0.0;

				$tax = ($item['object']->getTaxRate() * $price) / 100;
				$totalBaseRate2 += 1;
				$totalTaxRate2 += 0.20;

				$totalTax += $tax;

				$y -= 2;
				$page->drawLine($x, $y, $x + 480, $y);
			}
			///////////////////////////////////////////////////////////////////////////////////////////////////////


            //////////////////////////////////////////////////////////////
            // DISCOUNT
            //////////////////////////////////////////////////////////////
			if($this->view->hasContract) {
				$y -= 14;
				$page->drawText("Popust skupaj: ", $x+300, $y, "utf-8");
				$page->drawText(number_format($discountTotal,2)." ".$this->_config->currency, $x+430, $y, "utf-8");

			}


            //////////////////////////////////////////////////////////////
            // TAX RATE
            //////////////////////////////////////////////////////////////
			if($totalTaxRate1 > 0.0) {
				$y -= 14;
				$page->drawText("Osnova (".$this->billigconf->tax->rate1." %): ", $x+300, $y, "utf-8");
				$page->drawText(number_format($totalBaseRate1,2)." ".$this->_config->currency, $x+430, $y, "utf-8");
				$y -= 14;
				$page->drawText("DDV: ", $x+300, $y, "utf-8");
				$page->drawText(number_format($totalTaxRate1,2)." ".$this->_config->currency, $x+430, $y, "utf-8");
			}


			if($totalTaxRate2 > 0.0) {
				$y -= 14;
				$page->drawText("Osnova (".$this->billigconf->tax->rate2." %): ", $x+300, $y, "utf-8");
				$page->drawText(number_format($totalBaseRate2,2)." ".$this->_config->currency, $x+430, $y, "utf-8");
				$y -= 14;
				$page->drawText("DDV: ", $x+300, $y, "utf-8");
				$page->drawText(number_format($totalTaxRate2,2)." ".$this->_config->currency, $x+430, $y, "utf-8");
			}

			/*
			$y -= 14;
			$page->drawText($translator->translate("TAX_TOTAL").": ", $x+330, $y,"utf-8");
			$page->drawText(number_format($totalTax,2)." ".$this->_config->currency, $x+430, $y, "utf-8");
			*/



			$y -= 18;
			$page->setFont($font, 14);
			$page->drawText("Skupaj za plačilo: ", $x + 280, $y, "utf-8");
			$page->drawText(number_format($priceTotal,2)." ".$this->_config->currency, $x+430, $y, "utf-8");


			$page->drawLine($x, $y-2, $x + 480, $y-2);

			/*
			$x1 = $x;
			$y2 = $y;
			$y = $y1;

			$page->setFont($font, 18);
			$page->drawText($translator->translate('FOR'), $x2, $y, "utf-8");
			$y -= 20;

			$page->drawText($this->billconfig->payment_to, $x2, $y,"utf-8");
			$y -= 18;
			$page->drawText($this->billconfig->payment_address, $x2, $y,"utf-8");
			$y -= 18;
			$page->drawText($this->billconfig->payment_post, $x2, $y,"utf-8");
			$y -= 18;

			$y = $y2;


			$y -= 50;
			*/

			//$page->drawLine($x, $y, $x + 480,$y);
			/*
			$y1 = $y - 20;
			$page->drawLine($x2, $y, $x2, $y1);

			$y -= 16;




			$page->drawText($translator->translate("DESCRIPTION"), $x+10, $y,"utf-8");
			$page->drawText($translator->translate("PRICE"), $x2+10, $y,"utf-8");

			$page->drawLine($x, $y1, $x + 480, $y1);


			$y -= 20;



			$y -= 18;
			$page->drawText($translator->translate("TOGETHER").':', $x+10, $y,"utf-8");
			$page->drawText($order->getPriceTotal()." ".$this->_config->currency, $x2+10, $y,"utf-8");

			$y -= 2;
			$page->drawLine($x2, $y1, $x2, $y);

			*/


			// $pdf->pages[] = $page;


			$pdf->save($file);

            // create asset folder for post assets
        	$assetPdf = Asset::create(
        		$this->billigconf->system->bill_folder_unpaid_id,
        		array(
        			  "filename"         => $orderId.'.pdf',
        			  "userOwner"        => 1,
        		      "type"			 => 'document',
        			  "data"			 => file_get_contents($file),
        			  "userModification" => 1
        		)
        	);

        	$assetPdf->save();
		} catch (Zend_Pdf_Exception $e) {
			   error_log('PDF error: ' . $e->getMessage());
		} catch (Exception $e) {
			   error_log('Application error: ' . $e->getMessage());
		}

	}

	public function orderDetailsAction() {
	    
		$id = intval($this->_getParam("id"));
		if($id) {
		    $order = Resource_Order::getById($id);
			$this->view->order = $order;
			$list = new Resource_Order_List();

			$this->view->items = $list->getItems($this->_getParam("id"));
			
			if($this->_getParam("jqmCloseButton"))
			    $this->view->jqmCloseButton = 1;
		}
		
	}
	
	/**
	 * 
	 * Creates image of UPN with order data
	 * @param integer $orderId
	 */
	public static function createUPN($orderId) {
        /*
		$im = ImageCreateFromJpeg(PIMCORE_PLUGINS_PATH."/Billing/static/img/sepa.jpg");
		
		$orderDb = new Website_Billing_Data();
		$order = $orderDb->getOrderById($orderId);        
		$price = $order['price_total'];
		
		//get user from order
		// if $order['member_id'] = null / critical err
		
        $user = Object_User::getById($order['member_id']);
		
        if($order['start_date'])
        	$orderDate = new Zend_Date(strtotime($order['start_date']));
        else 
        	$orderDate = new Zend_Date(); 
        	
        $dateStr = $orderDate->toString("ddMMyy");

		$black = ImageColorAllocate($im, 0, 0, 0);
		
		$start_x = 25;
		$start_y = 95;
		
		$fontPath =  PIMCORE_DOCUMENT_ROOT.'/static/fonts/FreeSans.ttf';
		
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
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $config->payment->code);
		
        
		Imagettftext($im, 12, 0, 100, $start_y, $black, $fontPath, "naročilo #".$orderId);
		
		$length = strlen(number_format($price,2));
		
		$start_y += 31;
		Imagettftext($im, 12, 0, 205 - ($length * 7), $start_y, $black, $fontPath, number_format($price,2));
		
		Imagettftext($im, 12, 0, 360, $start_y, $black, $fontPath, $this->_config->bic);
		
        $start_y += 31;
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $this->_config->trr);		
		
		
		$start_y += 32;
		Imagettftext($im, 12, 0, 26, $start_y, $black, $fontPath, "SI00");
        Imagettftext($im, 12, 0, 88, $start_y, $black, $fontPath, $orderId);
		
		$start_y += 30;
		Imagettftext($im, 12, 0, 24, $start_y, $black, $fontPath, $this->_config->payment_to);
		
		$start_y += 14;
		Imagettftext($im, 12, 0, $start_x, $start_y, $black, $fontPath, $this->_config->payment_address." ".$this->_config->payment_post);
	    		
		
		$path = PIMCORE_DOCUMENT_ROOT.'/upn/upn'.$orderId.'.jpg';
		
		Imagejpeg($im, $path, 100);
		ImageDestroy($im);
        */
	}

	
	public function setStatusAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$order = Resource_Order::getById($this->_getParam("id"));
		$order->setStatus($this->_getParam("status"));
		$order->save();
	}
	
	
	public function testAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		$this->createBillPdfAction(32);
		echo "DONE";
	}
}