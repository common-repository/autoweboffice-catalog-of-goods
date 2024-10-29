<div id="center-panel">
<?php 
if (count($this->data['goods']) == 0)
{
?>
<div id="reviews-list" class="noelements">
	<p style="text-align:center;">Товары отсутствуют.</p>
</div> 
<?php
}
else
{
?>
	
<?php
	// Получаем идентификатор магазина
	$storesID = get_option('autoweboffice_catalog_of_goods_storesId');
	
	foreach ($this->data['goods'] as $key => $goods)
	{
	// Получаем ссылку на страницу подробного описания товара
	$description_link = '';
	
	if(trim($goods['url_page']) != '' and $goods['used_own_description'] == 0)
	{
		$description_link = $goods['url_page'];
	}
	
	// Если стоит использовать свою страницу описания
	if($goods['used_own_description'] > 0)
	{
		$description_link = '?'.getenv("QUERY_STRING").'&id_goods='.$goods['id_goods'];
	}
	
	?>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<td width="150px;">
					<?php
					if(trim($description_link) != '' )
					{
					?>
					<a  href="<?php echo $description_link;?>" title="<?php echo $goods['goods'];?>">
					<?php
					}
					?>
					<img src="<?php echo $goods['image'];?>" width="150px;" border="0" />
					<?php
					if(trim($description_link) != '' )
					{
					?>
					</a>
					<?php
					}
					?>
				</td>
				<td style="vertical-align: top;">
					<p><b style="font-size: 120%;" align="left">
					<?php
					if(trim($description_link) != '' )
					{
					?>
					<a style="text-decoration: none;" href="<?php echo $description_link;?>" title="<?php echo $goods['goods'];?>">
					<?php
					}
						echo $goods['goods'];
					if(trim($description_link) != '' )
					{
					?>
					</a>
					<?php
					}
					?>
					</b>
					<br />
					<?php
					if($goods['used_own_brief_description'])
					{
						echo $goods['own_brief_description'];
					}
					else
					{
						echo $goods['brief_description'];
					}
					?>
					</p>
				</td>
			</tr>
			<tr valign="top">
				<td style="vertical-align: middle;">
					<div align="center"><b>Цена: <?php echo number_format($goods['price'], 2, ',', ' ').'&nbsp;'.$goods['currency']; ?></b></div>
				</td>
				<td style="vertical-align: middle;">
					<div align="right">
					<?php
					// Если страница описания товара и не используем собственную страницу
					if(trim($description_link) != '' )
					{
					?>
						<input style="width: 100px;" type="submit" id="show_autoweboffice_catalog_of_goods_view_<?php echo $goods['id_goods'];?>" 
						value="Подробнее..." onClick="location.href='<?php echo $description_link; ?>'">
					<?php
					}
					?>
					&nbsp;<input style="width: 100px;" type="submit" id="show_autoweboffice_catalog_of_goods_view_<?php echo $goods['id_goods'];?>" 
					value="Заказать" onClick="location.href='<?php echo 'http://'.$storesID.'.autokassir.ru/?r=ordering/cart/as1&id='.$goods['id_goods'].'&lg='.$goods['language']; ?>'">
					</div>	
				</td>
			</tr>
		</tbody>
	</table>
	<?php 
	
	
	}  
?>

<?php
} 
?>
</div> <!-- /#center-panel -->
