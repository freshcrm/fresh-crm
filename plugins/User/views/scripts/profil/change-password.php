

<?= $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/includes/profil-menu.php') ?>

<?php if($this->msg == 1) { ?>
	<p class="info"><?= $this->translate("PASSWORD_CHANGED") ?></p>
<?php } ?>
<?php 
    echo $this->form;
?>
