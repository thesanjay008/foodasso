@section('filter')
	<div class="modal fade" id="walletModal" tabindex="-1" role="dialog" aria-labelledby="walletModalTitle" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="walletModalTitle">Update Wallet</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-9">
							<div class="input-group">
								<input id="title" placeholder="Enter Title" type="text" class="form-control">
							</div>
						</div>
						
						<div class="col-md-6">
							<br/>
							<div class="input-group">
								<input id="amount" placeholder="Enter amount" type="text" class="form-control">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<br>
							<h5 class="card-title">Transaction Type</h5>
							<div class="position-relative form-group">
								<div>
									<div class="custom-radio custom-control">
										<input type="radio" id="deposit" value="Deposit" name="typeRadio" class="custom-control-input" checked>
										<label class="custom-control-label" for="deposit">Credit</label>
									</div>
									<div class="custom-radio custom-control">
										<input type="radio" id="withdraw" value="Withdraw" name="typeRadio" class="custom-control-input">
										<label class="custom-control-label" for="withdraw">Debit</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary filter-btn" onclick="updateWallet();">Apply</button>
				</div>
			</div>
		</div>
	</div>
@endsection

	<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
		<li class="nav-item">
			<a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-0" aria-selected="true">
				<span>Wallet</span>
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
			<div class="row">
				<div class="col-lg-4">
					<div class="main-card mb-3 card wallet-card">
						<div class="card-body">
							<a href="javascript:void(0);" data-toggle="modal" data-target="#walletModal"><span class="badge badge-pill badge-danger">+</span></a>
							<h5 class="card-title">Balance</h5>
							<div class="dropdown d-inline-block">
								<h2>{{$wallet->balance}}</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="main-card mb-3 card">
						<div class="card-header">WALLET HISTORY
							<div class="btn-actions-pane-right">
								<!--<div role="group" class="btn-group-sm btn-group">
									<button class="active btn btn-focus">Last Week</button>
									<button class="btn btn-focus">All Month</button>
								</div>-->
								<!--<div role="group" class="btn-group-sm btn-group">
								<input id="start_date" type="date" class="form-control" placeholder="Start Date">
								</div>
								<div role="group" class="btn-group-sm btn-group">
								<input id="end_date" type="date" class="form-control" placeholder="End Date">
								</div>
								<a class="btn btn-secondary btn-wide btn-outline-2x mr-md-2 btn" href="javascript:void(0)" onclick="getData();"> Submit</a>-->
							</div>
						</div>
						<div class="table-responsive">
							<table class="align-middle mb-0 table table-borderless table-striped table-hover">
								<thead>
									<tr>
										<th>Date</th>
										<th>Title</th>
										<th class="text-center">Type</th>
										<th class="text-center">Status</th>
										<th class="text-center">Amount</th>
									</tr>
								</thead>
								<tbody>
									@if($wallet->history)
									@foreach($wallet->history as $list)
									<tr>
										<td class="text-muted">{{$list->created_at}}</td>
										<td>
											<div class="widget-content p-0">
												<div class="widget-content-wrapper">
													<div class="widget-content-left flex2">
														<div class="widget-heading">{{$list->title}}</div>
													</div>
												</div>
											</div>
										</td>
										<td class="text-center">{{$list->type}}</td>
										<td class="text-center">
											<div class="badge badge-success">{{$list->status}}</div>
										</td>
										<td class="text-center">{{$list->amount}}</td>
									</tr>
									@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>