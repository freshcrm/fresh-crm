<?php 
	if($this->view->err) {
		?>
		<p class="info">
				NAPAKA! 
			<br />
			Prosimo obvestite nas o napaki.
		
		</p>
		<?php 
	} else {
		?>
		<p class="info"><?= $this->translate("RESEND_VALIDATION_SUCCESS") ?></p>
		<?php 
	}

?>