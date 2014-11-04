<?php
if($this->err) { ?>
	<p class="err tc">
		<?php 
			switch($this->err) {
				case 1:
					echo $this->translate("WRONG_USERNAME_OR_PASSWORD");
					break;
				case 2:
					echo $this->translate("YOUR_ACCOUNT_HAS_BEEN_DISABLED");
					break;
				case 3:
					echo $this->translate("SESSION_TIMEOUT");
					break;
				case 4:
					echo $this->translate("NOT_VALIDATED");	
			}
		?>
	</p>
	
	
	<?php 
	if($this->err == 4) { 
		echo $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/profil/send-validation.php');
	} 
} ?>


<?php 
    echo $this->form;
?>
<div class="textblock">
	<a href="/pozabil-geslo"><?= $this->translate("FORGOTTEN_PASSWORD") ?></a>
</div>