
<?= $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/includes/profil-menu.php') ?>

<?php if($this->msg == 1) { ?>
	<p class="info"><?= $this->translate("PROFILE_DATA_UPDATED") ?></p>
<?php } ?>
<?php 
    echo $this->form;
?>
