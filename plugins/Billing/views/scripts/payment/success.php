<?php $this->layout()->setLayout('standard'); ?>

<h1><?= $this->translate("PAYMENT_DETAILS_SENT") ?></h1>

<div class="textblock">
	<p class="info">
	<?= $this->translate("WE_HAVE_SENT_TO_YOUR_EMAIL") ?> <strong><?= $this->order->getUser()->getEmail() ?></strong> <?= $this->translate("SEND_PAYMENT_DETAILS") ?>.
	</p>
	
	
	<h4><?= $this->translate("ORDER_DETAILS")  ?></h4>
	<?php $this->template(PIMCORE_PLUGINS_PATH."/Billing/views/scripts/payment/order-details.php") ?>
	
	<br />
	<br />
	<div style="text-align: right;">
		<a class="button" href="/profil"><?= $this->translate("MY_PROFILE") ?></a>
	</div>
</div>