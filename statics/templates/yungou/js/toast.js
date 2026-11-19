/**
 * 电脑端右上角消息提示，替代 alert 弹窗
 * 用法：toast('提示内容'); toast('成功', 'success'); toast('失败', 'error');
 */
(function () {
	var DURATION = 2800;
	var container = null;

	function getContainer() {
		if (container && document.body.contains(container)) return container;
		container = document.getElementById('pc-toast-container');
		if (!container) {
			container = document.createElement('div');
			container.id = 'pc-toast-container';
			document.body.appendChild(container);
		}
		return container;
	}

	function showToast(msg, type) {
		msg = msg == null ? '' : String(msg).trim();
		if (!msg) return;
		type = (type === 'success' || type === 'error') ? type : 'info';

		var el = document.createElement('div');
		el.className = 'pc-toast pc-toast-' + type;
		el.innerHTML = '<span class="pc-toast-icon"></span><span class="pc-toast-msg"></span>';
		el.querySelector('.pc-toast-msg').textContent = msg;

		var box = getContainer();
		box.appendChild(el);

		var t = setTimeout(function () {
			el.classList.add('pc-toast-out');
			setTimeout(function () {
				if (el.parentNode) el.parentNode.removeChild(el);
			}, 260);
		}, DURATION);
		el._timer = t;
	}

	window.toast = showToast;
})();
