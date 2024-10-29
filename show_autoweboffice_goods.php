<div id="center-panel">
<?php 
if (count($this->data['goods']) > 0)
{
	foreach ($this->data['goods'] as $key => $goods)
	{
		echo $goods['own_description'];
	}
}
else
{
	echo 'Запрашиваемой вами страницы не существует на сайте нашей компании.';
} 
?>
</div> <!-- /#center-panel -->
