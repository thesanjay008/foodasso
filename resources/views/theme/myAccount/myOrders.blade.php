						<h4><i class="icon-food_icon_cloche"></i> Orders</h4>
						<div id="myOrderList" class="row"></div>
@section('js')
	<script>
		$(document).ready(function(e) {
			myOrderList();
		});
	</script>
@endsection