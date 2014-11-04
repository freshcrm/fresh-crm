<?= $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/includes/profil-menu.php') ?>


<h4><?= $this->user->getEmail() ?></h4>
<table>
  <tr>
    <td><em><?= $this->translate("NAME") ?>:</em></td>
    <td><strong><?= $this->user->getName() ?></strong></td>
  </tr>
  <tr>
    <td><em><?= $this->translate("SURNAME") ?>:</em></td>
    <td><strong><?= $this->user->getSurname() ?></strong></td>
  </tr>
  <tr>
    <td><em><?= $this->translate("ADDRESS") ?>:</em></td>
    <td><strong><?= $this->user->getAddress() ?></strong></td>
  </tr>
  <tr>
    <td><em><?= $this->translate("POST") ?>:</em></td>
    <td><strong><?= $this->user->getPost() ?></strong></td>
  </tr>      
</table>

<?php if(count($this->orders)) { ?>
<hr />
<h4><?= $this->translate("CURRENT_ORDERS") ?></h4>

<table class="orders">
  <tr>
    <th><?= $this->translate("ORDERID") ?></th>
    <th><?= $this->translate("PAYMENT_METHOD") ?></th>
    <th><?= $this->translate("START_DATE") ?></th>
    <th><?= $this->translate("DELIVERY_TYPE") ?></th>
  </tr>
<?php foreach ($this->orders as $order) { ?>


  <tr>
    <td><a href="/order-details/<?= $order->getId() ?>" class="orderDetails"><?= $order->getId() ?></a></td>
    <td><?= $this->translate($order->getPaymentMethod()) ?></td>
    <td>
    	<?php 
    		$date = new Zend_Date($order->getStartDate());
    		echo $date->toString("dd.MM.yyyy");
    	?>
    </td>
    <td>
    	<?= $this->translate($order->getType()) ?>
    </td>   
  </tr>
<?php } ?>
</table>
<?php }?>