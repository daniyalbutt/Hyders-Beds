@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>Import Products</h1>
        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
            <ol class="breadcrumb pt-0">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Import Product</li>
            </ol>
        </nav>
    </div>
	<div class="col-lg-6">
		<div class="text-right">
			@can('product')
			<a href="{{ route('products.index') }}" class="btn btn-primary">Product List</a>
			@endcan
		</div>
	</div>
	<div class="col-md-12">
		<div class="separator mb-5"></div>
	</div>
</div>

<div class="card h-100">
	<div class="card-body">
		<h5 class="card-title">Add Product Form</h5>
		<div class="basic-form">
			<form class="form" method="post" action="{{ route('product.update') }}" enctype="multipart/form-data">
				<input type="hidden" name="has_file" value="1">
				@csrf
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
						<div class="col-md-12">
							<div class="form-group mb-3">
								<label class="form-label">File <strong>*</strong></label>
								<input type="file" class="form-control" name="file" required>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="submit" class="btn btn-primary">Upload Product</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="card h-100 mt-5">
	<div class="card-body">
		<h5 class="card-title">Copy Excel Data</h5>
		<div class="basic-form">
			<form class="form" method="post" enctype="multipart/form-data">
				@csrf
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
						<div class="col-md-12">
							<div id="pasteArea" contenteditable="true">
								Click here and paste (Ctrl+V) your Excel data.
							</div>
							<div id="tableContainer" class="table-responsive">
								<table class="table table-striped">
									<thead>
										<tr id="headerRow">
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer">
					<button type="button" id="submitBtn" class="btn btn-primary">Submit to Database</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('scripts')
<script>
    const pasteArea = document.getElementById('pasteArea');
	const table = document.querySelector('#tableContainer table');
	const headerRow = document.getElementById('headerRow');
	const tbody = table.querySelector('tbody');
	const submitBtn = document.getElementById('submitBtn');

	const fieldOptions = [
		'Product Code',
		'Product Description',
		'Sale Price',
		'Volume',
		'Weight',
		'Width',
		'Length',
		'Height',
		'Product Range',
		'Product Section',
		'Production Type'
	];

	// Dynamically generate the header with dropdowns
	function buildHeader(columnCount) {
		headerRow.innerHTML = '';
		for (let i = 0; i < columnCount; i++) {
			const th = document.createElement('th');
			const select = document.createElement('select');

			fieldOptions.forEach((optionText) => {
				const option = document.createElement('option');
				option.value = optionText;
				option.textContent = optionText;
				select.appendChild(option);
			});

			select.selectedIndex = i < fieldOptions.length ? i : 0;
			th.appendChild(select);
			headerRow.appendChild(th);
		}

		const actionTh = document.createElement('th');
		actionTh.textContent = 'Action';
		headerRow.appendChild(actionTh);
	}

	pasteArea.addEventListener('paste', (e) => {
		e.preventDefault();
		const clipboardData = e.clipboardData || window.clipboardData;
		const pastedData = clipboardData.getData('Text');

		const rows = pastedData.trim().split('\n');
		if (rows.length === 0) return;

		const firstRowColumns = rows[0].split('\t').length;
		if (headerRow.children.length === 0) {
			buildHeader(firstRowColumns);
		}

		rows.forEach(row => {
			const tr = document.createElement('tr');
			const cells = row.split('\t');

			cells.forEach(cell => {
				const td = document.createElement('td');
				td.textContent = cell;
				td.contentEditable = true;
				tr.appendChild(td);
			});

			const actionTd = document.createElement('td');
			const deleteBtn = document.createElement('button');
			deleteBtn.textContent = 'Delete';
			deleteBtn.className = 'btn btn-sm btn-danger';
			deleteBtn.onclick = () => tr.remove();
			actionTd.appendChild(deleteBtn);
			tr.appendChild(actionTd);

			tbody.appendChild(tr);
		});
	});

	submitBtn.addEventListener('click', () => {
		const table = document.querySelector('#tableContainer table');
		const rows = table.querySelectorAll('tbody tr');
		const updatedData = [];

		// Step 1: Get selected field names from header dropdowns
		const selectedHeaders = [];
		const headerSelects = document.querySelectorAll('#headerRow select');
		headerSelects.forEach(select => selectedHeaders.push(select.value));

		// Step 2: Get all table data rows
		rows.forEach(row => {
			const cells = row.querySelectorAll('td');
			const rowData = [];

			// Ignore last cell (action button)
			for (let i = 0; i < cells.length - 1; i++) {
				rowData.push(cells[i].textContent.trim());
			}

			updatedData.push(rowData);
		});

		// Step 3: Send both headers and data to Laravel
		fetch('{{ route("product.update") }}', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
				},
				body: JSON.stringify({
					headers: selectedHeaders,
					data: updatedData,
					has_file: 0
				})
			})
			.then(res => res.json())
			.then(result => {
				alert('Data saved successfully!');
			})
			.catch(err => {
				console.error(err);
				alert('Error saving data');
			});
	});
</script>
@endpush