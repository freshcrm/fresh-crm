<?php $this->layout()->setLayout('standard'); ?>

<br class="clear"/>
<h3>Naročilo:</h3>
<div class="entry-content">
		<?php if(count($this->cart) == 0) { ?>
		<p class="note"><?php echo $this->translate("Košarica je prazna.") ?></p>
		<?php } else { ?>
			<table>
				<thead>
					<tr>
						<th scope="col" style="width: 75px;"></th>
						<th scope="col">Naziv:</th>
						<th scope="col">Dimenzija:</th>
						<th scope="col">Cena:</th>
						<th scope="col">Količina:</th>
						<th scope="col">Skupaj:</th>
					</tr>
				</thead>
				<tbody>
			<?php
				$n = 0;
				$priceTotal = 0.0;
				
				foreach($this->cart as $item) { 
					
					//////////////////////////////////////////////////////////////////// Pool Graphics
					
					if($item['object'] instanceof Object_PoolGraphics) {			        
			        
				        foreach($item['object']->getImages() as $image)  {
				        	
				        	$asset = Asset::getByPath($image->getImage());
				        	if($asset instanceof Asset_Image) {
								$pathToImageThumb = $asset->getThumbnail("thumb");
								$pathToImage = $asset->getThumbnail("fullwidth");
				        	} else { 
								$pathToImageThumb = "default";
								$pathToImage = "default";
				        	}
								
							break;
				        }
				        
						$j = 0;
						$price = 0.0;
						$dimension = '';
						
						foreach($item['object']->getDimensionPrice() as $p) {
							$price = $p->getPrice();
							
							if($j == $item['dimension']) {
								$dimension = $p->getDimension();
								break;
							}
								
							$j++;
						}				        
				        
				        
						?>			
					<tr>
						<td>
							<div class="post-image">
								<img src="<?php echo $pathToImageThumb?>" class="small framed"/>
							</div>
						</td>
						<td>
							<p><span class="text big"><?php echo $item['object']->getTitle() ?></span>
						</td>
						
						<td>
							<p><?php echo $dimension ?></p>
						</td>
						
						<td>
						
							<span class="text big"><?php echo number_format($price,2)?> &euro;</span>
						</td>
						
						<td>
							<span class="text big"><?php echo $item['quantity'] ?></span>
						</td>
						<td>
							<span class="text big"><?php echo number_format($price * $item['quantity'] ,2)?> &euro;</span>
							<?php 
								$priceTotal += $price * $item['quantity'];
							?>
						</td>

					</tr>
					<?php
						$n++; 
					}
				} // if Pool Graphics	
				?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="5" style="text-align: right;"><span class="text big" >Skupaj za plačilo (z DDV): </span></td>
						<td><span class="text big" ><?php echo number_format($priceTotal,2) ?> &euro;</span></td>
						
					</tr>
				</tfoot>
			</table>

		<?php }?>
	</div>
