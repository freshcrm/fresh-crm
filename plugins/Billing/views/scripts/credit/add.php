<?php $this->layout()->setLayout('standard'); ?>

<h1><?= $this->translate("ACCOUNT_BALLANCE") ?>: <?= $this->user->getAccountBalance() ?> <?= $this->config->currency ?></h1>

<div class="textblock">
	<?= $this->translate("ADD_CREDIT_TEXT") ?>
</div>

<div class="textblock">
	<?= $this->form ?>
	
	<script type="text/javascript">
		$(document).ready(function() {
			if($("#isentity").attr('checked')) {
				$("#entity").removeAttr('disabled');
				$("#vat").removeAttr('disabled');				
			}
			
			$("#isentity").change(function() {
				if($(this).attr('checked')) {
					$("#entity").removeAttr('disabled');
					$("#vat").removeAttr('disabled');
				} else {
					$("#entity").attr('disabled',true);
					$("#vat").attr('disabled', 'disabled');
				}
			});
		});
	</script>
	
	
</div>