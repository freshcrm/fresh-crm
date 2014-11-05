<?php

class Resource_Order extends Pimcore_Model_Abstract
{

    /**
     * @var integer
     */
    public $id = 0;

    /**
     *
     * @var integer
     */
    public $paymentId;
    
    
    /**
     * @var integer
     */
    public $entity;
    
    /**
     *
     * @var string
     */
    public $user;
    
    /**
     * @var integer
     */
    public $userId;    
    
    /**
     *
     * @var double
     */
    public $discount;

    
    /**
     *
     * @var double
     */
    public $priceTotal;

    /**
     * @var double
     */
    public $tax;

    /**
     *
     * @var timestamp
     */
    public $startDate;

    
    /**
     *
     * @var timestamp
     */
    public $paid;
    
    /**
     * @var string
     */    
    public $type; 
    
    /**
     * @var string
     */
    public $paymentMethod;

    
    /**
     * @var string
     */
    public $result;

    /**
     *
     * @var string
     */
    public $auth;

    
    /**
     *
     * @var string
     */
    public $ref;
    
    /**
     *
     * @var string
     */
    public $tranId;    


    /**
     *
     * @var string
     */
    public $trackId;

    /**
     *
     * @var string
     */
    public $responseCode;

    
    /**
     *
     * @var string
     */
    public $errMsg;
    
    /**
     *
     * @var string
     */
    public $ip;
        
    /**
     *
     * @var string
     */
    public $referrer;
    

    /**
     *
     * @var string
     */
    public $respBody;    
    
    /**
     * @var integer
     */
    public $status;
    
    
    /**
     * 
     * @return integer $id
     */    
    public function getId() {
    	return $this->id;
    } 
    
    /**
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * 
     * @return integer $paymentId
     */    
    public function getPaymentId() {
    	return $this->paymentId;
    } 
    
    /**
     *
     * @param integer $paymentId
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;
    }
    
   	/**
   	 * return integer $entity
   	 */
    public function getEntity() {
    	return $this->entity;
    }
    
    /**
     * @param integer $entity
     */
    public function setEntity($entity) {
    	$this->entity = $entity;
    }
        
    /**
     * return string $user
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * return integer $userId
     */
    public function getUserId() {
    	return $this->user->getO_id();
    }
        
    /**
     *
     * @param Object_Abstract $user
     */
    public function setUser($user)
    {
        $this->user = $user;
        $this->userId = $user->getO_Id();
    }

    /**
     *
     * @param integer $id
     */
    public function setUserId($id)
    {
        $this->userId = $id;
        try {
            $this->user = Object_Abstract::getById($id);
        } catch (Exception $e) {

        }
    }

    /**
     * 
     * @return double $discount
     */    
    public function getDiscount() {
    	return $this->discount;
    } 
    
    /**
     *
     * @param double $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * 
     * @return double $priceTotal
     */    
    public function getPriceTotal() {
    	return $this->priceTotal;
    } 
    
    /**
     *
     * @param double $priceTotal
     */
    public function setPriceTotal($priceTotal)
    {
        $this->priceTotal = $priceTotal;
    }

    /**
     * 
     * @return double $tax
     */    
    public function getTax() {
    	return $this->tax;
    } 
    
    /**
     *
     * @param double $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    } 

    /**
     * 
     * @return timestamp $startDate
     */    
    public function getStartDate() {
    	return $this->startDate;
    } 
    
    /**
     *
     * @param timespamp $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }  

    /**
     * 
     * @return integer $paid
     */    
    public function getPaid() {
    	return $this->paid;
    } 
    
    /**
     *
     * @param integer $paid
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    } 

     /**
     * @return string $paymentMethod
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param string $paymentMethod
     */
    public function setType($type)
    {
        $this->type = $type;
    }    
    
     /**
     * @return string $paymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     *
     * @param string $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return string $result
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     *
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     *
     * @return string $auth
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     *
     * @param string $auth
     */
    public function setAuth($auth)
    {
        $this->auth = $auth;
    } 

    /**
     *
     * @return string $ref
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     *
     * @param string $ref
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
    }

    /**
     *
     * @return string $tranId
     */
    public function getTranId()
    {
        return $this->tranId;
    }

    /**
     *
     * @param string $tranId
     */
    public function setTranId($tranId)
    {
        $this->tranId = $tranId;
    }

    /**
     *
     * @return string $tracId
     */
    public function getTrackId()
    {
        return $this->trackId;
    }

    /**
     *
     * @param string $trackId
     */
    public function setTrackId($trackId)
    {
        $this->trackId = $trackId;
    }
    
    /**
     *
     * @return string $responseCode
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     *
     * @param string $responseCode
     */
    public function setResponseCode($responseCode)
    {
        $this->responseCode = $responseCode;
    }

    /**
     *
     * @return string $errMsg
     */
    public function getErrMsg()
    {
        return $this->errMsg;
    }

    /**
     *
     * @param string $errMsg
     */
    public function setErrMsg($errMsg)
    {
        $this->errMsg = $errMsg;
    }    
    
    /**
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     *
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }  

    /**
     *
     * @return string $referrer
     */
    public function getReferrer()
    {
        return $this->referrer;
    }

    /**
     *
     * @param string $ip
     */
    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }
    
    /**
     *
     * @return string $respBody
     */
    public function getRespBody()
    {
        return $this->respBody;
    }

    /**
     *
     * @param string $respBody
     */
    public function setRespBody($respBody)
    {
        $this->respBody = $respBody;
    }

    
    public function getResource()
    {
        if (!$this->resource) {
            $this->initResource("Resource_Order");
        }
        return $this->resource;
    }
    
    /**
     * @return void
     */
    public function save()
    {
        if ($this->getId()) {
            $this->update();
        }
        else {
            $this->getResource()->create();
            $this->update();
        }
    }

    public function delete()
    {
        $this->getResource()->delete();
    }
    
    
    /**
     *
     * @param Resource_Order $id
     */
    public static function getById($id)
    {
        $order = new Resource_Order();
        $order->getResource()->getById($id);
        return $order;
    }
    
    
    public function insertItem($data) {
        $this->getResource()->insertItem($data);
    }
    
    /**
     * 
     * @return integer $status
     */    
    public function getStatus() {
    	return $this->status;
    } 
    
    /**
     *
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }    
    
}