<?php

class Resource_Order_Resource_Mysql extends Pimcore_Model_Resource_Mysql_Abstract
{

    /**
     * Contains all valid columns in the database table
     *
     * @var array
     */
    protected $validColumns = array();

    /**
     * Get the valid columns from the database
     *
     * @return void
     */

    public function init()
    {
        $data = $this->db->fetchAll("SHOW COLUMNS FROM orders");
        foreach ($data as $d) {
            $this->validColumns[] = $d["Field"];
        }
    }

    /**
     * Get the data for the object from database for the given id
     *
     * @param integer $id
     * @return void
     */
    public function getById($id)
    {
        try {
            $data = $this->db->fetchRow("SELECT * FROM orders WHERE id = ?", $id);
            $this->assignVariablesToModel($data);
        }
        catch (Exception $e) {
        }
    }


    /**
     * Get the data for the object from database for the given name
     *
     * @param string $name
     * @return void
     */
    public function save()
    {

        if ($this->model->getId()) {
            return $this->model->update();
        }
        return $this->create();
    }

    /**
     * Create a new record for the object in database
     *
     * @return boolean
     */
    public function create()
    {
        try {

            $this->db->insert("orders", array(
                                                "paymentId" 	=> $this->model->getPaymentId(),
            									"entity"		=> $this->model->getEntity(),
                                                "userId" 		=> $this->model->getUserId(),
                                                "discount" 		=> $this->model->getDiscount(),
                                                "priceTotal"	=> $this->model->getPriceTotal(),
                                                "tax" 			=> $this->model->getTax(),
            									"paid"			=> $this->model->getPaid(),
                                                "type"			=> $this->model->getType(),
                                                "paymentMethod"	=> $this->model->getPaymentMethod(),
            									"result"		=> $this->model->getResult(),
            									"auth"			=> $this->model->getAuth(),
            									"ref"			=> $this->model->getRef(),
            									"tranId"		=> $this->model->getTranId(),
            									"trackId"		=> $this->model->getTrackId(),
            									"responseCode"	=> $this->model->getResponseCode(),
            									"errMsg"		=> $this->model->getErrMsg(),
            									"ip"			=> $this->model->getIp(),
            									"referrer"		=> $this->model->getReferrer(),
            									"respBody"		=> $this->model->getRespBody(),
            									"status"		=> $this->model->getStatus()
                              ));

                              
            $this->model->setId($this->db->lastInsertId());
            return $this->save();
        }
        catch (Exception $e) {
        	error_log("Resource_Order_Resource_Mysql/create: ".$e->getMessage());
            throw $e;
        }

    }

    /**
     * Save changes to database, it's an good idea to use save() instead
     *
     * @return void
     */

    public function update()
    {
        try {
            $data["id"] 			= $this->model->getId();
            $data["paymentId"] 		= $this->model->getPaymentId();
            $data["entity"]			= $this->model->getEntity();
            $data["userId"] 		= $this->model->getUserId();
            $data["discount"] 		= $this->model->getDiscount();
            $data["priceTotal"] 	= $this->model->getPriceTotal();
            $data["tax"] 			= $this->model->getTax();
            $data['paid']			= $this->model->getPaid();
            $data['type']           = $this->model->getType();
			$data["paymentMethod"] 	= $this->model->getPaymentMethod();
			$data['result']			= $this->model->getResult();
			$data['auth']			= $this->model->getAuth();
			$data['ref']			= $this->model->getRef();
			$data['tranId']			= $this->model->getTranId();
			$data['trackId']		= $this->model->getTrackId();
			$data['responseCode']	= $this->model->getResponseCode();
			$data['errMsg']			= $this->model->getErrMsg();
			$data['ip']				= $this->model->getIp();
			$data['referrer']		= $this->model->getReferrer();
			$data['respBody']		= $this->model->getRespBody();
			$data['status']			= $this->model->getStatus();

            $this->db->update("orders", $data, "id='" . $this->model->getId() . "'");

        }
        catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Deletes object from database
     *
     * @return void
     */
    public function delete()
    {
        try {
            $this->db->delete("orders", "id='" . $this->model->getId() . "'");
        }
        catch (Exception $e) {
            throw $e;
        }
    }    

    public function insertItem($data) {
        return $this->db->insert('order_product', $data);
    }

}


?>
