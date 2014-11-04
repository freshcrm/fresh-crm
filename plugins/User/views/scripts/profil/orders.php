
<?= $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/includes/profil-menu.php') ?>


<?php if(count($this->orders)) { ?>
	<table>
	  <tr>
	    <th><?= $this->translate("ORDERID") ?></th>
	    <th><?= $this->translate("PAYMENT_METHOD") ?></th>
	    <th><?= $this->translate("START_DATE") ?></th>
	    <th><?= $this->translate("PAID_DATE") ?></th>
	    <th><?= $this->translate("DELIVERY_TYPE") ?></th>
	    
	  </tr>
	<?php foreach ($this->orders as $order) { ?>
	
	
	  <tr>
	    <td><?= $order->getId() ?></td>
	    <td><?= $order->getPaymentMethod() ?></td>
	    <td>
	    	<?php 
	    		$date = new Zend_Date($order->getStartDate());
	    		echo $date->toString("dd.MM.yyyy");
	    	?>
	    </td>
	    <td>
	
	    	<?php 
	    		if($order->getPaid()) {
	    			$paidDate = new Zend_Date($order->getPaid());
	    			echo $paidDate->toString("dd.MM.yyyy");
	    		}
	    	?>
	    </td>
	    <td>
	    	<?= $order->getType() ?>
	    </td>          
	  </tr>
	<?php } ?>
	</table>
<?php } ?>