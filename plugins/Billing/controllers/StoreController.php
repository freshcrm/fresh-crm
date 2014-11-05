<?php 

class Billing_StoreController extends Website_Controller_Plugin {

	public function getOrdersAction() {
		$request = $this->getRequest();
		$params = $request->getParams();

		$per = 35;
		
		if($request->getParam("per"))
			$per = $request->getParam("per"); 
		
		$start = (isset($params['start']) ? (int)$params['start'] : 0);
        $limit = (isset($params['limit']) ? (int)$params['limit'] : 35);

		// @TODO: Resource_Order_List
		$userConfig = new Zend_Config_Xml(PIMCORE_PLUGINS_PATH.'/User/config.xml');
		$userTable = 'object_'.$userConfig->object->objectid;
		
		$db = Pimcore_Resource_Mysql::getConnection();
		
        //remote sort extjs
        if (empty($params['dir'])) {
            $params['dir'] = 'DESC';
        }
        if (!empty($params['sort'])) {
            if ($params['sort'] == 'paid') {
                $params['sort'] = 'orders.paid';
            }
            $orderBy = new Zend_Db_Expr($params['sort'] . " {$params['dir']}");
        } else {
            $orderBy = "orders.id DESC";
        } 

		$orders = $db->fetchAll("SELECT id, priceTotal, paid, orders.entity, paymentMethod, type, startDate, status,
										name, surname, email
										FROM orders 
										JOIN $userTable ON $userTable.o_id = orders.userId 
										ORDER BY {$orderBy} 
										LIMIT {$start}, {$limit}");
		
		$total = $db->fetchOne('SELECT count(*) FROM orders');
		
	    $sum = 0;

        foreach ($orders as $item) {
            $sum += $item['priceTotal'];
        }		
		
		return $this->_helper->json(array('orders' 	=> $orders,
										  'total'	=> $total,
										  'sum'		=> (empty ($sum) ? 0 : $sum)));
	}
}