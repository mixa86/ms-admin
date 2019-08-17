/*
 * @author Mixail Sayapin
 * https://ms-web.ru
 */
(function ($) {
	var AA = ipotechniycalculator;

	AA.initNav = function () {
		var nav = $('#navbarCollapse');
		nav.find('.nav-link-collapse').on('click', function () {
			$('.nav-link-collapse').not(this).removeClass('nav-link-show');
			$(this).toggleClass('nav-link-show');
		});
		var items = nav.find('li.nav-item');
		items.each(function (idx, el) {
			var dataId = el.getAttribute('data-id');
			if (dataId != AA.pageId)
				el.classList.remove('active');
			else
				el.classList.add('active');

			if (dataId == AA.pageId) {
				var elParent = $(el).parents('ul:first');

				if (elParent.length && elParent.hasClass('nav-second-level')) {
					elParent.parent().toggleClass('nav-link-show');
					elParent.toggleClass('collapse');
					elParent.prev().addClass('nav-link-show');
				}
			}
		});
	};


	$(document).ready(function () {
		AA.initNav();
	});
})(jQuery);


