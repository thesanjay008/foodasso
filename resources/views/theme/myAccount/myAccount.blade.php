	<h4><i class="icon-food_icon_dish"></i> Dashboard</h4>
	<div class="row">
		<div class="col-md-12">
			<br>
			<h5>Open Orders</h5>
		</div>
		<div id="myOrderList" class="row"></div>
	</div>
@section('js')
	<script>
		$(document).ready(function(e) {
			myOrderList('open');
		});
	</script>
@endsection