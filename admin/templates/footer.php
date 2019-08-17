<?php
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */

if (!defined('ABCPATH'))
	die;
global $config;
?>
</div>
</main>

<footer class="footer">
	<div class="container">
		<div class="text-center">
			<span>Developed by <a href="https://ms-web.ru">MS-Web</a>, 2019</span>
		</div>
	</div>
</footer>
<?
if (!empty($GLOBALS['scripts']['footer'])) {
	foreach ($GLOBALS['scripts']['footer'] as $script) {
		echo '<script src="' . $script . '"></script>' . "\n";
	}
}
?>
<?
if (!empty($GLOBALS['inline_scripts']['footer'])) { ?>

	<?
	foreach ($GLOBALS['inline_scripts']['footer'] as $key => $inline_script) {
		echo '<script id="msweb-inline-script-in-footer-' . $key . '">';
		echo $inline_script['script'];
		if (!empty($inline_script['remove_after_load'])) {
			echo 'document.getElementById(\'msweb-inline-script-in-footer-' . $key . '\') && document.getElementById(\'msweb-inline-script-in-footer-' . $key . '\').remove()';
		}
		echo "</script>\n";
	}
	?>

	<?
}
?>
</body>
</html>
