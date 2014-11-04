

<?php if(!$this->user) { ?>
<div>
	<p class="info"><?= $this->translate("ALREADY_REGISTERED") ?> <a href="/prijava"><?= $this->translate("LOGIN_ALREADY_REGISTERED") ?></a></p>
</div>
<?php } ?>

<?php if($this->err == 1) { ?>
	<p class="err tc">
		<?php 
			echo $this->translate("NOT_VALIDATED");				
		?>
	</p>
	<?php 
	echo $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/profil/send-validation.php');
	
    } else if($this->err == 2) {
    	?>
    	<p class="info"><a href="/pozabil-geslo"><?= $this->translate("HAVE_YOU_FORGOT_YOUR_PASSWORD") ?></a></p>
    	<?php 
    } ?>
<?php
	if($this->MSG == 1) {
		?><p class="info"><?php
		echo  $this->translate("WE_HAVE_SEND_TO_EMAIL")." <strong>".$this->email."</strong> ".$this->translate("AUTENTICATION_EMAIL_CLIK_ON_LINK_IN_EMAIL_TO_FINISH_REGISTRATION").".";
		?></p>
		<?php
	} else { 
    	echo $this->form;
    }
?>