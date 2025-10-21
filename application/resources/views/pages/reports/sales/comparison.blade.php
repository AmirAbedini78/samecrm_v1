@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mb-4">گزارش مقایسه فروش بر اساس تاریخ سند</h4>
                    <div class="row g-3 align-items-end">
						<div class="col-md-3">
							<label class="form-label">از تاریخ (بازه 1)</label>
                            <input type="text" id="range1_from" class="form-control pickadate" autocomplete="off">
						</div>
						<div class="col-md-3">
							<label class="form-label">تا تاریخ (بازه 1)</label>
                            <input type="text" id="range1_to" class="form-control pickadate" autocomplete="off">
						</div>
						<div class="col-md-3">
							<label class="form-label">از تاریخ (بازه 2)</label>
                            <input type="text" id="range2_from" class="form-control pickadate" autocomplete="off">
						</div>
						<div class="col-md-3">
							<label class="form-label">تا تاریخ (بازه 2)</label>
                            <input type="text" id="range2_to" class="form-control pickadate" autocomplete="off">
						</div>
					</div>
					<div class="text-end mt-3">
						<button id="run-comparison" class="btn btn-primary"><i class="ti-bar-chart"></i> اجرا</button>
					</div>

					<hr>

                <div id="comparison-results" class="row g-3">
						<div class="col-md-6">
							<div class="card bg-light">
								<div class="card-body">
									<h5 class="mb-3">بازه 1</h5>
									<p class="mb-1">تعداد رکورد: <span id="r1-count">0</span></p>
									<p class="mb-1">مجموع مبلغ فروش: <span id="r1-total">0</span></p>
									<p class="mb-0">میانگین مبلغ فروش: <span id="r1-avg">0</span></p>
								</div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="mb-2">جدول بازه 1</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="tbl-range1">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>تاریخ سند</th>
                                        <th>مشتری</th>
                                        <th>محصول</th>
                                        <th>مقدار</th>
                                        <th>مبلغ فروش</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2">جدول بازه 2</h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="tbl-range2">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>تاریخ سند</th>
                                        <th>مشتری</th>
                                        <th>محصول</th>
                                        <th>مقدار</th>
                                        <th>مبلغ فروش</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card bg-light">
								<div class="card-body">
									<h5 class="mb-3">بازه 2</h5>
									<p class="mb-1">تعداد رکورد: <span id="r2-count">0</span></p>
									<p class="mb-1">مجموع مبلغ فروش: <span id="r2-total">0</span></p>
									<p class="mb-0">میانگین مبلغ فروش: <span id="r2-avg">0</span></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function fmt(amount){
	try { return new Intl.NumberFormat('fa-IR').format(parseFloat(amount||0)); } catch(e){ return amount; }
}
$('#run-comparison').on('click', function(){
	var data = {
		range1_from: $('#range1_from').val(),
		range1_to: $('#range1_to').val(),
		range2_from: $('#range2_from').val(),
		range2_to: $('#range2_to').val(),
		_action: 'comparison'
	};
	$.post('/report/sales/comparison/data', data).done(function(resp){
		if(resp.success){
			$('#r1-count').text(resp.data.range1.count);
			$('#r1-total').text(fmt(resp.data.range1.total_sales_amount));
			$('#r1-avg').text(fmt(resp.data.range1.average_sales_amount));
			$('#r2-count').text(resp.data.range2.count);
			$('#r2-total').text(fmt(resp.data.range2.total_sales_amount));
			$('#r2-avg').text(fmt(resp.data.range2.average_sales_amount));

        // fill tables
        var tbody1 = $('#tbl-range1 tbody');
        var tbody2 = $('#tbl-range2 tbody');
        tbody1.empty(); tbody2.empty();
        (resp.data.range1.rows || []).forEach(function(r){
            tbody1.append('<tr>'+
                '<td>'+r.sales_id+'</td>'+
                '<td>'+r.document_date+'</td>'+
                '<td>'+(r.customer_name||'')+'</td>'+
                '<td>'+(r.product_name||'')+'</td>'+
                '<td>'+(r.main_quantity||0)+'</td>'+
                '<td>'+fmt(r.base_sales_amount||0)+'</td>'+
            '</tr>');
        });
        (resp.data.range2.rows || []).forEach(function(r){
            tbody2.append('<tr>'+
                '<td>'+r.sales_id+'</td>'+
                '<td>'+r.document_date+'</td>'+
                '<td>'+(r.customer_name||'')+'</td>'+
                '<td>'+(r.product_name||'')+'</td>'+
                '<td>'+(r.main_quantity||0)+'</td>'+
                '<td>'+fmt(r.base_sales_amount||0)+'</td>'+
            '</tr>');
        });
		}
	}).fail(function(xhr){
		console.error(xhr.responseText);
	});
});
</script>
@endsection


