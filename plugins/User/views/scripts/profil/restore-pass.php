

<?php if($this->err == 1) { ?>
	<p class="err tc">
		<?php 
			echo $this->translate("RESTORE_PASS_HASH_DOESNT_MATCH");				
		?>
	</p>
	
	<a href="/pozabil-geslo"><?= $this->translate("FORGOTTEN_PASSWORD") ?></a>
	<?php 
	}
	
	if($this->MSG == 1) {
		?><p class="info"><?= $this->translate("PASSWORD_UPDATED") ?></p>
			<p class="info"><a href="/prijava"><?= $this->translate("LOGIN") ?></a></p>
		<?php
	} else { 
    	echo $this->form;
    }
    
?>