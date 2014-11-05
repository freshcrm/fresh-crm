		<?php if(count($this->cart) == 0) { ?>
		<p class="note"><?php echo $this->translate("Košarica je prazna.") ?></p>
		<?php } else { ?>
			<table class="cart">
				<thead>
					<tr>
						<th scope="col" style="width: 75px;"></th>
						<th scope="col">Naziv:</th>
						<th scope="col">Tip:</th>
						<th scope="col" style="width: 130px;">Cena:</th>
						<th scope="col" style="width: 130px;">Količina:</th>
						<th scope="col" style="width: 130px;">Skupaj:</th>
						<?php if(!$this->noedit) { ?>
						<th scope="col" style="width: 82px;"></th>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
			<?php
				$n = 0;
				$priceTotal = 0.0;
				
				foreach($this->cart as $item) { 
					
					//////////////////////////////////////////////////////////////////// Pool Graphics
					
					if($item['object'] instanceof Object_Item) {			        
			        
						$j = 0;
						$price = 0.0;
						
						
						if($item['type'] == 'delivery') {
						    $price = $item['object']->getPrice();
						} else {
						    $price = $item['object']->getPricePreorder();
						}
					?>			
					<tr>
						<td class="image">
							<?php if($item['object']->getImage()) { ?>
							<img src="<?= $item['object']->getImage()->getThumbnail("list") ?>" class="small framed"/>
							<?php } ?>
						</td>
						<td class="desc">
							<span class="text big"><?php echo $item['object']->getName() ?> <?= $item['object']->getId() ?></span>
						</td>
						
						<td class="center desc" style="width: 100px; text-align: left;">
						<?php 
						    if($this->noedit) {
						        if($item['type'] == 'delivery')
    							     echo $this->translate("DELIVERY");
    							        
    							if($item['type'] == 'download') 
    							    echo $this->translate("DOWNLOAD"); 
						        
						    } else {
						        ?>
    							<input type="radio" name="buytype<?= $item['object']->getId() ?>" id="p<?= $item['object']->getId() ?>" class="buytype" value="delivery" <?php if($item['type'] == 'delivery') {?>checked="checked"<?php }?>/><?= $this->translate("DELIVERY") ?><br />
    							<input type="radio" name="buytype<?= $item['object']->getId() ?>" id="p<?= $item['object']->getId() ?>" class="buytype" value="download" <?php if($item['type'] == 'download') {?>checked="checked"<?php }?>/><?= $this->translate("DOWNLOAD") ?>						        
						        <?php 
						    } 
						?>
						</td>
												
						<td class="center desc">
							<span class="text big"><?php echo number_format($price,2)?> &euro;</span>
						</td>
						
						<td class="center desc">
							<?php if($item['type'] == 'delivery') { ?>
							<form action="/kosarica/kolicina" method="post">
								
									<?php if($this->noedit) { 
                                              echo $item['quantity'];
									      } else {
									    ?>
									    <div style="float: left;">
        									<input type="text" class="quantity" name="quantity" value="<?php echo $item['quantity'] ?>" id="p<?= $item['object']->getId() ?>"/>
        									<input type="hidden" name="index" value="<?= $n ?>"/>
										</div>        											
        								<div style="float: left; margin-top: 5px; margin-left: 5px;">
        									<a href="#" class="refresh" onclick="return false;"><img src="/static/img/refresh.png"/></a>
        								</div>																	    
									    <?php 
									} ?>  	
								

							</form>
						    <?php } ?>
						</td>
												
						<td class="center">
							<span class="text big"><?php echo number_format($price * $item['quantity'] ,2)?> &euro;</span>
							<?php 
								$priceTotal += $price * $item['quantity'];
							?>
						</td>
						<?php if(!$this->noedit) { ?>
						<td>
							<a href="/cart-remove/<?php echo $n ?>" class="button">Odstrani</a>
						</td>
						<?php } ?>
					</tr>
					<?php
						$n++; 
					}
				}
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align: right;"><span class="text big" >DDV: </span></td>
						<td style="text-align: center;"><span class="text big" ><?php echo number_format(($priceTotal*20/100),2) ?> &euro;</span></td>
						<?php if(!$this->noedit) { ?>
							<td>&nbsp;</td>
						<?php } ?>
					</tr>				
					<tr>
						<td colspan="5" style="text-align: right;"><span class="text big" >Skupaj za plačilo (z DDV): </span></td>
						<td style="text-align: center;"><span class="text big" ><?php echo number_format($priceTotal,2) ?> &euro;</span></td>
						<?php if(!$this->noedit) { ?>
							<td>&nbsp;</td>
						<?php } ?>
					</tr>
				</tfoot>
			</table>
			
			
			<?php if(!$this->noedit) { ?>
			<div style="text-align: right;">
				<a href="/nakup/korak2" class="button bigger"><?php echo $this->translate("Naprej") ?></a>
			</div>
			<?php } ?>
			
		<?php }?>