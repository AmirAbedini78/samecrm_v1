/*
 Lightweight column resize for plain HTML tables.
 - Adds a small draggable handle at the right edge of each <th>
 - Dragging resizes that column (and optionally the next one) while keeping table width
 - Keeps default styling; no visual theming
*/

(function () {
    function initTableColumnResize(tableSelector) {
        var $table = $(tableSelector);
        if (!$table.length) return;

        // Ensure predictable layout for width manipulation
        $table.css({
            tableLayout: 'fixed',
            width: '100%'
        });

        var $headers = $table.find('thead th');
        if (!$headers.length) return;

        $headers.css('position', 'relative');

        $headers.each(function (idx) {
            var $th = $(this);

            // Skip checkbox column if present
            if ($th.hasClass('list-checkbox-wrapper')) return;
            // Skip last column (actions) from resizing to keep fixed width
            if (idx === $headers.length - 1) return;

            // Add handle only once
            if ($th.find('.col-resize-handle').length) return;

            var $handle = $('<span class="col-resize-handle"></span>');
            $handle.css({
                position: 'absolute',
                top: 0,
                right: 0,
                width: '6px',
                height: '100%',
                cursor: 'col-resize',
                zIndex: 5
            });

            $th.append($handle);

            var startX, startWidth, $nextTh, nextStartWidth, isDragging = false;

            $handle.on('mousedown', function (e) {
                e.preventDefault();
                e.stopPropagation();

                isDragging = true;
                startX = e.pageX;
                startWidth = $th.outerWidth();

                $('body').addClass('noselect');
                $(document).on('mousemove.colresize', onMouseMove);
                $(document).on('mouseup.colresize', onMouseUp);
            });

            function onMouseMove(e) {
                if (!isDragging) return;
                var dx = e.pageX - startX;

                var newWidth = Math.max(100, startWidth + dx); // minimum width
                $th.css('width', newWidth + 'px');
            }

            function onMouseUp() {
                isDragging = false;
                $('body').removeClass('noselect');
                $(document).off('mousemove.colresize mouseup.colresize');
            }
        });
    }

    // Init on ready and after ajax table refreshes
    $(document).ready(function () {
        initTableColumnResize('#inventory-list-table');
        initTableColumnResize('#sales-list-table');
    });

    // When table DOM is replaced via AJAX (sorting/search), re-init
    $(document).on('ajaxComplete', function () {
        initTableColumnResize('#inventory-list-table');
        initTableColumnResize('#sales-list-table');
    });
})();


