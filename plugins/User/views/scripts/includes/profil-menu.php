<a href="/profil"><?= $this->translate("MY_PROFIL") ?></a> | <a href="/profil/ured-podatke"><?= $this->translate("EDIT_PROFILE_DATA") ?></a> | <a href="/profil/spremeni-geslo"><?= $this->translate("CHANGE_PASSWORD") ?></a> | <a href="/profil/kosarica"><?= $this->translate("PERIODIC_ORDER") ?></a> <!--| <a href="/profil/narocila"><?= $this->translate("MY_ORDERS") ?></a> -->

<?php 


	if($this->user && $this->user->getValidation() != 1) { ?>
	<p class="err tc">
		<?php 
			echo $this->translate("NOT_VALIDATED");				
		?>
	</p>
	<?php 
		echo $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/profil/send-validation.php');
	} 
?>


  