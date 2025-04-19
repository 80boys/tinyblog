// 图片拖拽监听器
function dragMoveListener(event) {
    event.stopPropagation();
    var target = event.target;
    var scale = parseFloat(target.style.transform.split('scale(')[1]);
    if (isNaN(scale)) {
        scale = 1;
    }
    var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
    var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;
    target.style.transform = 'translate(' + x + 'px, ' + y + 'px) scale(' + scale + ')';

    target.setAttribute('data-x', x);
    target.setAttribute('data-y', y);
}

// 初始化图片查看功能
document.addEventListener('DOMContentLoaded', function() {
    // 为所有大图添加点击和双击事件
    const images = document.querySelectorAll('img');
    images.forEach(image => {
        image.addEventListener('click', function(event) {
            event.stopPropagation();
            const zoomImage = document.createElement('div');
            zoomImage.classList.add('zoom-image');
            const zoomImg = document.createElement('img');
            zoomImg.src = this.src;
            zoomImage.appendChild(zoomImg);
            zoomImg.style.transform = 'scale(1)';
            document.body.appendChild(zoomImage);

            // 添加拖拽功能
            interact(zoomImg).draggable({
                inertia: true,
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: 'parent',
                        endOnly: true
                    })
                ],
                autoScroll: true,
                listeners: {
                    move: dragMoveListener,
                    end(event) {
                        event.stopPropagation();
                    }
                }
            });

            // 点击关闭图片查看
            zoomImg.addEventListener('click', function(event) {
                event.stopPropagation();
                document.body.removeChild(zoomImage);
            });
        });
    });
}); 