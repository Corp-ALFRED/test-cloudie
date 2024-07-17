window.addEventListener('DOMContentLoaded', function () {
    // タッチデバイスの場合はhoverクラスを削除
    if ('ontouchstart' in window) {
        var elements = document.querySelectorAll('.hoverable');
        elements.forEach(function (element) {
            element.classList.remove('hoverable');
        });
    }
});