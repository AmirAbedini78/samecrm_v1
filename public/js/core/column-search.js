/**
 * Column-specific search functionality - SIMPLIFIED VERSION
 * Test with only one search input
 */

$(document).ready(function() {
    
    // Column search functionality loaded
    
    // Handle column search inputs
    $(document).on('input', '.column-search-input', function() {
        var $input = $(this);
        var column = $input.data('column');
        var searchValue = $input.val();
        var searchUrl = $input.data('url');
        
        console.log('Input event:', column, searchValue);
        
        // Clear previous timeout
        clearTimeout($input.data('search-timeout'));
        
        // Search for any input (live search)
        $input.data('search-timeout', setTimeout(function() {
            console.log('Performing search:', column, searchValue);
            performColumnSearch($input);
        }, 1000));
    });
    
    // Handle enter key
    $(document).on('keypress', '.column-search-input', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            // Enter key pressed
            clearTimeout($(this).data('search-timeout'));
            performColumnSearch($(this));
        }
    });
    
    function performColumnSearch($input) {
        var column = $input.data('column');
        var searchValue = $input.val();
        var searchUrl = $input.data('url');
        
        console.log('Performing search for:', column, searchValue);
        
        // Build search parameters
        var searchParams = {
            action: 'search'
        };
        
        // Always include the search parameter, even if empty
        searchParams['column_search_' + column] = searchValue || '';
        
        // Build URL with parameters
        var url = searchUrl + '?' + $.param(searchParams);
        
        console.log('Search URL:', url);
        
        // Perform AJAX request
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $input.addClass('searching');
                $input.prop('disabled', true);
                $input.attr('placeholder', 'در حال جستجو...');
            },
            success: function(response) {
                console.log('Search response:', response);
                
                // Store current input values before updating DOM
                var currentInputValues = {};
                $('.column-search-input').each(function() {
                    var $input = $(this);
                    currentInputValues[$input.data('column')] = $input.val();
                });
                
                // Update table content
                if (response.dom_html) {
                    response.dom_html.forEach(function(update) {
                        var $target = $(update.selector);
                        if ($target.length) {
                            switch (update.action) {
                                case 'replace':
                                    $target.html(update.value);
                                    break;
                                case 'replace-with':
                                    $target.replaceWith(update.value);
                                    break;
                                case 'append':
                                    $target.append(update.value);
                                    break;
                            }
                        }
                    });
                }
                
                // Update stats if available
                if (response.stats) {
                    console.log('Stats received:', response.stats);
                    updateStats(response.stats);
                } else {
                    console.log('No stats in response');
                    console.log('Full response:', response);
                }
                
                // Restore input values after DOM update
                setTimeout(function() {
                    $('.column-search-input').each(function() {
                        var $input = $(this);
                        var column = $input.data('column');
                        if (currentInputValues[column] !== undefined) {
                            $input.val(currentInputValues[column]);
                        }
                    });
                }, 100);
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error, xhr.responseText);
            },
            complete: function() {
                $input.removeClass('searching');
                $input.prop('disabled', false);
                $input.attr('placeholder', 'جستجو در ' + column + '...');
            }
        });
    }
    
    // Clear search
    $(document).on('click', '.clear-column-searches', function(e) {
        e.preventDefault();
        // Clear button clicked
        $('.column-search-input').val('');
        $('.column-search-input').each(function() {
            clearTimeout($(this).data('search-timeout'));
            performColumnSearch($(this));
        });
    });
    
    // Function to update stats
    function updateStats(stats) {
        console.log('Updating stats with:', stats);
        
        // Update total sales amount
        if (stats.total_sales_amount !== undefined && stats.total_sales_amount !== null) {
            var $totalSalesAmount = $('.stats-total-sales-amount');
            if ($totalSalesAmount.length) {
                $totalSalesAmount.text(formatCurrency(stats.total_sales_amount, 'IRR'));
                console.log('Updated total sales amount:', stats.total_sales_amount);
            } else {
                console.log('Total sales amount element not found');
            }
        }
        
        // Update average sales amount
        if (stats.average_sales_amount !== undefined && stats.average_sales_amount !== null) {
            var $averageSalesAmount = $('.stats-average-sales-amount');
            if ($averageSalesAmount.length) {
                $averageSalesAmount.text(formatCurrency(stats.average_sales_amount, 'IRR'));
                console.log('Updated average sales amount:', stats.average_sales_amount);
            } else {
                console.log('Average sales amount element not found');
            }
        }
        
        // Total revenue box removed - no need to update
    }
    
    // Function to format currency
    function formatCurrency(amount, currency) {
        if (currency === 'IRR') {
            // Simple formatting for Persian numbers
            var num = parseFloat(amount) || 0;
            var formatted = num.toLocaleString('fa-IR');
            return formatted + ' تومان';
        }
        return amount;
    }
    
    // Column filter dropdown functionality
    $(document).on('click', '.column-filter-dropdown', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $dropdown = $(this);
        var column = $dropdown.data('column');
        
        // Close other dropdowns
        $('.column-filter-menu').removeClass('show');
        $('.column-filter-dropdown').removeClass('active');
        
        // Toggle current dropdown
        if ($dropdown.hasClass('active')) {
            $dropdown.removeClass('active');
            return;
        }
        
        $dropdown.addClass('active');
        
        // Check if menu already exists
        var $existingMenu = $dropdown.siblings('.column-filter-menu');
        if ($existingMenu.length > 0) {
            $existingMenu.addClass('show');
            return;
        }
        
        // Create and show loading menu
        var $menu = $('<div class="column-filter-menu"><div class="column-filter-item">در حال بارگذاری...</div></div>');
        $dropdown.after($menu);
        $menu.addClass('show');
        
        // Load unique values for this column
        loadColumnUniqueValues(column, $menu);
    });
    
    // Close dropdowns when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.column-filter-dropdown, .column-filter-menu').length) {
            $('.column-filter-menu').removeClass('show');
            $('.column-filter-dropdown').removeClass('active');
        }
    });
    
    // Handle filter item clicks
    $(document).on('click', '.column-filter-item', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $item = $(this);
        var value = $item.data('value');
        var column = $item.data('column');
        
        if (value === 'clear') {
            // Clear filter for this column
            clearColumnFilter(column);
        } else {
            // Apply filter for this column
            applyColumnFilter(column, value);
        }
        
        // Close dropdown
        $('.column-filter-menu').removeClass('show');
        $('.column-filter-dropdown').removeClass('active');
    });
    
    function loadColumnUniqueValues(column, $menu) {
        var url = window.location.pathname + '?action=unique_values&column=' + column;
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && response.data) {
                    var html = '';
                    
                    // Add clear option
                    html += '<div class="column-filter-item column-filter-clear" data-value="clear" data-column="' + column + '">پاک کردن فیلتر</div>';
                    
                            // Add unique values
                            response.data.forEach(function(value) {
                                if (value !== null && value !== '') {
                                    html += '<div class="column-filter-item" data-value="' + encodeURIComponent(value) + '" data-column="' + column + '">' + decodeURIComponent(value) + '</div>';
                                }
                            });
                    
                    $menu.html(html);
                } else {
                    $menu.html('<div class="column-filter-item">خطا در بارگذاری داده‌ها</div>');
                }
            },
            error: function() {
                $menu.html('<div class="column-filter-item">خطا در بارگذاری داده‌ها</div>');
            }
        });
    }
    
            function applyColumnFilter(column, value) {
                console.log('Applying filter:', column, value);
                // Add filter to search inputs
                var $searchInput = $('.column-search-input[data-column="' + column + '"]');
                if ($searchInput.length) {
                    $searchInput.val(decodeURIComponent(value));
                    performColumnSearch($searchInput);
                }
            }
    
    function clearColumnFilter(column) {
        // Clear filter from search inputs
        var $searchInput = $('.column-search-input[data-column="' + column + '"]');
        if ($searchInput.length) {
            $searchInput.val('');
            performColumnSearch($searchInput);
        }
    }
    
});

