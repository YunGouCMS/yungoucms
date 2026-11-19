/**
 * 电脑端骨架屏：页面就绪后隐藏骨架、展示真实内容
 */
(function () {
  function hideSkeleton() {
    var el = document.getElementById('page-skeleton');
    if (el) {
      el.classList.add('skeleton-hidden');
      setTimeout(function () {
        el.style.display = 'none';
      }, 280);
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', hideSkeleton);
  } else {
    hideSkeleton();
  }
  window.addEventListener('load', hideSkeleton);
})();
