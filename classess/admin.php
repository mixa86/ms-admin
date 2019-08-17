<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

namespace CALC;


class Admin
{
	static public function getTemplate($name)
	{
		if (file_exists(ABCPATH . '/admin/templates/' . $name . '.php'))
			include_once ABCPATH . '/admin/templates/' . $name . '.php';
	}

	static public function getHeader()
	{
		include_once ABCPATH . '/admin/templates/header.php';
	}

	static public function getFoter()
	{
		include_once ABCPATH . '/admin/templates/footer.php';
	}

	static public function getMenu()
	{
		$shtml = '';
		$pages = array(
			array(
				'id' => 1,
				'data' => array(
					'Пример страницы' => ABCURL . '/admin/sample'
				),
			),

			/*array(
					'id' => 2,
					'data' => array(
							'Другие страницы' => array(
									array(
											'id' => 3,
											'data' => array(
													'Page 1' => ABCURL . '/admin/page1'
											)
									),
									array(
											'id' => 4,
											'data' => array(
													'Page 2' => ABCURL . '/admin/page2'
											)
									)
							)
					)
			)*/
		);


		$shtml .= '
			<ul class="navbar-nav mr-auto sidenav" id="navAccordion">';
		$x = 0;


		foreach ($pages as $page) {
			foreach ($page['data'] as $pageName => $pageData) {
				// для средних можно добавить <span class="sr-only">(current)</span>
				// для активных на всех класс active
				$x++;
				if (is_string($pageData)) {
					$shtml .= '<li class="nav-item" data-id="' . $page['id'] . '">
											<a class="nav-link" href="' . $pageData . '">' . $pageName . ' </a>
										 </li>';
				} else {
					$shtml .= '
				<li class="nav-item">
				<a class="nav-link nav-link-collapse" href="#" data-toggle="collapse" data-target="#collapseSubItems' . $x . '" aria-controls="collapseSubItems2" aria-expanded="false">' . $pageName . '</a>
				<ul class="nav-second-level collapse" id="collapseSubItems' . $x . '" data-parent="#navAccordion">
					';
					foreach ($pageData as $p) {
						foreach ($p['data'] as $pn => $url) {
							$shtml .= '
					<li class="nav-item" data-id="' . $p['id'] . '">
						<a class="nav-link" href="' . $url . '">
							<span class="nav-link-text">' . $pn . '</span>
						</a>
					</li>
					';
						}
					}
					$shtml .= '
				</ul>
			</li>
				';
				}
			}
		}

		$shtml .= '</ul>';

		return $shtml;
	}

	/**
	 * добавляет код js в массив для вывода прямо в html
	 * @param $name
	 * @param $str
	 * @param bool $inFooter
	 */
	static public function addInlineScript($str, $inFooter = true, $removeAfterLoad = false)
	{
		$arr = array('script' => $str);
		if ($removeAfterLoad) {
			$arr['remove_after_load'] = true;
		}

		if ($inFooter)
			$GLOBALS['inline_scripts']['footer'][] = $arr;
		else
			$GLOBALS['inline_scripts']['header'][] = $arr;
	}

	/**
	 * Подключение скрипта
	 */
	static public function addScript($src, $inFooter = true)
	{
		if ($inFooter)
			$GLOBALS['scripts']['footer'][] = $src;
		else
			$GLOBALS['scripts']['header'][] = $src;
	}

	static public function addStyle($src)
	{
		$GLOBALS['styles'][] = $src;
	}

	static public function includeScripts($scriptName)
	{
		global $config;
		$compile = !empty($config['DEBUG']) ? time() : '1559755042';
		if (file_exists(ABCPATH . '/admin/js/' . $scriptName . '.js'))
			self::addScript(ABCURL . '/admin/js/' . $scriptName . '.js?v=' . $compile);
	}

	static public function includeStyle($styleName)
	{
		global $config;
		$compile = !empty($config['DEBUG']) ? time() : '1559755042';
		if (file_exists(ABCPATH . '/admin/css/' . $styleName . '.css'))
			self::addStyle(ABCURL . '/admin/css/' . $styleName . '.css?v=' . $compile);
	}

	static public function getPageHeadLine($str)
	{
		return '<h4 class="admin-headline">' . $str . '</h4>';
	}

}