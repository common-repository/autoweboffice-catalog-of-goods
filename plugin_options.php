<?php
$autoweboffice_catalog_of_goods_num_goods = 10;
$autoweboffice_catalog_of_goods_api_key_get = '';
$autoweboffice_catalog_of_goods_storesId = '';
$autoweboffice_catalog_of_goods_id_stores = 0;

if (get_option('autoweboffice_catalog_of_goods_num_goods') !== FALSE and get_option('autoweboffice_catalog_of_goods_num_goods') > 0)
{
	$autoweboffice_catalog_of_goods_num_goods = (int)get_option('autoweboffice_catalog_of_goods_num_goods');
}

if (get_option('autoweboffice_catalog_of_goods_api_key_get') !== FALSE)
{
	$autoweboffice_catalog_of_goods_api_key_get = get_option('autoweboffice_catalog_of_goods_api_key_get');
}

if (get_option('autoweboffice_catalog_of_goods_storesId') !== FALSE)
{
	$autoweboffice_catalog_of_goods_storesId = get_option('autoweboffice_catalog_of_goods_storesId');
}

if (get_option('autoweboffice_catalog_of_goods_id_stores') !== FALSE)
{
	$autoweboffice_catalog_of_goods_id_stores = (int)get_option('autoweboffice_catalog_of_goods_id_stores');
}

// Получаем дату последноне обновления товаров
$autoweboffice_catalog_of_goods_update_date = '0000-00-00 00:00:00';

if (get_option('autoweboffice_catalog_of_goods_update_date') !== FALSE)
{
	$autoweboffice_catalog_of_goods_update_date = $this->get_carent_datetime(get_option('autoweboffice_catalog_of_goods_update_date'));
}
?>

<div class="wrap" id="center-panel">
<div id="icon-options-general" class="icon32"><br></div>
<h2>Основные настройки модуля «Католог товаров»</h2>

<?php
// Выводим кнопку Обновить товары только в случае если указаны настройки подключения
if(trim($autoweboffice_catalog_of_goods_api_key_get) != '' and $autoweboffice_catalog_of_goods_id_stores > 0
	and trim($autoweboffice_catalog_of_goods_storesId) != '')
{
?>
<form action="admin.php?page=options-catalog-of-goods" method="POST">
	<input type="hidden" name="update" value="true" />
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Обновить товары"></p>
	<p class="description">Дата последнего обновления товаров: <?php echo $autoweboffice_catalog_of_goods_update_date;?></p>
</form>
<?php
}
?>
<form action="admin.php?page=options-catalog-of-goods" method="POST">
 
<h3 class="title">Настройки отображения:</h3>
 
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="autoweboffice_catalog_of_goods_num_goods">Товаров на странице:</label></th>
			<td>
			<input name="autoweboffice_catalog_of_goods_num_goods" type="number" step="1" min="1" id="autoweboffice_catalog_of_goods_num_goods" value="<?php echo $autoweboffice_catalog_of_goods_num_goods; ?>" class="small-text">
			<p class="description">Количество товаров выводимое на одной странице каталога</p>
			</td>
		</tr>
	</tbody>
</table>

<h3 class="title">Настройки подключения к магазину зарегистрированному в сервисе АвтоОфис:</h3> 

<table class="form-table">
	<tbody>
		<tr valign="top">
			<th scope="row"><label for="autoweboffice_catalog_of_goods_api_key_get">API_KEY_GET</label></th>
			<td><input name="autoweboffice_catalog_of_goods_api_key_get" type="text" id="autoweboffice_catalog_of_goods_api_key_get" 
			value="<?php echo $autoweboffice_catalog_of_goods_api_key_get; ?>" class="regular-text">
			<p class="description">API-ключ для получения данных</p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="autoweboffice_catalog_of_goods_storesId">Идентификатор магазина:</label></th>
			<td><input name="autoweboffice_catalog_of_goods_storesId" type="text" id="autoweboffice_catalog_of_goods_storesId" 
			value="<?php echo $autoweboffice_catalog_of_goods_storesId; ?>" class="regular-text">
			<p class="description">Уникальный идентификатор магазина</p></td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="autoweboffice_catalog_of_goods_id_stores">ID магазина:</label></th>
			<td><input name="autoweboffice_catalog_of_goods_id_stores" type="text" id="autoweboffice_catalog_of_goods_id_stores" 
			value="<?php echo $autoweboffice_catalog_of_goods_id_stores; ?>" class="regular-text">
			</td>
		</tr>
	</tbody>
</table>
<input type="hidden" name="save" value="true" />
<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения"></p>

</form>
</div> <!-- /#center-panel -->
