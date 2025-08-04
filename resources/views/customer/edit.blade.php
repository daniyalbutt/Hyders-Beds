@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Customers</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Customers</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Customer - {{ $data->name }}</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('customer')
			<a href="{{ route('customers.index') }}" class="btn btn-primary">Customer List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>


<div class="card h-100">
	<div class="card-body">
		<h5 class="card-title">Edit Customer Form - {{ $data->name }}</h5>

		<ul class="nav nav-tabs mb-4 custom-nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="details-tab" data-toggle="tab" data-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
			</li>
			<li class="nav-item d-none" role="presentation">
				<button class="nav-link" id="customer_sale-tab" data-toggle="tab" data-target="#customer_sale" type="button" role="tab" aria-controls="customer_sale" aria-selected="false">Customer Sale / Trade Partnership</button>
			</li>
			<li class="nav-item d-none" role="presentation">
				<button class="nav-link" id="limited_plc-tab" data-toggle="tab" data-target="#limited_plc" type="button" role="tab" aria-controls="limited_plc" aria-selected="false">Limited P.L.C</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="credit_request-tab" data-toggle="tab" data-target="#credit_request" type="button" role="tab" aria-controls="credit_request" aria-selected="false">Credit Request</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="trade_reference-tab" data-toggle="tab" data-target="#trade_reference" type="button" role="tab" aria-controls="trade_reference" aria-selected="false">Trade Reference</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="bank_details-tab" data-toggle="tab" data-target="#bank_details" type="button" role="tab" aria-controls="bank_details" aria-selected="false">Bank Details</button>
			</li>
		</ul>
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
				<div class="basic-form">
					<form class="form ajax-form" method="post" action="{{ route('customers.update', $data->id) }}">
						<input type="hidden" name="step" value="details">
						@csrf
						@method('PUT')
						<div class="box-body">
							@if($errors->any())
								{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
							@endif
							@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
							@endif
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Name</label>
										<input type="text" class="form-control" name="name" value="{{ old('name', $data->name) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">E-mail</label>
										<input type="email" class="form-control" name="email" value="{{ old('email', $data->email) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-3">
										<label class="form-label">Telephone <strong>*</strong></label>
										<input type="text" class="form-control" name="telephone" value="{{ old('telephone', $data->telephone) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-3">
										<label class="form-label">Address <strong>*</strong></label>
										<input type="text" class="form-control" name="address" value="{{ old('address', $data->address) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-3">
										<label class="form-label">City <strong>*</strong></label>
										<input type="text" class="form-control" name="city" value="{{ old('city', $data->city) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-3">
										<label class="form-label">Country <strong>*</strong></label>
										<input type="text" class="form-control" name="country" value="{{ old('country', $data->country) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-3">
										<label class="form-label">Postcode <strong>*</strong></label>
										<input type="text" class="form-control" name="postcode" value="{{ old('postcode', $data->postcode) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group mb-3">
										<label class="form-label">Organizational Structure <strong>*</strong></label>
										<select name="organizational_structure" id="organizational_structure" class="form-control" required>
											<option value="">Select Option</option>
											<option value="Sale Trader" {{ $data->organizational_structure == "Sale Trader" ? 'selected' : '' }}>Sale Trader</option>
											<option value="Partnership" {{ $data->organizational_structure == "Partnership" ? 'selected' : '' }}>Partnership</option>
											<option value="Limited Company" {{ $data->organizational_structure == "Limited Company" ? 'selected' : '' }}>Limited Company</option>
											<option value="P.L.C" {{ $data->organizational_structure == "P.L.C" ? 'selected' : '' }}>P.L.C</option>
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group mb-3">
										<label class="form-label">Sales Person</label>
										<select name="sales_person[]" id="sales_person" class="form-control select2-multiple" multiple="multiple" data-width="100%">
											@foreach($sales_person as $key => $value)
											<option value="{{ $value->id }}" {{ in_array($value->id, $data->sales->pluck('sale_id')->toArray()) ? 'selected' : '' }}>{{ $value->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group mb-3">
										<label class="form-label">Description</label>
										<textarea name="description" id="description" class="form-control">{{ old('description', $data->description) }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer mt-3">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
			<div class="tab-pane fade" id="customer_sale" role="tabpanel" aria-labelledby="customer_sale-tab">
				<div class="basic-form">
					<form class="form" method="post" action="{{ route('customers.update', $data->id) }}">
						<input type="hidden" name="step" value="update_partner">
						@csrf
						@method('PUT')
						<div class="box-body">
							@if($errors->any())
								{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
							@endif
							@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
							@endif
							@foreach($data->partners as $key => $value)
							<div class="row">
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Name</label>
										<input type="text" value="{{ $value->name }}" class="form-control" name="name[{{$value->id}}]" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Address</label>
										<input type="text" value="{{ $value->address }}" class="form-control" name="address[{{$value->id}}]" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Telephone</label>
										<input type="text" value="{{ $value->telephone }}" class="form-control" name="telephone[{{$value->id}}]" required>
									</div>
								</div>
								<div class="col-md-1 mt-1">
									<button class="btn btn-danger btn-sm delete-data mt-4" data-url="{{ route('customers.destroy', $value->id) }}" data-table="customer_partnerships">DELETE</button>
								</div>
							</div>
							@endforeach
						</div>
						<!-- /.box-body -->
						<div class="box-footer mt-3">
						    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-backdrop="static" data-target="#customerSaleModal">Add New</button>
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
			<div class="tab-pane fade" id="limited_plc" role="tabpanel" aria-labelledby="limited_plc-tab">
				<div class="basic-form">
					<form class="form ajax-form" method="post" action="{{ route('customers.update', $data->id) }}">
						<input type="hidden" name="step" value="limited_plc">
						@csrf
						@method('PUT')
						<div class="box-body">
							@if($errors->any())
								{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
							@endif
							@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
							@endif
							<div class="row">
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Director Official Name <strong>*</strong></label>
										<input type="text" class="form-control" value="{{ $data->limited != null ? $data->limited->director_official_name : ''}}" name="director_official_name" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Position <strong>*</strong></label>
										<input type="text" class="form-control" name="position" value="{{ $data->limited != null ? $data->limited->position : '' }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">TelepContact Name for Paymenthone <strong>*</strong></label>
										<input type="text" class="form-control" name="telepcontact_name_for_paymenthone" value="{{ $data->limited != null ? $data->limited->telepcontact_name_for_paymenthone : ''}}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Account Phone No <strong>*</strong></label>
										<input type="text" class="form-control" name="account_phone_no" value="{{ $data->limited != null ? $data->limited->account_phone_no : ''}}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Account Email <strong>*</strong></label>
										<input type="text" class="form-control" name="account_email" value="{{ $data->limited != null ? $data->limited->account_email : '' }}" required>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer mt-3">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
			<div class="tab-pane fade" id="credit_request" role="tabpanel" aria-labelledby="credit_request-tab">
				<div class="basic-form">
					<form class="form ajax-form" method="post" action="{{ route('customers.update', $data->id) }}">
						<input type="hidden" name="step" value="credit_request">
						@csrf
						@method('PUT')
						<div class="box-body">
							@if($errors->any())
								{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
							@endif
							@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
							@endif
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Estimated Monthly Business</label>
										<input type="text" class="form-control" name="estimated_monthly_business" value="{{ old('estimated_monthly_business', $data->estimated_monthly_business) }}" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Credit Limit Requested</label>
										<input type="text" class="form-control" name="credit_limit_requested" value="{{ old('credit_limit_requested', $data->credit_limit_requested) }}" required>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer mt-3">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
			<div class="tab-pane fade" id="trade_reference" role="tabpanel" aria-labelledby="trade_reference-tab">
				<div class="basic-form">
					<form class="form ajax-form" method="post" action="{{ route('customers.update', $data->id) }}">
						<input type="hidden" name="step" value="update_trade">
						@csrf
						@method('PUT')
						<div class="box-body">
							@if($errors->any())
								{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
							@endif
							@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
							@endif
							@foreach($data->trades as $key => $value)
							<div class="row">
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Company Name <strong>*</strong></label>
										<input type="text" class="form-control" name="company_name[{{$value->id}}]" value="{{ $value->company_name }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Address <strong>*</strong></label>
										<input type="text" class="form-control" name="address[{{$value->id}}]" value="{{ $value->address }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Telephone <strong>*</strong></label>
										<input type="text" class="form-control" name="telephone[{{$value->id}}]" value="{{ $value->telephone }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Fax / Email <strong>*</strong></label>
										<input type="text" class="form-control" name="email[{{$value->id}}]" value="{{ $value->email }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Contact No <strong>*</strong></label>
										<input type="text" class="form-control" name="contact_no[{{$value->id}}]" value="{{ $value->contact_no }}" required>
									</div>
								</div>
								<div class="col-md-1 mt-1">
									<button class="btn btn-danger btn-sm delete-data mt-4" data-url="{{ route('customers.destroy', $value->id) }}" data-table="customer_trades">DELETE</button>
								</div>
							</div>
							@endforeach
						</div>
						<!-- /.box-body -->
						<div class="box-footer mt-3">
							<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-backdrop="static" data-target="#tradeReferenceModal">Add New</button>
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
			<div class="tab-pane fade" id="bank_details" role="tabpanel" aria-labelledby="bank_details-tab">
				<div class="basic-form">
					<form class="form ajax-form" method="post" action="{{ route('customers.update', $data->id) }}">
						<input type="hidden" name="step" value="add_bank">
						@csrf
						@method('PUT')
						<div class="box-body">
							@if($errors->any())
								{!! implode('', $errors->all('<div class="alert alert-danger">:message</div>')) !!}
							@endif
							@if(session()->has('success'))
								<div class="alert alert-success">
									{{ session()->get('success') }}
								</div>
							@endif
							<div class="row">
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Bank <strong>*</strong></label>
										<input type="text" class="form-control" name="bank" value="{{ $data->bank != null ? $data->bank->bank : '' }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Bank Address <strong>*</strong></label>
										<input type="text" class="form-control" name="bank_address" value="{{ $data->bank != null ? $data->bank->bank_address : '' }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Name of Account <strong>*</strong></label>
										<input type="text" class="form-control" name="name_of_account" value="{{ $data->bank != null ? $data->bank->name_of_account : '' }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Account Number <strong>*</strong></label>
										<input type="text" class="form-control" name="account_number" value="{{ $data->bank != null ? $data->bank->account_number : '' }}" required>
									</div>
								</div>
								<div class="col-md">
									<div class="form-group">
										<label class="form-label">Sort Code <strong>*</strong></label>
										<input type="text" class="form-control" name="sort_code" value="{{ $data->bank != null ? $data->bank->sort_code : '' }}" required>
									</div>
								</div>
							</div>
						</div>
						<!-- /.box-body -->
						<div class="box-footer mt-3">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade modal-right" id="customerSaleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<form class="ajax-form" action="{{ route('customers.update', $data->id) }}" method="post">
				<input type="hidden" name="step" value="new_customer_sale" data-form="update_partner">
				@csrf
				@method('PUT')
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add Customer Sale / Trade Partnership</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
                    <div class="form-group">
						<label>Name <strong>*</strong></label>
						<input type="text" name="name" class="form-control" required>
					</div>
                    <div class="form-group">
						<label>Address <strong>*</strong></label>
						<input type="text" name="address" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Telephone <strong>*</strong></label>
						<input type="text" name="telephone" class="form-control" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
        </div>
    </div>
</div>

<div class="modal fade modal-right" id="tradeReferenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<form class="ajax-form" action="{{ route('customers.update', $data->id) }}" method="post">
				<input type="hidden" name="step" value="new_trade" data-form="update_trade">
				@csrf
				@method('PUT')
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Add New Trade Reference</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
                    <div class="form-group">
						<label>Company Name <strong>*</strong></label>
						<input type="text" name="company_name" class="form-control" required>
					</div>
                    <div class="form-group">
						<label>Address <strong>*</strong></label>
						<input type="text" name="address" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Telephone <strong>*</strong></label>
						<input type="text" name="telephone" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Fax / Email <strong>*</strong></label>
						<input type="text" name="email" class="form-control" required>
					</div>
					<div class="form-group">
						<label>Contact No <strong>*</strong></label>
						<input type="text" name="contact_no" class="form-control" required>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
	$(document).ready(function(){
		$('#organizational_structure').on('change', function () {
            let value = $(this).val();
            $('#customer_sale-tab').closest('li').addClass('d-none');
            $('#limited_plc-tab').closest('li').addClass('d-none');

            if (value === 'Sale Trader' || value === 'Partnership') {
                $('#customer_sale-tab').closest('li').removeClass('d-none');
            } else if (value === 'Limited Company' || value === 'P.L.C') {
                $('#limited_plc-tab').closest('li').removeClass('d-none');
            }
        });
		$('#organizational_structure').change();
	});
</script>
@endpush