<?php

class Resource_Order_List_Resource_Mysql extends Pimcore_Model_List_Resource_Mysql_Abstract {


        /**
         * Loads a list of objects for the specicifies parameters, returns an array of Object_Abstract elements
         *
         * @return array
         */
        public function load () {
                $ordes = array();
				$ordersData = $this->db->fetchAll("SELECT id FROM orders".$this->getCondition().$this->getOrder().$this->getOffsetLimit());

                foreach ($ordersData as $orderData) {
                	$orders[] = Resource_Order::getById($orderData["id"]);
                }

                $this->model->setOrders($orders);
                return $orders;
        }


        
        public function getItemsByOrderId($orderId) {
            
            $select = $this->db->select()
                                ->from('order_product', array("product_id", "quantity", "discount"))
                                ->where('order_id=?',$orderId);
                                
            
            $stmt = $this->db->query($select);
            $result = $stmt->fetchAll();
            
            $items = array();
            
            foreach ($result as $item) {
                $items[] = array(
                    'object'	=> Object_Abstract::getById($item["product_id"]),
                	'discount'	=> $item["discount"],
                    'quantity'	=> $item["quantity"]
                );
            }
            
            
            return $items;
            
        }
        
}
