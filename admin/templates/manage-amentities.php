<?
/**
 * @author Mixail Sayapin
 * https://ms-web.ru
 */


if (!defined('MSWEB_HOTELS_PLUGIN'))
	die;

echo Admin::getPageHeadLine('Управление услугами в номерах');
?>
<div class="row">
	<div class="col-sm-3 offset-sm-9 mb-3">
		<button class="btn btn-success" onclick="HMA.addAmentity()">
			Добавить услугу
		</button>
	</div>
</div>
<div id="amentities-container"></div>
<?
echo Main::getSvgs();
?>