

<p class="info">
	<?php 
	    switch($this->MSG) {
	        case 1:
	            ?>
                <?= $this->translate("ACCOUNT_VALIDATED")?><br />
                <a href="/prijava"><?= $this->translate("YOU_CAN_SIGN_IN") ?></a>
                <?php 
	            break;
	        case 0:
	            ?>
	            <?= $this->translate("ERROR_ACCOUNT_VALIDATION") ?>
	            <?php 
	            break;
	    }
	    
	?>
</p>