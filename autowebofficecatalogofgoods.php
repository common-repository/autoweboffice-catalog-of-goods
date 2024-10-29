<?php
/*
 * Файл отвечающий за запуск плагина
 * Plugin Name: AutoWebOffice Catalog  Of Goods
 * Plugin URI: http://wordpress.org/plugins/autoweboffice-catalog-of-goods/
 * Description: Создание каталога товаров для магазина зарегистрированного в сервисе АвтоОфис
 * Version: 1.1
 * Author: Alexander Kruglov (zakaz@autoweboffice.com)
 * Author URI: http://autoweboffice.com/
 */
 
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) 
{ 
	die('You are not allowed to call this page directly.'); 
}
 
if (!class_exists('AutowebofficeCatalogOfGoods')) 
{
	// Основной класс плагина
	class AutowebofficeCatalogOfGoods 
	{
		// Хранение внутренних данных
		public $data = array();
		// Конструктор объекта
		// Инициализация основных переменных
		function AutowebofficeCatalogOfGoods()
		{
			global $wpdb;
		
			## Объявляем константу инициализации нашего плагина
			define('AutowebofficeCatalogOfGoods', true);
			
			## Название файла нашего плагина 
			$this->plugin_name = plugin_basename(__FILE__);
			
			## URL адресс для нашего плагина
			$this->plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));
			
			## Таблица для хранения наших отзывов
			## обязательно должна быть глобально объявлена перменная $wpdb
			$this->tbl_autoweboffice_goods   = $wpdb->prefix.'autoweboffice_goods';
			
			## Функция которая исполняется при активации плагина
			register_activation_hook($this->plugin_name, array(&$this, 'activate'));
			
			## Функция которая исполняется при деактивации плагина
			register_deactivation_hook($this->plugin_name, array(&$this, 'deactivate'));
			
			##  Функция которая исполняется удалении плагина
			register_uninstall_hook($this->plugin_name, array(&$this, 'uninstall'));
			
			// Если мы в адм. интерфейсе
			if (is_admin()) 
			{
				// Добавляем стили и скрипты
				add_action('wp_print_scripts', array(&$this, 'admin_load_scripts'));
				add_action('wp_print_styles', array(&$this, 'admin_load_styles'));
				
				// Добавляем меню для плагина
				add_action('admin_menu', array(&$this, 'admin_generate_menu'));
				
			} 
			else 
			{
				// Добавляем стили и скрипты
				add_action('wp_print_scripts', array(&$this, 'site_load_scripts'));
				add_action('wp_print_styles', array(&$this, 'site_load_styles'));
				
				// На любой странице сайта для вывода размещаем код [show_autoweboffice_catalog_of_goods]
				add_shortcode('show_autoweboffice_catalog_of_goods', array (&$this, 'site_show_catalog_of_goods'));
			}
		}
		
		/**
		 * Загрузка необходимых скриптов для страницы управления 
		 * в панели администрирования
		 */
		function admin_load_scripts()
		{
		
		}
		
		/**
		 * Загрузка необходимых стилей для страницы управления 
		 * в панели администрирования
		 */
		function admin_load_styles()
		{	

		}
		
		/**
		 * Генерируем меню
		 */	
		function admin_generate_menu()
		{
			// Добавляем основной раздел меню
			add_menu_page('Каталог товаров', 'Каталог товаров', 'manage_options', 'edit-catalog-of-goods', array(&$this, 'admin_edit_catalog_of_goods'));
			 
			// Добавляем дополнительный раздел
			add_submenu_page('edit-catalog-of-goods', 'Настройки плагина Каталог товаров', 'Настройки', 'manage_options', 'options-catalog-of-goods', array(&$this, 'admin_options_catalog_of_goods'));
			add_submenu_page('edit-catalog-of-goods', 'Справка по работе с плагином Каталог товаров', 'Справка', 'manage_options', 'plugin_info', array(&$this,'admin_catalog_of_goods_info'));
		}
		
		/**
		 * Выводим список товаров для редактирования
		 */
		public function admin_edit_catalog_of_goods()
		{
			global $wpdb;
			
			// Получаем текущее действие
			$action = isset($_GET['action']) ? $_GET['action'] : null ;
			
			switch ($action) 
			{
				// Редактирование
				case 'edit':
					// Получаем данные из БД
					$this->data['goods'] 	= $wpdb->get_row("SELECT * FROM `" . $this->tbl_autoweboffice_goods . "` WHERE `id_goods`= ". (int)$_GET['id'], ARRAY_A);
					
					// Подключаем страницу для отображения результатов 
					include_once('edit_goods.php');
				break;
				
				// Сохранение переданных данных
				case 'submit':
				
					$inputData = array(
						'used_own_brief_description' 	  => intval($_POST['used_own_brief_description']),
						'own_brief_description'   => $_POST['own_brief_description'],
						'used_own_description' 	  => intval($_POST['used_own_description']),
						'own_description'   => $_POST['own_description'],
						'not_show'   => intval($_POST['not_show']),
					);
				
					$id_goods = intval($_POST['id']);
				
					if ($id_goods == 0) return false;
				
					// Обновляем существующую запись
					$wpdb->update($this->tbl_autoweboffice_goods, $inputData, array('id_goods' => $id_goods));
					
					// Показываем список товаров
					$this->admin_show_goods_list();
					
				break;
				
				default:
					// Показываем список товаров
					$this->admin_show_goods_list();
			}
			
		}
		
		/**
		 * Функция для отображения списка товаров в адм. панели
		 */
		private function admin_show_goods_list()
		{
			global $wpdb;
			
			// Получаем данные из БД
			$this->data['goods'] = $wpdb->get_results("SELECT * FROM `" . $this->tbl_autoweboffice_goods . "` WHERE deleted=0 ORDER BY goods", ARRAY_A);
			
			// Подключаем страницу для отображения результатов 
			include_once('view_goods_list.php');
		}
		
		/**
		 * Выводим список настроек плагина
		 */
		public function admin_options_catalog_of_goods()
		{
			if ($_POST['save']) 
			{
				// Сохраняем настройку плагина в таблицу wp_options
				update_option('autoweboffice_catalog_of_goods_num_goods', $_POST['autoweboffice_catalog_of_goods_num_goods']);
				update_option('autoweboffice_catalog_of_goods_api_key_get', $_POST['autoweboffice_catalog_of_goods_api_key_get']);
				update_option('autoweboffice_catalog_of_goods_storesId', $_POST['autoweboffice_catalog_of_goods_storesId']);
				update_option('autoweboffice_catalog_of_goods_id_stores', $_POST['autoweboffice_catalog_of_goods_id_stores']);				
				echo '<div id="message" class="updated fade"><p><strong>Настройки успешно сохранены.</strong></p></div>';
			}
			
			if($_POST['update'])
			{
				// Обновляем данные по товарам
				$result= $this->admin_update_catalog_of_goods();
				
				if($result['goods'])
				{
					// Сохраняем дату обновления товаров в таблицу wp_options
					update_option('autoweboffice_catalog_of_goods_update_date', time());
					echo '<div id="message" class="updated fade"><p><strong>Обновлено товаров: '.$result['goods'].'.</strong></p></div>';
				}
				else
				{
					echo '<div id="message" class="updated fade"><p><strong style="color: red;">'.$result['errors'].'</strong></p></div>';
				}
			}
			
			$this->admin_view_options_catalog_of_goods();
		}
		
		/**
		 * Функция для отображения настроек плагина
		 */
		private function admin_view_options_catalog_of_goods()
		{		
			// Подключаем страницу с настроками плгина
			include_once('plugin_options.php');
		}
		
		/**
		 * Функция для обновления информации о товарах
		 */
		private function admin_update_catalog_of_goods()
		{	
			global $wpdb;
			
			// Получаем данные по товарам магазина
			$result['goods'] = $this->getDataFromAutoOffice('getGoods', $filter); 
			
			if(!$result['goods']) // Если данные не получены, то прерываем
			{
				$result['errors'] = 'Не удалось получить данные по товарам.';
				$result['goods'] = false;
				return $result;
			}
			
			// Получаем данные по товарам магазина
			$shop_mainsettings = $this->getDataFromAutoOffice('getShopMainsettings'); 
			
			if(!$shop_mainsettings) // Если данные не получены, то прерываем
			{
				$result['errors'] = 'Не удалось получить данные по основным настрокам магазина.';
				$result['goods'] = false;
				return $result;
			}
			
			$currency = $shop_mainsettings['0']['currency'];
			$language = $shop_mainsettings['0']['stores_language'];
			
			// Проверяем получен ли массив с данными
			if(!is_array($result['goods']))
			{
				$result['errors'] = $result['goods'];
				$result['goods'] = false;
				return $result;
			}
			
			// Если получено 0 товаров
			if(count($result['goods']) == 0)
			{
				return $result;
			}
			
			$num_goods = 0; // Для подсчета количества добавленных товаров
			
			foreach($result['goods'] as $key => $goods)
			{
				// Получаем данные из БД
				$goods_results = $wpdb->get_results("SELECT id_goods FROM `" . $this->tbl_autoweboffice_goods . "` WHERE id_goods='".$goods['id_goods']."'", ARRAY_A);
				
				// Если существует запись
				if (count($goods_results) > 0)
				{
					$inputData = array(
						'goods' 			=> $goods['goods'],
						'image'   			=> $goods['image'],
						'brief_description' => $goods['brief_description'],
						'price'   			=> $goods['price'],
						'url_page' 			=> $goods['url_page'],
						'not_sold'   		=> $goods['not_sold'],
						'id_goods_kind' 	=> $goods['id_goods_kind'],
						'deleted'   		=> $goods['deleted'],
						'currency' 			=> $currency,
						'language'   		=> $language
						
					);
				
					// Обновляем существующую запись
					$wpdb->update($this->tbl_autoweboffice_goods, $inputData, array('id_goods' => $goods['id_goods']));
					
					$num_goods ++;
				}
				else
				{
					// Удаляем существующую запись
					$wpdb->query("DELETE FROM `".$this->tbl_autoweboffice_goods."` WHERE `id_goods` = '".$goods['id_goods']."'");
					
					$sql_insert = "INSERT INTO `".$this->tbl_autoweboffice_goods."` 
									(`id_goods`, `goods`, `image`, 
									`brief_description`, `price`, `url_page`, 
									`not_sold`, `id_goods_kind`, `deleted`, 
									`currency`, `language`) 
									VALUES ('".$goods['id_goods']."', '".$goods['goods']."', '".$goods['image']."', 
									'".$goods['brief_description']."', '".$goods['price']."', '".$goods['url_page']."', 
									'".$goods['not_sold']."', '".$goods['id_goods_kind']."', '".$goods['deleted']."',
									'".$currency."', '".$language."');";
				}
				
				// Добавляем существующую запись
				if($wpdb->query($sql_insert))
				{
					$num_goods ++;
				}
			}
			
			$result['goods'] = $num_goods;
			return $result;
			
		}
		
		/**
		 * Загрузка необходимых скриптов для страницы отображения 
		 * плагина на сайте
		 */
		function site_load_scripts()
		{
			
		}

		/**
		 * Загрузка необходимых стилей для страницы отображения 
		 * плагина на сайте
		 */
		function site_load_styles()
		{

		}
		
		/**
		 * Список отзывов на сайте
		 */
		public function site_show_catalog_of_goods($atts, $content=null)
		{
			global $wpdb;
			
			// Если хотят увидеть подробное описание товара
			$id_goods = (int)$_GET['id_goods'];
			
			if($id_goods  > 0)
			{
				// Получаем информацию по выбранному товару
				$this->data['goods'] = $wpdb->get_results("SELECT * FROM `" . $this->tbl_autoweboffice_goods . "` 
															WHERE deleted=0 AND not_sold=0 AND not_show=0 AND id_goods = ".$id_goods."
															ORDER BY goods", ARRAY_A);
				
				## Включаем буферизацию вывода
				ob_start ();
				
				include_once('show_autoweboffice_goods.php');
				
				## Получаем данные
				$output = ob_get_contents ();
				## Отключаем буферизацию
				ob_end_clean ();

			}
			else
			{
			
				// Количество выводимых товаров на странице
				$autoweboffice_catalog_of_goods_num_goods = 10;
				if (get_option('autoweboffice_catalog_of_goods_num_goods') !== FALSE and get_option('autoweboffice_catalog_of_goods_num_goods') > 0)
				{
					$autoweboffice_catalog_of_goods_num_goods = (int)get_option('autoweboffice_catalog_of_goods_num_goods');
				}
				
				$paged = 1;
				if((int)get_query_var('paged') > 1)
				{
					$paged = get_query_var('paged');
				}
				// С какого элемента выводить
				$limit_start = ($paged - 1)*$autoweboffice_catalog_of_goods_num_goods;
				
				// Выбираем опубликаованные товары для каталога из Базы Данных (ARRAY_A - вернуть массив с данными)
				$this->data['goods'] = $wpdb->get_results("SELECT * FROM `" . $this->tbl_autoweboffice_goods . "` 
															WHERE deleted=0 AND not_sold=0 AND not_show=0 
															ORDER BY goods 
															LIMIT ".$limit_start.", ".$autoweboffice_catalog_of_goods_num_goods, ARRAY_A);
				
				## Включаем буферизацию вывода
				ob_start ();
				
				include_once('show_autoweboffice_catalog_of_goods.php');
				
				// Вывод пагинации страниц каталога
				$this->show_navigation($this->data['goods']);
				
				## Получаем данные
				$output = ob_get_contents ();
				## Отключаем буферизацию
				ob_end_clean ();
			}
			return $output;
		}
		
		function show_navigation() 
		{
			global $wpdb;
			
			// Получаем количество товаров в каталоге
			// Выбираем опубликаованные товары для каталога из Базы Данных (ARRAY_A - вернуть массив с данными)
			$goods = $wpdb->get_results("SELECT * FROM `" . $this->tbl_autoweboffice_goods . "` 
														WHERE deleted=0 AND not_sold=0 AND not_show=0 
														ORDER BY goods", ARRAY_A);
			$num_goods = count($goods );
			
			// Количество выводимых товаров на странице
			$autoweboffice_catalog_of_goods_num_goods = 10;
			if (get_option('autoweboffice_catalog_of_goods_num_goods') !== FALSE and get_option('autoweboffice_catalog_of_goods_num_goods') > 0)
			{
				$autoweboffice_catalog_of_goods_num_goods = (int)get_option('autoweboffice_catalog_of_goods_num_goods');
			}
			
			// Получаем количесво товаров в каталоге
			$pages = '';
			$max = ceil($num_goods/$autoweboffice_catalog_of_goods_num_goods); // Максимальное число страниц
			
			if (!$current = get_query_var('paged')) 
			{
				$current = 1;
			}

			$a['base'] = str_replace(999999999, '%#%', get_pagenum_link(999999999));
			$a['total'] = $max;
			$a['current'] = $current;
			 
			$total = 1; //1 - выводить текст "Страница N из N", 0 - не выводить
			
			$a['mid_size'] = 3; //сколько ссылок показывать слева и справа от текущей
			$a['end_size'] = 1; //сколько ссылок показывать в начале и в конце
			$a['prev_text'] = '&laquo;'; //текст ссылки "Предыдущая страница"
			$a['next_text'] = '&raquo;'; //текст ссылки "Следующая страница"
			 
			if ($max > 1) echo '<div class="navigation">';
			if ($total == 1 && $max > 1) $pages = '<span class="pages">Страница ' . $current . ' из ' . $max . '</span>'."\r\n";
			echo $pages . paginate_links($a);
			if ($max > 1) echo '</div>';
		}
		
		/*
		 * Функция отправляет данные по XML
		 * $function - какой функции отправляем
		 * $data - данные для отправки
		 */
		function getDataFromAutoOffice($function, $filter = '')
		{
			// Получаем данные из настроек
			$api_key_get = get_option('autoweboffice_catalog_of_goods_api_key_get');
			$id = (int)get_option('autoweboffice_catalog_of_goods_id_stores');
			$storesID = get_option('autoweboffice_catalog_of_goods_storesId');
			
			// Составляем хэш
			$hash = md5($id.$api_key_get.$storesID);
			
			// Запускаем клиент
			require_once('lib/IXR_Library.php');
			$client = new IXR_ClientSSL($storesID.'.autokassir.ru', '/?r=api/xml/startserver');

			// $client->debug = true;

			// Получаем настройки фильтра
			$filter = serialize($filter);
			
			// Обращаемся к функции, указанной в параметре
			if (!$client->query($function, array(
					'id'	=> $id,
					'hash'	=> $hash,
					'filter'	=> $filter,
			)))
			{
				if($client->getErrorCode() == -6)
				{
					return $errors = 'Не удалось подключится к магазину. Проверьте настройки подключения.';
				}
				return 'ERROR'.$client->getErrorCode().": ".$client->getErrorMessage();
			}

			return $client->getResponse();

		}
		
		/*
		 * Получаем текущее значение даты с учетом настроек часового пояса
		 * $time - текущая метка времени
		 * $formate - фотрмат отображения даты и времени
		 */
		function get_carent_datetime($time, $formate = '')
		{
			// Если не передан формат, то берем формат из настроек по умолчанию
			if($formate == '')
			{
				$formate = get_option('date_format').' '.get_option('time_format');
			}
			return date($formate , $time + get_option('gmt_offset') * HOUR_IN_SECONDS);
		}
		
		/**
		 * Показываем статическую страницу с информацией о плагине
		 */
		public function admin_catalog_of_goods_info()
		{
			include_once('plugin_info.php');
		}
		
		/**
		 * Активация плагина
		 */
		function activate() 
		{
			// Отвечает за запросы к базе данных
			global $wpdb;
			
			// Для работы с функцией dbDelta
			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			
			## Определение версии mysql
			if(version_compare(mysql_get_server_info(), '4.1.0', '>=')) 
			{
				if(!empty($wpdb->charset))
				{
					$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
				}
				
				if(!empty($wpdb->collate))
				{
					$charset_collate .= " COLLATE $wpdb->collate";
				}
			} 
			
			## Структура нашей таблицы для хранения информации о товарах магазина
			$sql_table_autoweboffice_goods = "
					CREATE TABLE `".$this->tbl_autoweboffice_goods."` (
						`id_goods` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Код товара',
						`goods` varchar(255) DEFAULT NULL COMMENT 'Название товара',
						`image` varchar(255) DEFAULT NULL COMMENT 'Основное изображение',
						`brief_description` text COMMENT 'Краткое описание товара',
						`used_own_brief_description` tinyint(4) DEFAULT '0' COMMENT 'Использовать собственное краткое описание товара',
						`own_brief_description` text COMMENT 'Собственное краткое описание товара',
						`used_own_description` tinyint(4) DEFAULT '0' COMMENT 'Использовать собственное описание товара',
						`own_description` text COMMENT 'Собственное краткое описание товара',
						`price` decimal(10,2) DEFAULT '0.00' COMMENT 'Цена товара',
						`url_page` varchar(255) NOT NULL,
						`currency` varchar(45) DEFAULT NULL COMMENT 'Валюта',
						`language` varchar(5) DEFAULT NULL COMMENT 'Язык страниц оформления заказа',
						`not_sold` tinyint(4) DEFAULT '0' COMMENT 'Товар не продается',
						`id_goods_kind` int(11) NOT NULL COMMENT 'Код вида товара',
						`deleted` tinyint(4) DEFAULT '0' COMMENT 'Товар удален',
						`not_show` tinyint(4) DEFAULT '0' COMMENT 'Не показывать в каталоге',
						PRIMARY KEY (`id_goods`)
					)".$charset_collate.";"; 
				
			## Проверка на существование таблицы	
			if($wpdb->get_var("SHOW TEBLES LIKE '$this->tbl_autoweboffice_goods'") != $this->tbl_autoweboffice_goods)
			{
				// Анализирует текущую структуру таблицы, сравнивает ee с желаемой структурой таблицы, и либо добавляет или изменяет таблицу по мере необходимости
				dbDelta($sql_table_autoweboffice_goods);
			}
		}
		
		function deactivate() 
		{
			return true;
		}
		
		/**
		 * Удаление плагина
		 */
		function uninstall() 
		{
			global $wpdb;

			// Удаляем таблицы плагина
			$wpdb->query("DROP TABLE IF EXISTS `".$this->tbl_autoweboffice_goods."`");
			
			// Удаляем настройки плагина
			delete_option('autoweboffice_catalog_of_goods_num_goods');
			delete_option('autoweboffice_catalog_of_goods_api_key_get');
			delete_option('autoweboffice_catalog_of_goods_storesId');
			delete_option('autoweboffice_catalog_of_goods_id_stores');
		}
	}
}
 
global $rprice;
$rprice = new AutowebofficeCatalogOfGoods();
?>