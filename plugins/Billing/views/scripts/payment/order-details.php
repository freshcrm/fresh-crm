<div id="order_detail" summary="order<?= $this->order->getId() ?>">
<?php if($this->order->getUser()) { ?>
	<h4><?= $this->translate("USER") ?></h4>
    	<table>
    		<colgroup>
    			<col />
    			<col />
    		</colgroup>		
    		<?php if($this->order->getUser()->getName()) { ?>
    		<tr>
    			<td><em><?= $this->translate("NAME") ?>:</em></td>
    			<td><?= $this->order->getUser()->getName() ?></td>
    		</tr>
    		<?php } ?>
    		<?php if($this->order->getUser()->getSurname()) { ?>
    		<tr>
    			<td><em><?= $this->translate("SURNAME") ?>:</em></td>
    			<td><?= $this->order->getUser()->getSurname() ?></td>
    		</tr>
    		<?php } ?>

    		<?php if($this->order->getUser()->getAddress()) { ?>		
    		<tr>
    			<td><em><?= $this->translate("ADDRESS") ?>:</em></td>
    			<td><?= $this->order->getUser()->getAddress() ?></td>		
    		</tr>
    		<?php } ?>

    		<?php if($this->order->getUser()->getPost()) { ?>		
    		<tr>
    			<td><em><?= $this->translate("POST") ?>:</em></td>
    			<td><?= $this->order->getUser()->getPost() ?></td>		
    		</tr>
    		<?php } ?>
    		<?php if($this->order->getUser()->getPhone()) { ?>		
    		<tr>
    			<td><em><?= $this->translate("PHONE") ?>:</em></td>
    			<td><?= $this->order->getUser()->getPhone() ?></td>		
    		</tr>
    		<?php } ?>
    	</table>
    	
    	
    	
    	
    	<?php /* if($this->order->getEntity()) { ?>
    		<h4><?= $this->translate("ISENTITY") ?></h4>
    		<table>
    			<colgroup>
    				<col />
    				<col />
    			</colgroup>			
    			<tr>
    				<td><em><?= $this->translate("ENTITY") ?>:</em></td>
    				<td><?= $this->order->getUser()->getEntity() ?></td>		
    			</tr>		
    			<tr>
    				<td><em><?= $this->translate("VAT") ?>:</em></td>
    				<td><?= $this->order->getUser()->getVat() ?></td>		
    			</tr>			
    		</table>			
    	<?php } */ ?>
	
	
	<?php } ?>
	
	<h4><?= $this->translate("ORDER") ?></h4>
	<table>
		<colgroup>
			<col />
			<col />
		</colgroup>
		<tr>
			<td><em><?= $this->translate("ID") ?>:</em></td>
			<td><?= $this->order->getId() ?></td>		
		</tr>		
		<tr>
			<td><em><?= $this->translate("TOTAL") ?>:</em></td>
			<td><?= number_format($this->order->getPriceTotal(),2) ?> €</td>		
		</tr>
		<tr>
			<td><em><?= $this->translate("START_DATE") ?>:</em></td>
			<td>
				<?php 
					$startDate = new Zend_Date($this->order->getStartDate());
					echo $startDate->toString("dd.MM.yyyy hh:mm:ss"); 
				?>
			</td>		
		</tr>
		<tr>
			<td><em><?= $this->translate("PAID") ?>:</em></td>
			<td>
				<?php 
					if(!$this->order->getPaid()) { 
						echo $this->translate("NO");
					} else {
						$paidDate = new Zend_Date($this->order->getPaid());
						echo $paidDate->toString("dd.MM.yyyy hh:mm:ss");
					}
				?>
			
			</td>		
		</tr>				
		<tr>
			<td><em><?= $this->translate("PAYMENT_METHOD") ?>:</em></td>
			<td><?= $this->translate($this->order->getPaymentMethod()) ?></td>		
		</tr>							
	</table>			
</div>

<h4><?= $this->translate("ORDER_CONTENT") ?></h4>
<div>
<?php

    if(count($this->items)) {
        ?>
        <table style="width: 100%">
        	<tr>
				<th scope="col">Naziv:</th>
				<th scope="col" style="width: 100px;">Cena:</th>
				<?php if($this->hasContract) { ?>
				<th scope="col" style="width: 100px;">Cena s popustom:</th>
				<?php } ?>
				<th scope="col" style="width: 100px;">Količina:</th>
				<th scope="col" style="">Skupaj:</th>
        	</tr>
		<?php         
        foreach ($this->items as $item) {
            ?>
            <tr>
            	
            	<td><?= $item['object']->getTitle() ?></td>
            	<td><?= number_format($item['object']->getPrice(),2) ?> <?= $this->config->currency ?></td>
            	<?php if($this->hasContract) { ?>
            	<td><?php 
            			if($item['object'] instanceof Object_Item) 
            				$price = 80 * $item['object']->getPrice() / 100;
            			else 
            				$price = $item['object']->getPrice() - 1;
            		
            			
            			echo number_format($price,2)
            		?> <?= $this->config->currency ?>
            		
            	</td>
            	<?php } ?>
            	<td><?= $item['quantity'] ?></td>
            	
            	<?php if($this->hasContract) { ?>
            	<td><?= number_format($item['quantity'] * $price,2) ?> <?= $this->config->currency ?></td>
            	<?php } else { ?>
            	<td><?= number_format($item['quantity'] * $item['object']->getPrice(),2) ?> <?= $this->config->currency ?></td>
            	<?php } ?>
            </tr>     
           <?php 
        }
        ?>
        
        <?php if($this->order->getType() == 'DELIVERY') { ?>
            <tr>
            	<td><?= $this->translate("DELIVERY") ?></td>
            	<td><?= number_format($this->config->delivery_price,2) ?> <?= $this->config->currency ?></td>
            	<?php if($this->hasContract) { ?>
            	<td>&nbsp;</td>
            	<?php } ?>
            	<td>1</td>
            	<td><?= number_format($this->config->delivery_price,2) ?> <?= $this->config->currency ?></td>
            </tr>         
        <?php } ?>
        
        </table>
        <?php 
    }
?>
</div>

<?php if($this->jqmCloseButton) { ?>
<div class="winBottomMenu">
	<a href="#" class="jqmClose button"><?= $this->translate("CLOSE_WINDOW") ?></a>
</div>
<?php } ?>