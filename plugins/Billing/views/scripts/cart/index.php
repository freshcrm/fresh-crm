<?php $this->layout()->setLayout('standard'); ?>

<div>
	<div class="group marginb">
    	<div class="left">
    		<h1><?php echo $this->translate("CART_CONTENT") ?></h1>
    	</div>
    	
    	<div class="group right">
    		<div class="left">
    				<span class="dropcap-circle colored">1</span>
    		</div>
    		
    		<div class="left">
    			<span class="dropcap-circle" style="opacity: 0.6;">2</span> <br/><br/>
    		</div>
    		
    		<div class="left">
    			<span class="dropcap-circle" style="opacity: 0.6;">3</span> <br/><br/>
    		</div>
    		
    		<div class="left">
    			<span class="dropcap-circle" style="opacity: 0.6;">4</span> <br/><br/>
    		</div>
    	</div>
    </div>
	
	
	<div class="list" id="cartcontent">
		<?= $this->template('cart/content.php') ?>
	</div>
</div> <!-- entry-content -->