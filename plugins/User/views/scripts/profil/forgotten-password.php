
<?php 
if($this->MSG == 1) { ?>
	<p class="info"><?=  $this->translate("TO_YOUR_EMAIL") ?> <strong><?php echo $this->email ?></strong> <?= $this->translate("SEND_YOUR_PASSWORD") ?>.
<?php 
} else { 
	echo $this->form;

} 
?>