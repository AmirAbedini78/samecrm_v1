@extends('layout.wrapper')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-body">
					<h4 class="card-title mb-4">گزارش تجمیعی مبلغ فروش</h4>
					<div class="row g-3 align-items-end">
						<div class="col-md-3">
							<label class="form-label">از تاریخ</label>
                            <input type="text" id="document_date_from" class="form-control pickadate" autocomplete="off">
						</div>
						<div class="col-md-3">
							<label class="form-label">تا تاریخ</label>
                            <input type="text" id="document_date_to" class="form-control pickadate" autocomplete="off">
						</div>
						<div class="col-md-3">
							<label class="form-label">مشتری (یونیک)</label>
							<input type="text" id="column_customer_name" class="form-control" placeholder="نام مشتری">
						</div>
						<div class="col-md-3">
							<label class="form-label">انبار (یونیک)</label>
							<input type="text" id="column_warehouse" class="form-control" placeholder="انبار">
						</div>
					</div>
					<div class="text-end mt-3">
						<button id="run-aggregates" class="btn btn-primary"><i class="ti-stats-up"></i> محاسبه</button>
					</div>

					<hr>

					<div id="agg-results" class="row g-3">
						<div class="col-md-4">
							<div class="card bg-light">
								<div class="card-body">
									<p class="mb-1">تعداد رکورد: <span id="agg-count">0</span></p>
									<p class="mb-1">مجموع مبلغ فروش: <span id="agg-total">0</span></p>
									<p class="mb-0">میانگین مبلغ فروش: <span id="agg-avg">0</span></p>
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
$('#run-aggregates').on('click', function(){
	var data = {
		document_date_from: $('#document_date_from').val(),
		document_date_to: $('#document_date_to').val(),
		column_customer_name: $('#column_customer_name').val(),
		column_warehouse: $('#column_warehouse').val(),
	};
	$.post('/report/sales/aggregates/data', data).done(function(resp){
		if(resp.success){
			$('#agg-count').text(resp.data.count);
			$('#agg-total').text(fmt(resp.data.total_sales_amount));
			$('#agg-avg').text(fmt(resp.data.average_sales_amount));
		}
	}).fail(function(xhr){
		console.error(xhr.responseText);
	});
});
</script>
@endsection


