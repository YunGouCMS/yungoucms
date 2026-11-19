// 表单防重复提交
(function() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        let isSubmitting = false;
        form.addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }
            isSubmitting = true;
            setTimeout(() => {
                isSubmitting = false;
            }, 3000);
        });
    });
})();

