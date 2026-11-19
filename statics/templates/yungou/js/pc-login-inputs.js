/**
 * 电脑端登录/注册/找回密码：账号、密码、验证码输入限制
 * - 账号：仅允许数字、字母、@、. （手机号11位或邮箱），禁止标点符号
 * - 密码：仅允许数字、字母，禁止标点符号
 * - 验证码：仅允许6位数字或字母，禁止符号
 */
(function () {
	function bindOne(el, allowRegex, maxLen) {
		if (!el) return;
		var re = new RegExp('[^' + allowRegex + ']', 'g');
		function filter() {
			var val = el.value;
			var filtered = val.replace(re, '');
			if (maxLen && filtered.length > maxLen) filtered = filtered.slice(0, maxLen);
			if (filtered !== val) el.value = filtered;
		}
		// 按键时拦截非法字符，避免标点等先显示再被删
		el.addEventListener('keypress', function (e) {
			var ch = typeof e.key === 'string' ? e.key : String.fromCharCode(e.which || e.keyCode);
			if (ch.length !== 1) return;
			var reOne = new RegExp('[' + allowRegex + ']');
			if (!reOne.test(ch)) e.preventDefault();
		});
		el.addEventListener('input', filter);
		el.addEventListener('paste', function () { setTimeout(filter, 0); });
		el.addEventListener('change', filter);
		if (maxLen) el.setAttribute('maxlength', String(maxLen));
		filter();
	}

	function bindInputFilter(selector, allowRegex, maxLen) {
		var list = document.querySelectorAll(selector);
		for (var i = 0; i < list.length; i++) bindOne(list[i], allowRegex, maxLen);
	}

	/** 登录页账号：手机号 11 位 或 邮箱 20 位，仅允许数字/字母/@/.（不含其他标点） */
	function bindLoginAccount(el) {
		var reAllowed = /[^0-9a-zA-Z@.]/g;
		function filter() {
			var val = el.value.replace(reAllowed, '');
			if (val.indexOf('@') !== -1) {
				val = val.slice(0, 20);
			} else {
				if (/^\d+$/.test(val)) val = val.slice(0, 11);
				else val = val.slice(0, 20);
			}
			if (val !== el.value) el.value = val;
		}
		// 允许数字、字母、@、. 按键；具体按手机/邮箱规则与长度由 input 时 filter 处理
		el.addEventListener('keypress', function (e) {
			var ch = typeof e.key === 'string' ? e.key : String.fromCharCode(e.which || e.keyCode);
			if (ch.length !== 1) return;
			if (!/[0-9a-zA-Z@.]/.test(ch)) e.preventDefault();
		});
		el.addEventListener('input', filter);
		el.addEventListener('paste', function () { setTimeout(filter, 0); });
		el.addEventListener('change', filter);
		el.removeAttribute('maxlength');
		filter();
	}

	function init() {
		var loginForm = document.getElementById('LoginForm');
		var scope = loginForm || document;
		// 登录/注册/找回密码：账号统一逻辑（手机号 11 位 或 邮箱 20 位，仅允许数字/字母/@/.）
		var loginAccount = loginForm && loginForm.querySelector('input[name="username"]');
		if (loginAccount) bindLoginAccount(loginAccount);
		var registerAccount = document.getElementById('ipyanz');
		if (registerAccount) bindLoginAccount(registerAccount);
		var findPwdAccount = document.getElementById('userAccount');
		if (findPwdAccount) bindLoginAccount(findPwdAccount);
		// 其他页面的 name（账号）输入框：同规则
		var accountNames = document.querySelectorAll('.login_ConInput input[name="name"], .login_Content input[name="name"]');
		accountNames.forEach(function (el) {
			if (el.id === 'ipyanz' || el.id === 'userAccount') return;
			bindLoginAccount(el);
		});

		// 密码：仅数字、字母  maxlength 20
		['#LoginForm input[name="password"]', 'input[name="userpassword"]', 'input[name="userpassword2"]'].forEach(function (sel) {
			bindInputFilter(sel, '0-9a-zA-Z', 20);
		});

		// 验证码：仅数字、字母，6位
		['#LoginForm input[name="captcha"]', 'input[name="checkcode"]', 'input[name="code"]'].forEach(function (sel) {
			bindInputFilter(sel, '0-9a-zA-Z', 6);
		});

		// 绑定页手机号：仅数字，11位
		var mobileInput = document.querySelector('input[name="mobile"]');
		if (mobileInput) {
			mobileInput.setAttribute('maxlength', '11');
			mobileInput.addEventListener('input', function () {
				this.value = this.value.replace(/\D/g, '');
			});
			mobileInput.addEventListener('paste', function () { var t = this; setTimeout(function () { t.value = t.value.replace(/\D/g, ''); }, 0); });
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
