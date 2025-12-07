/**
 * Inventory Transactions Management JavaScript
 */

$(document).ready(function() {
    // Auto-calculate amount from quantity and unit price
    function calculateAmount() {
        var quantity = parseFloat($('input[name="quantity"]').val()) || 0;
        var unitPrice = parseFloat($('input[name="unit_price"]').val()) || 0;
        var amount = quantity * unitPrice;
        
        if (amount > 0) {
            $('input[name="amount"]').val(amount.toFixed(2));
        }
    }

    // Bind calculation on quantity and unit_price changes
    $(document).on('input', 'input[name="quantity"], input[name="unit_price"]', calculateAmount);

    // Delete transaction handler
    $(document).on('click', '.delete-transaction', function(e) {
        e.preventDefault();
        
        if (!confirm('آیا از حذف این تراکنش اطمینان دارید؟')) {
            return;
        }
        
        var transactionId = $(this).data('id');
        var row = $(this).closest('tr');
        var url = '/inventory/transactions/' + transactionId;
        
        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.success) {
                    row.fadeOut(300, function() {
                        $(this).remove();
                        // Check if table is empty
                        if ($('tbody tr').length === 0) {
                            $('tbody').append('<tr><td colspan="10" class="text-center">هیچ تراکنشی یافت نشد</td></tr>');
                        }
                    });
                    
                    // Show success message
                    if (typeof showNotification === 'function') {
                        showNotification('تراکنش با موفقیت حذف شد', 'success');
                    } else {
                        alert('تراکنش با موفقیت حذف شد');
                    }
                } else {
                    alert('خطا: ' + (response.message || 'خطای نامشخص'));
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                if (response && response.message) {
                    alert('خطا: ' + response.message);
                } else {
                    alert('خطا در حذف تراکنش');
                }
            }
        });
    });

    // Form submission handlers
    $('#transaction-create-form, #transaction-edit-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = form.serialize();
        var url = form.attr('action');
        var method = form.find('input[name="_method"]').val() || 'POST';
        
        // Disable submit button
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="ti-spinner"></i> در حال ذخیره...');
        
        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                if (response.success) {
                    if (typeof showNotification === 'function') {
                        showNotification(response.message || 'عملیات با موفقیت انجام شد', 'success');
                    } else {
                        alert(response.message || 'عملیات با موفقیت انجام شد');
                    }
                    
                    // Redirect after short delay
                    setTimeout(function() {
                        window.location.href = '/inventory/transactions';
                    }, 1000);
                } else {
                    alert('خطا: ' + (response.message || 'خطای نامشخص'));
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var errorMessage = 'خطا در انجام عملیات';
                
                if (response) {
                    if (response.message) {
                        errorMessage = response.message;
                    } else if (response.errors) {
                        var errors = [];
                        $.each(response.errors, function(key, value) {
                            errors.push(value[0]);
                        });
                        errorMessage = errors.join('\n');
                    }
                }
                
                alert(errorMessage);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Import form handler
    $('#transaction-import-form').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = new FormData(this);
        var url = form.attr('action');
        var fileInput = form.find('input[type="file"]')[0];
        
        if (fileInput.files.length === 0) {
            alert('لطفا فایلی انتخاب کنید');
            return;
        }
        
        // Disable submit button
        var submitBtn = form.find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="ti-spinner"></i> در حال آپلود...');
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    var message = 'وارد کردن موفق!\n';
                    message += 'وارد شده: ' + response.imported + '\n';
                    message += 'رد شده: ' + response.skipped;
                    
                    if (typeof showNotification === 'function') {
                        showNotification(message, 'success');
                    } else {
                        alert(message);
                    }
                    
                    // Redirect after short delay
                    setTimeout(function() {
                        window.location.href = '/inventory/transactions';
                    }, 1500);
                } else {
                    alert('خطا: ' + (response.message || 'خطای نامشخص'));
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                var response = xhr.responseJSON;
                var errorMessage = 'خطا در وارد کردن فایل';
                
                if (response && response.message) {
                    errorMessage = response.message;
                }
                
                alert(errorMessage);
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Filter form handler
    $('#transaction-filter-form').on('submit', function(e) {
        // Allow normal form submission for filters
        // Can be enhanced with AJAX if needed
    });

    // Initialize date pickers if using a date picker library
    if (typeof $().datepicker !== 'undefined') {
        $('input[type="date"]').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    }
});

