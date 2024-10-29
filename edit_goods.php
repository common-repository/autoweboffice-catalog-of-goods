<div class="wrap" id="center-panel">
<div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div><h2>Описание товара: <?php echo $this->data['goods']['goods']; ?></h2>

<h3 class="title">Описание в сервисе АвтоОфис:</h3>
<hr />
<?php 
	if(trim($this->data['goods']['brief_description']) != '')
	{
		echo $this->data['goods']['brief_description']; 
	}
	else
	{
		echo 'Нет описания...';
	}
?>
<hr />

<h3 class="title">Дополнительные настройки:</h3>

<?php
	if($this->data['goods']['used_own_brief_description'] > 0)
	{
		$checked_used_own_brief_description = 'checked="checked"';
	}
	
	if($this->data['goods']['used_own_description'] > 0)
	{
		$checked_used_own_description = 'checked="checked"';
	}
	
	if($this->data['goods']['not_show'] > 0)
	{
		$checked_not_show = 'checked="checked"';
	}
?>

<form action="admin.php?page=edit-catalog-of-goods&action=submit" method="POST">
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="used_own_brief_description">Использовать свое краткое описание:</label></th>
			<td>
				<input name="used_own_brief_description" type="checkbox" id="used_own_brief_description" 
				value="1" <?php echo $checked_used_own_brief_description;?>>
			</td>
		</tr>
		<tr valign="top">
			<td colspan="2">
				<?php
						wp_editor($this->data['goods']['own_brief_description'], 'own_brief_description' );
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="used_own_description">Страницу описания (URL-адрес) в АвтоОфис::</label></th>
			<td>
				<?php 
				if(trim($this->data['goods']['own_brief_description']) == '')
				{
					echo 'Не указана...';
				}
				else
				{
					echo '<a href="'.$this->data['goods']['own_brief_description'].'" target="_blank">'.$this->data['goods']['own_brief_description'].'</a>';
				}
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="used_own_description">Использовать свое полное описание:</label></th>
			<td>
				<input name="used_own_description" type="checkbox" id="used_own_description" 
				value="1" <?php echo $checked_used_own_description;?>>
			</td>
		</tr>
		<tr valign="top">
			<td colspan="2">
				<?php
						wp_editor($this->data['goods']['own_description'], 'own_description' );
				?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="not_show">Не показывать в каталоге:</label></th>
			<td>
				<input name="not_show" type="checkbox" id="not_show" 
				value="1" <?php echo $checked_not_show;?>>
			</td>
		</tr>
	</tbody>
</table>

<input type="hidden" name="id" value="<?php echo $this->data['goods']['id_goods'] ?>" />
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения"></p>

 </form>
</div> <!-- /#center-panel -->
