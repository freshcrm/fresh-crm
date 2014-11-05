<?php $this->layout()->setLayout('standard'); ?>

<div class="group marginb">
	<div class="left">
		<h1><?php echo $this->translate("PAYMENT_SELECT") ?></h1>
	</div>
	
	<div class="group right">
		<div class="left">
				<span class="dropcap-circle" style="opacity: 0.6;">1</span>
		</div>
		
		<div class="left">
			<span class="dropcap-circle" style="opacity: 0.6;">2</span> <br/><br/>
		</div>
		
		<div class="left">
			<span class="dropcap-circle colored">3</span> <br/><br/>
		</div>
		
		<div class="left">
			<span class="dropcap-circle" style="opacity: 0.6;">4</span> <br/><br/>
		</div>
	</div>
</div>

<div>
	<form method="post" action="/nakup" id="paymentform" name="paymentform">
		<fieldset>
			<legend>IZBERI NAČIN PLAČILA</legend>
			<input name="payment" type="radio" id="upn" value="upn"></input>
			<label for="dostava"><?= $this->translate("PAYMENT_UPN") ?></label>
			<br />
			<input name="payment" type="radio" id="moneta" value="moneta"></input>
			<label for="dostava"><?= $this->translate("PAYMENT_MONETA") ?></label>				
			<br />
			
			<?php 
			
			    $user = Object_User::getById($this->user->o_id);
			?>
			
			<?php if($user->getAccountBalance() > $this->priceTotal) { ?>
			<input name="payment" type="radio" id="account" value="account"></input>
			<?php } ?>
			<label for="account"><?= $this->translate("PAYMENT_ACCOUNT") ?> (<?= $this->translate("ACCOUNT_BALANCE")?>: <?= $user->getAccountBalance() ?> €) </label>				
			<p class="error" id="payment-err" style="display: none;"> 
			</p>				
		</fieldset>
	</form>
</div>
	
<div class="group">
	<div class="left">
		<a href="/nakup/korak2?back=1" class="button bigger">Nazaj</a>
	</div>
	
	<div class="right">
		<a href="#" class="button bigger" id="nakup">Naprej</a>
	</div>
</div>


<div id="cart-list">

</div>