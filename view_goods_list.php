<div class="wrap">
<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div><h2>Список товаров в Каталоге товаров</h2>
<br />
	 <table class="widefat">
	 
	  <thead>
	    <tr class="table-header">
		 
		 <th>Товар</th>
		 <th>Свое описание</th>
		 <th>Снят с продажи</th>
		 <th>Не показывать</th>
		 <th>Цена</th>
		 <th>Валюта</th>
		</tr>
	  </thead>
	  
	  <tbody>
	   <?php if (count($this->data['goods']) > 0): ?>
	   <?php foreach ($this->data['goods'] as $key => $goods): ?>
		<tr>
		 <td>  
		   <div><a style="text-decoration: none;" href="admin.php?page=edit-catalog-of-goods&action=edit&id=<?php echo $goods['id_goods']; ?>" 
		   title="<?php echo $goods['goods'];?>"><?php echo $goods['goods'];?></a></div>
		 </td>
		 <td>
		  <div>
		  <?php
			if($goods['used_own_brief_description'] > 0)
			{
				echo 'Да';
			}
			else
			{
				echo 'Нет';
			}
		  ?>
		  </div>
		 </td>
		 <td>
		  <div>
		  <?php
			if($goods['not_sold'] > 0)
			{
				echo 'Да';
			}
			else
			{
				echo 'Нет';
			}
		  ?>
		  </div>
		 </td>
		 <td>
		  <div>
		  <?php
			if($goods['not_show'] > 0)
			{
				echo 'Да';
			}
			else
			{
				echo 'Нет';
			}
		  ?>
		  </div>
		 </td>
		 <td>
		  <div><?php echo number_format($goods['price'], 2, ',', ' '); ?></div>
		 </td>
		 <td><?php echo $goods['currency']; ?></td>
		</tr>
	   <?php endforeach; ?>
	   
	   <?php else:?>
	    <tr>
		 <td colspan="7" style="text-align:center">Списо товаров пуст.</td>
		</tr>
	   <?php endif;?>
	  </tbody>

	 </table> 
</div>