// Add CSS for search inputs
$('<style>')
    .prop('type', 'text/css')
    .html(`
        .column-search-input {
            width: 100%;
            min-width: 80px;
            pointer-events: auto !important;
            z-index: 10;
            position: relative;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        .column-search-row th {
            padding: 5px;
            vertical-align: middle;
            position: relative;
            z-index: 1;
        }
        .column-search-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .column-search-input.searching {
            background-color: #f8f9fa;
            border-color: #007bff;
        }
        
        /* Ensure dropdown menus have higher z-index */
        .dropdown-menu {
            z-index: 1050 !important;
        }
        
        /* Table config dropdown specifically */
        .table-config-dropdown {
            z-index: 1051 !important;
        }
        
        /* Column filter dropdown styles */
        .column-header-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .column-filter-dropdown {
            cursor: pointer;
            margin-left: 5px;
            padding: 2px 4px;
            border-radius: 3px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
            transition: all 0.2s ease;
        }
        
        .column-filter-dropdown:hover {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .column-filter-dropdown.active {
            background-color: #007bff;
            color: white;
        }
        
        .column-filter-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            display: none;
        }
        
        .column-filter-menu.show {
            display: block;
        }
        
        .column-filter-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f8f9fa;
            font-size: 13px;
            transition: background-color 0.2s ease;
        }
        
        .column-filter-item:hover {
            background-color: #f8f9fa;
        }
        
        .column-filter-item:last-child {
            border-bottom: none;
        }
        
        .column-filter-clear {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
        }
        
        .column-filter-clear:hover {
            background-color: #c82333;
        }
    `)
    .appendTo('head');