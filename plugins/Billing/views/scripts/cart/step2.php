<?php $this->layout()->setLayout('standard'); ?>

<div class="group marginb">
	<div class="left">
		<h1><?php echo $this->translate("DELIVERY_DATA") ?></h1>
	</div>
	
	<div class="group right">
		<div class="left">
				<span class="dropcap-circle" style="opacity: 0.6;">1</span>
		</div>
		
		<div class="left">
			<span class="dropcap-circle  colored">2</span> <br/><br/>
		</div>
		
		<div class="left">
			<span class="dropcap-circle" style="opacity: 0.6;">3</span> <br/><br/>
		</div>
		
		<div class="left">
			<span class="dropcap-circle" style="opacity: 0.6;">4</span> <br/><br/>
		</div>
	</div>
</div>

<div>
	<?php 
	    echo $this->form;
	?>
</div>

<div class="group">
	<div class="left">
		<a href="/cart" class="button bigger">Nazaj</a>
	</div>
	
	<div class="right">
		<a href="/nakup/korak3" class="button bigger" id="step2">Naprej</a>
	</div>
</div>

<div id="cart-list">

</div>

