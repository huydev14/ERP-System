
/** USAGE EXAMPLE
 *
 * fluentToast({
    type: 'success',
    title: 'Email sent',
    description: 'This is a toast body',
    subtitle: 'Subtitle',
    actionType: 'link',
    actionText: 'Undo',
    onAction: function() {
        console.log('Test');
    },
    bottomActions: [
        {
            text: 'View details',
            onClick: function() { console.log('Mở trang chi tiết'); }
        },
        {
            text: 'Resend',
            onClick: function() { console.log('Gửi lại lần nữa'); }
        }
    ]
});
 */

window.fluentToast = function ({
    type = 'success',
    title,
    description = '',
    subtitle = '',
    actionType = 'close',
    actionText = '',
    onAction = null,
    bottomActions = [],
}) {
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-times-circle',
        info: 'fas fa-arrow-down',
        warning: 'fas fa-exclamation-triangle',
    };

    // Render top right button
    let actionHtml = '';
    if (actionType === 'link') {
        actionHtml = `<button class="action-btn link-btn">${actionText}</button>`;
    } else {
        actionHtml = `<button class="action-btn close-btn"><i class="fas fa-times"></i></button>`;
    }

    // Render description
    let extraTextHtml = '';
    if (description) {
        extraTextHtml += `<div class="toast-description">${description}</div>`;
    }

    // Render subtitle
    if (subtitle) {
        extraTextHtml += `<div class="toast-subtitle">${subtitle}</div>`;
    }

    // Render bottom actions
    let bottomActionsHtml = '';
    if (bottomActions && bottomActions.length > 0) {
        let buttonsHtml = bottomActions
            .map((btn, index) => `<button class="bottom-action-btn" data-index="${index}">${btn.text}</button>`)
            .join('');
        bottomActionsHtml = `<div class="toast-bottom-actions">${buttonsHtml}</div>`;
    }

    const toastHtml = $(`
        <div class="fluent-toast-card">
            <div class="toast-left">
                <i class="${icons[type]} toast-icon ${type}"></i>
                <div class="toast-text-container">
                    <span class="toast-title">${title}</span>
                    ${extraTextHtml}
                    ${bottomActionsHtml}
                </div>
            </div>
            <div class="toast-action">
                ${actionHtml}
            </div>
        </div>
    `);

    $('#fluent-toast-container').append(toastHtml);
    setTimeout(() => toastHtml.addClass('show'), 10);

    let displayTime = (description || bottomActions.length) > 0 ? 8000 : 4000;

    let autoClose = setTimeout(() => {
        removeToast(toastHtml);
    }, displayTime);

    toastHtml.find('.action-btn').on('click', function () {
        if (actionType === 'link' && typeof onAction === 'function') {
            onAction();
        }
        clearTimeout(autoClose);
        removeToast(toastHtml);
    });

    if (bottomActions && bottomActions.length > 0) {
        toastHtml.find('.bottom-action-btn').on('click', function () {
            let index = $(this).data('index');
            if (typeof bottomActions[index].onClick === 'function') {
                bottomActions[index].onClick();
            }
            clearTimeout(autoClose);
            removeToast(toastHtml);
        });
    }

    function removeToast($el) {
        $el.removeClass('show');
        setTimeout(() => $el.remove(), 300);
    }
};
