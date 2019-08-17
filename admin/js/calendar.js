/**
 *
 <div id="calendar" class="calendar">
 <p>Please enable Javascript to view this calendar.</p>
 </div>
 $('#calendar').calendarWidget({
      onselectday: this.onSelectDate.bind(this),
      urlParams: [paramName,...] // urlparams to add in url hrefs,
			tdAditionalContent: {'YYYY-MM-DD': 'shtml'},
			tdClass: ''
   });
 */
(function ($) {
	var methods = {
		/**
		 * @param params
		 */
		calendarWidget: function (params) {

			var now = new Date();
			var thismonth = msweb.urlGet('month');
			if (!thismonth)
				thismonth = now.getMonth();

			var thisyear = msweb.urlGet('year') || now.getYear() + 1900;

			var opts = {
				month: thismonth,
				year: thisyear
			};

			$.extend(opts, params);

			this.tdAditionalContent = opts.tdAditionalContent || {};

			var monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
			var dayNames = ['Воскреснье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
			var month = i = parseInt(opts.month);
			var mmonth = month + 1;
			mmonth = String(mmonth).length == 1 ? '0' + mmonth : mmonth;
			var mmonthMinus = String(month).length == 1 ? '0' + month : month;
			var mmonthPlus  = month + 2;
			mmonthPlus = String(mmonthPlus).length == 1 ? '0' + mmonthPlus : mmonthPlus;

			var year = parseInt(opts.year);
			var m = 0;
			var table = '';
			var urlParams = '', urlParamValue;
			if (opts.urlParams) {
				for (var i = 0; i < opts.urlParams.length; i++) {
					if (urlParamValue = msweb.urlGet(opts.urlParams[i])) {
						urlParams += '&' + opts.urlParams[i] + '=' + urlParamValue;
					}
				}
			}


			// next month
			if (month == 11) {
				var next_month = '<a href="?month=' + 1 + '&amp;year=' + (year + 1);
				if (urlParams)
					next_month += urlParams;
				next_month += '" title="' + monthNames[0] + ' ' + (year + 1) + '">' + monthNames[0] + ' ' + (year + 1) + '</a>';
			} else {
				var next_month = '<a href="?month=' + (month + 1) + '&amp;year=' + (year);
				if (urlParams)
					next_month += urlParams;
				next_month += '" title="' + monthNames[month + 1] + ' ' + (year) + '">' + monthNames[month + 1] + ' ' + (year) + '</a>';
			}

			// previous month
			if (month == 0) {
				var prev_month = '<a href="?month=' + 12 + '&amp;year=' + (year - 1);
				if (urlParams)
					prev_month += urlParams;
				prev_month += '" title="' + monthNames[11] + ' ' + (year - 1) + '">' + monthNames[11] + ' ' + (year - 1) + '</a>';
			} else {
				var prev_month = '<a href="?month=' + (month - 1) + '&amp;year=' + (year);
				if (urlParams)
					prev_month += urlParams;
				prev_month += '" title="' + monthNames[month - 1] + ' ' + (year) + '">' + monthNames[month - 1] + ' ' + (year) + '</a>';
			}
			table += ('<div class="nav-prev">' + prev_month + '</div>');

			table += ('<h3 id="current-month">' + monthNames[month] + ' ' + year + '</h3>');
			// uncomment the following lines if you'd like to display calendar month based on 'month' and 'view' paramaters from the URL
			table += ('<div class="nav-next">' + next_month + '</div>');
			table += ('<table class="calendar-month " ' + 'id="calendar-month' + i + ' " cellspacing="0">');

			table += '<tr>';

			for (var d = 0; d < 7; d++) {
				table += '<th class="weekday">' + dayNames[d] + '</th>';
			}

			table += '</tr>';

			var firstDayDate = new Date(year, month, 1);
			var firstDay = firstDayDate.getDay();

			var prev_m = month == 0 ? 11 : month - 1;
			var prev_y = prev_m == 11 ? year - 1 : year;
			var prev_days = methods.getDaysInMonth(prev_m, prev_y);

			firstDay = (firstDay == 0 && firstDayDate) ? 7 : firstDay;

			var i = 0, day, dday, iscurdate;

			var currday = moment().format('D');
			var curryear = moment().format('Y');
			var currmonth = moment().format('M') - 1;
			var dataDate;

			for (var j = 0; j < 42; j++) {
				if ((j < firstDay)) {
					day = prev_days - firstDay + j + 1;
					dday = String(day).length == 1 ? '0' + day : day;
					dataDate = year + '-' + mmonthMinus + '-' + dday;

					iscurdate = (currday == day && curryear == year && currmonth == month);
					table += '<td class="other-month';
					if (opts.tdClass)
						table += ' ' + opts.tdClass;
					if (iscurdate) {
						table += ' today';
					}
					table += '" data-date="' + dataDate + '"><span class="day">' + day + '</span>';
					if (this.tdAditionalContent[dataDate] && (typeof this.tdAditionalContent[dataDate] === 'string')) {
						table += '<div class="calendar-additional-content">'+this.tdAditionalContent[dataDate] + '</div>';
					}
					table += '</td>';
				} else if ((j >= firstDay + methods.getDaysInMonth(month, year))) {
					i = i + 1;
					day = i;
					dday = String(day).length == 1 ? '0' + day : day;
					dataDate = year + '-' + mmonthPlus + '-' + dday;

					iscurdate = (currday == day && curryear == year && currmonth == month);
					table += '<td class="other-month';
					if (opts.tdClass)
						table += ' ' + opts.tdClass;
					if (iscurdate) {
						table += ' today';
					}
					table += '" data-date="' + dataDate + '"><span class="day">' + day + '</span>';
					if (this.tdAditionalContent[dataDate] && (typeof this.tdAditionalContent[dataDate] === 'string')) {
						table += '<div class="calendar-additional-content">'+this.tdAditionalContent[dataDate] + '</div>';
					}
					table += '</td>';
				} else {
					day = j - firstDay + 1;
					dday = String(day).length == 1 ? '0' + day : day;
					dataDate = year + '-' + mmonth + '-' + dday;

					iscurdate = (currday == day && curryear == year && currmonth == month);
					table += '<td class="current-month';
					if (opts.tdClass)
						table += ' ' + opts.tdClass;
					if (iscurdate) {
						table += ' today';
					}
					table += '" data-date="' + dataDate + '"><span class="day">' + day + '</span>';
					if (this.tdAditionalContent[dataDate] && (typeof this.tdAditionalContent[dataDate] === 'string')) {
						table += '<div class="calendar-additional-content">'+this.tdAditionalContent[dataDate] + '</div>';
					}
					table += '</td>';
				}
				if (j % 7 == 6) table += ('</tr>');
			}

			table += ('</table>');

			this.html(table);

			if (opts.onselectday && typeof opts.onselectday === 'function') {
				this.find('td.other-month, td.current-month').click(function () {
					var date = this.getAttribute('data-date');
					opts.onselectday(date, this);
				});
			}
		},


		/**
		 * @param month
		 * @param year
		 * @returns {number}
		 */
		getDaysInMonth: function (month, year) {
			var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
			if ((month == 1) && (year % 4 == 0) && ((year % 100 != 0) || (year % 400 == 0))) {
				return 29;
			} else {
				return daysInMonth[month];
			}
		},


		/**
		 * добавить доп контент в ячейку
		 * @param date - 'Y-m-d'
		 * @param content - shtml
		 */
		setAdditionalContent: function (date, content) {
			var td = this.find('[data-date="'+date+'"]');
			if (!td.length) return;
			var currAdditional = td.find('div.calendar-additional-content');
			if (!currAdditional.length) {
				currAdditional = $('<div>', {'class': 'calendar-additional-content'});
				td.append(currAdditional);
			}
			currAdditional.html(content);
		}
	};


	$.fn.calendarWidget = function (method) {
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.calendarWidget.apply(this, arguments);
		} else {
			$.error('Метод с именем ' + method + ' не существует для jQuery.tooltip');
		}
	};

})(jQuery);