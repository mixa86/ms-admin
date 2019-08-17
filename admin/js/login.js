function post(data, callback) {
	$.post(ipotechniycalculator.url + '/admin/ajax.php', data, function (d) {
		try {
			if (d && d.length)
				d = JSON.parse(d);
		}
		catch (e) {

		}
		callback && callback(d);
	});
}

function userlogin(form) {
	post({
		action: 'login',
		login: form.login.value,
		password: form.password.value,
		remember: form.check.checked
	}, function (d) {
		if (d.status == 200) {
			window.location.reload();
		}
		else
			swal({
				icon: 'warning',
				text: d.error
			})
	});
}

function register(form) {
	post({
		action: 'register',
		login: form.email.value,
		password: form.regpassword.value
	}, function (d) {
		if (d.status == 200) {
			window.location.reload();
		}
	});
}

function forgotPass() {
	$('li[role="presentation"]').removeClass('active');
}

function restore(form) {
	post({
		action: 'forgot',
		login: form.email.value
	}, function (d) {
		if (d.status == 200) {
			swal({
				icon: 'success',
				text: 'На ящик ' +form.email.value + ' была отправлена ссылка для смены пароля.'
			})
		}
		else {
			swal({
				icon: 'warning',
				text: d.error
			})
		}
	});
}

function userlogout() {
	post({
		action: 'logout'
	}, function (d) {
		if (d && d.length)
			d = JSON.parse(d);
		if (d.status == 200) {
			window.location.reload();
		}
	});
}

function changepassword(form) {
	var pass1 = form.password1.value;
	var pass2 = form.password2.value;
	if (pass1 !== pass2) {
		return swal({
			icon: 'warning',
			text: 'Введённые пароли не совпадают'
		});
	}
	post({
		action: 'changepassword',
		newpassword: pass1,
		email: msweb.urlGet('email'),
		key: msweb.urlGet('key')
	}, function (d) {
		if (d && d.length)
			d = JSON.parse(d);
		if (d.status == 200) {
			swal({
				icon:'success',
				text: 'Новый пароль установлен!'
			});
			setTimeout(function () {
				debugger
				window.location.href = ipotechniycalculator.url + '/admin/';
			}, 2000);
		}
	});
}