
<?= $this->template(PIMCORE_PLUGINS_PATH.'/User/views/scripts/includes/profil-menu.php') ?>

<?php if(!count($this->boxContracts)) { ?>
<p><?= $this->translate("YOU_HAVE_NO_CONTRACTS_YET") ?></p>

	<?php if($this->config->box_page) { ?>
		<a href="<?= $this->config->box_page->getFullPath() ?>"><?= $this->config->box_page->getName() ?></a>
	<?php } ?>

<?php } else { ?>
<?php 
	if($this->MSG == 1) {
		?>
		<p class="info"><?= $this->translate("BOX_CONTRACT_UPDATED") ?></p>
		<?php 
	}
	foreach ($this->boxContracts as $bc) {
		?>
		<h4><?= $this->translate("CONTRACT_NUMBER") ?>: <?= $bc->contract->getId() ?></h4>
		
		<?php 
		
			$this->box = $bc->box;
			$this->hideOrder = 1;
			echo $this->template(PIMCORE_WEBSITE_PATH.'/views/scripts/includes/box.php');
		?>
		
		
		<form name="contractBox<?= $this->box->getId() ?>" action="/profil/kosarica" method="post">
			<input type="hidden" name="id" value="<?= $bc->contract->getId() ?>" />
			<fieldset>
				<legend><?= $this->translate("CHANGE_BOX") ?></legend>
				<?php foreach ($this->boxList as $box) { ?>
				<input type="radio" name="boxId" value="<?= $box->getId() ?>" <?php if($bc->contract->getBoxId() == $box->getId()) { ?>checked="checked"<?php } ?> /><?= $box->getTitle() ?><br />
				<?php }?>
			</fieldset>
			<fieldset>
				<legend><?= $this->translate("CHANGE_PERIOD") ?></legend>
				<input type="radio" name="period" value="1" <?php if($bc->contract->getPeriod() == 1) { ?>checked="checked"<?php } ?> /><?= $this->translate("montly") ?><br />
				<input type="radio" name="period" value="2" <?php if($bc->contract->getPeriod() == 2) { ?>checked="checked"<?php } ?> /><?= $this->translate("every 2 weeks") ?><br />
				<input type="radio" name="period" value="4" <?php if($bc->contract->getPeriod() == 4) { ?>checked="checked"<?php } ?> /><?= $this->translate("weekly") ?><br />
			</fieldset>
			<fieldset>
				<legend><?= $this->translate("DELIVERY_TYPE") ?></legend>
				<input type="radio" name="deliveryType" value="DELIVERY" <?php if($bc->contract->getDeliveryType() == 'DELIVERY') { ?>checked="checked"<?php } ?> /><?= $this->translate("DELIVERY") ?><br />
				<input type="radio" name="deliveryType" value="PICKUP" <?php if($bc->contract->getDeliveryType() == 'PICKUP') { ?>checked="checked"<?php } ?> /><?= $this->translate("PICKUP") ?><br />				
			</fieldset>
			<fieldset>
				<legend><?= $this->translate("DEFAULT_PAYMENT") ?></legend>
				<input type="radio" name="defaultPayment" value="UPN" <?php if($bc->contract->getDefaultPayment() == 'UPN') { ?>checked="checked"<?php } ?> /><?= $this->translate("UPN") ?><br />
				<input type="radio" name="defaultPayment" value="CASH" <?php if($bc->contract->getDefaultPayment() == 'CASH') { ?>checked="checked"<?php } ?> /><?= $this->translate("CASH") ?><br />				
			</fieldset>		
			<fieldset>
				<legend><?= $this->translate("CHOOSE_ADDITIONAL_WISHES") ?></legend>
				<textarea id="note" name="note"><?= $bc->contract->getNote() ?></textarea>
			</fieldset>
							
			<input type="submit" value="<?= $this->translate("SUBMIT") ?>" />
		</form>

		<hr />


        <?php
        /*


        <div>
            <h2>Navodila ....</h2>
            <p>test ...</p>
        </div>


		<?php
        $date = new Zend_Date();
        while(intval($date->toString(Zend_Date::DAY)) != 7) {
            $date->subDay(1);
        }

        $tmpMonth = (int)$date->toString(ZEND_DATE::MONTH_SHORT) - 1;

        $i = 0;
        while((int)$date->toString(Zend_Date::WEEK) != 1) {
                if($tmpMonth != (int)$date->toString(ZEND_DATE::MONTH_SHORT)):
                    $tmpMonth = (int)$date->toString(ZEND_DATE::MONTH_SHORT);

                ?>
                <div class="item">
                    <div class="title"><?php echo $date->toString(Zend_Date::MONTH_NAME) ?></div>

                <?php
                endif;


            ?>
            <div class="listItem">
                <div class="group">
                    <div class="left">
                        <span>KT: <span class="bigger"><?php echo $date->toString("dd.MM.") ?></span></span>
                    </div>
                    <div class="right">
                        <input type="checkbox" id="<?php echo (int)$date->toString(Zend_Date::WEEK); ?>" class="kw"/>
                    </div>
                </div>
            </div>
            <?php
                $date->addWeek(1);
                if($tmpMonth != (int)$date->toString(ZEND_DATE::MONTH_SHORT)):
            ?>
                    </div>
                <?php
               endif;


               $i++;
        }

        ?>
        </div>

                 */
        ?>

        <?php
	}
?>
<?php } ?>


<script type="text/javascript">
    $(document).ready(function() {
        $.pnotify.defaults.styling = "bootstrap3";
        $.pnotify.defaults.history = false;


        $(".kw").change(function() {
            $.pnotify({
                type: 'error',
                title: 'Custom Function',
                text: 'I use a different effect.'
            });
        });
    });
</script>