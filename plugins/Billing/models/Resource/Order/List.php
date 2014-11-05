<?php 
class Resource_Order_List extends Pimcore_Model_List_Abstract {

	/**
	 * @var array
	 */
	public $orders = array();
	
	/**
	 * 
	 * @var Object_Abstract
	 */
	public $object;
	
	/**
	 * @var array
	 */
	public $validOrderKeys = array(
		"id",
	    "paymentId",
	    "priceTotal",
	    "paymentType"
	);
	
	/**
	 * @param boolean $objectTypeObject
	 * @return void
	 */
	public function __construct() {
		$this->initResource("Resource_Order_List");
	}
	
	/**
	 * @param string $key
	 * @return boolean
	 */
	public function isValidOrderKey ($key) {
		return true;
	}
	
	/**
	 * @return array
	 */
	public function getOrders() {
		return $this->orders;
	}
	
	/**
	 * @param string $objects
	 * @return void
	 */
	public function setOrders($orders) {
		$this->orders = $orders;
	}
	
	/**
	 * @return Object_Abstract $object
	 */
	public function getObject(){
		return $this->object;
	}
	
	public function getItems($orderId) {
	    return $this->getItemsByOrderId($orderId);
	}
	
	
}