<!-- https://dore-jquery.coloredstrategies.com/Dashboard.Analytics.html -->
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('css/iconsminds.css') }}">
    <link rel="stylesheet" href="{{ asset('css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datatables.responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body id="app-container" class="menu-default show-spinner">
    <nav class="navbar fixed-top">
        <div class="d-flex align-items-center navbar-left">
            <a href="#" class="menu-button d-none d-md-block">
                <svg class="main" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 17">
                    <rect x="0.48" y="0.5" width="7" height="1"/>
                    <rect x="0.48" y="7.5" width="7" height="1"/>
                    <rect x="0.48" y="15.5" width="7" height="1"/>
                </svg>
                <svg class="sub" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 17">
                    <rect x="1.56" y="0.5" width="16" height="1"/>
                    <rect x="1.56" y="7.5" width="16" height="1"/>
                    <rect x="1.56" y="15.5" width="16" height="1"/>
                </svg>
            </a>
            <a href="#" class="menu-button-mobile d-xs-block d-sm-block d-md-none">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 17">
                    <rect x="0.5" y="0.5" width="25" height="1"/>
                    <rect x="0.5" y="7.5" width="25" height="1"/>
                    <rect x="0.5" y="15.5" width="25" height="1"/>
                </svg>
            </a>
            <div class="search" data-search-path="Pages.Search.html?q="><input placeholder="Search..."> <span class="search-icon"><i class="simple-icon-magnifier"></i></span></div>
        </div>
        <a class="navbar-logo" href="Dashboard.Default.html"><span class="logo d-none d-xs-block"></span> <span class="logo-mobile d-block d-xs-none"></span></a>
        <div class="navbar-right">
            <div class="header-icons d-inline-block align-middle">
                <div class="d-none align-text-bottom mr-3">
                    <div class="custom-switch custom-switch-primary-inverse custom-switch-small pl-1" data-toggle="tooltip" data-placement="left" title="Dark Mode"><input class="custom-switch-input" id="switchDark" type="checkbox" checked="checked"> <label class="custom-switch-btn" for="switchDark"></label></div>
                </div>
                <div class="position-relative d-none d-sm-inline-block">
                    <button class="header-icon btn btn-empty" type="button" id="iconMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="simple-icon-grid"></i></button>
                    <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="iconMenuDropdown"><a href="#" class="icon-menu-item"><i class="iconsminds-equalizer d-block"></i> <span>Settings</span> </a><a href="#" class="icon-menu-item"><i class="iconsminds-male-female d-block"></i> <span>Users</span> </a><a href="#" class="icon-menu-item"><i class="iconsminds-puzzle d-block"></i> <span>Components</span> </a><a href="#" class="icon-menu-item"><i class="iconsminds-bar-chart-4 d-block"></i> <span>Profits</span> </a><a href="#" class="icon-menu-item"><i class="iconsminds-file d-block"></i> <span>Surveys</span> </a><a href="#" class="icon-menu-item"><i class="iconsminds-suitcase d-block"></i> <span>Tasks</span></a></div>
                </div>
                <div class="position-relative d-inline-block">
                    <button class="header-icon btn btn-empty" type="button" id="notificationButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="simple-icon-bell"></i> <span class="count">3</span></button>
                    <div class="dropdown-menu dropdown-menu-right mt-3 position-absolute" id="notificationDropdown">
                        <div class="scroll">
                            <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                                <a href="#"><img src="img/profiles/l-2.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle"></a>
                                <div class="pl-3">
                                    <a href="#">
                                        <p class="font-weight-medium mb-1">Joisse Kaycee just sent a new comment!</p>
                                        <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                                <a href="#"><img src="img/notifications/1.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle"></a>
                                <div class="pl-3">
                                    <a href="#">
                                        <p class="font-weight-medium mb-1">1 item is out of stock!</p>
                                        <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex flex-row mb-3 pb-3 border-bottom">
                                <a href="#"><img src="img/notifications/2.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle"></a>
                                <div class="pl-3">
                                    <a href="#">
                                        <p class="font-weight-medium mb-1">New order received! It is total $147,20.</p>
                                        <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                                    </a>
                                </div>
                            </div>
                            <div class="d-flex flex-row mb-3 pb-3">
                                <a href="#"><img src="img/notifications/3.jpg" alt="Notification Image" class="img-thumbnail list-thumbnail xsmall border-0 rounded-circle"></a>
                                <div class="pl-3">
                                    <a href="#">
                                        <p class="font-weight-medium mb-1">3 items just added to wish list by a user!</p>
                                        <p class="text-muted mb-0 text-small">09.04.2018 - 12:45</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="header-icon btn btn-empty d-none d-sm-inline-block" type="button" id="fullScreenButton"><i class="simple-icon-size-fullscreen"></i> <i class="simple-icon-size-actual"></i></button>
            </div>
            <div class="user d-inline-block">
                <button class="btn btn-empty p-0" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="name">{{ Auth::user()->name }}</span> <span><img alt="Profile Picture" src="{{ asset('img/l-1.jpg') }}"></span></button>
                <div class="dropdown-menu dropdown-menu-right mt-3">
                    <a class="dropdown-item" href="#">Account</a>
                    <a class="dropdown-item" href="#">Features</a>
                    <a class="dropdown-item" href="#">History</a>
                    <a class="dropdown-item" href="#">Support</a>
                    <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="{{ route('logout') }}">Sign out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
    </nav>
    <div class="menu">
        <div class="main-menu">
            <div class="scroll">
                <ul class="list-unstyled">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <a href="{{ route('home') }}">
                            <i class="iconsminds-shop-4"></i> <span>Dashboards</span>
                        </a>
                    </li>
                    @can('customer')
                    <li class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <a href="{{ route('customers.index') }}">
                            <i class="iconsminds-add-user"></i> Customers
                        </a>
                    </li>
                    @endcan
                    @can('product')
                    <li class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <a href="{{ route('products.index') }}">
                            <i class="iconsminds-pantone"></i> Products
                        </a>
                    </li>
                    @endcan
                    @can('order')
                    <li class="{{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <a href="{{ route('orders.index') }}">
                            <i class="iconsminds-shopping-bag"></i> Orders
                        </a>
                    </li>
                    @endcan
                    <li><a href="#menu"><i class="iconsminds-three-arrow-fork"></i> Menu</a></li>
                    <li><a href="Blank.Page.html"><i class="iconsminds-bucket"></i> Blank Page</a></li>
                    <li><a href="https://dore-jquery-docs.coloredstrategies.com" target="_blank"><i class="iconsminds-library"></i> Docs</a></li>
                    @can('role')
                    <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}">
                            <i class="iconsminds-layer-forward"></i> Roles
                        </a>
                    </li>
                    @endcan
                    @can('user')
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
                            <i class="iconsminds-user"></i> Users
                        </a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
    <main>
        @yield('content')
    </main>
    <footer class="page-footer">
        <div class="footer-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <p class="mb-0 text-muted">{{ config('app.name', 'Laravel') }} {{ date('Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?<br>This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const ASSET_PATH = "{{ asset('') }}";
    </script>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/progressbar.min.js') }}"></script>
    <script src="{{ asset('js/select2.full.js') }}"></script>
    <script src="{{ asset('js/dore.script.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    @stack('scripts')
    <script>
        $(document).ready(function () {
            $('.ajax-form').on('submit', function (e) {
                e.preventDefault(); // Prevent normal form submission

                // Clear previous messages
                $('.alert-danger, .alert-success').remove();

                let form = $(this);
                let formData = new FormData(this);
                let actionUrl = form.attr('action');
                let submitButton = form.find('button[type="submit"]');
                let stepInput = form.find('input[name="step"]');
                submitButton.prop('disabled', true);

                $.ajax({
                    url: actionUrl,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function (response) {
                        if($(stepInput).length != 0){
                            var previous_step = $(stepInput).data('form');
                            var box_body = $('input[name="step"][value="'+previous_step+'"]').parent().find('.box-body');
                            if($(stepInput).val() == 'new_trade'){
                                let newTrade = response.data;
                                let html = `
                                    <div class="row">
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" name="company_name[${newTrade.id}]" value="${newTrade.company_name}" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address[${newTrade.id}]" value="${newTrade.address}" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Telephone</label>
                                                <input type="text" class="form-control" name="telephone[${newTrade.id}]" value="${newTrade.telephone}" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Fax / Email</label>
                                                <input type="text" class="form-control" name="email[${newTrade.id}]" value="${newTrade.email}" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Contact No</label>
                                                <input type="text" class="form-control" name="contact_no[${newTrade.id}]" value="${newTrade.contact_no}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 mt-1">
                                            <button class="btn btn-danger btn-sm delete-data mt-4" data-url="/customers/${newTrade.id}" data-table="customer_trades">DELETE</button>
                                        </div>
                                    </div>
                                    `;
                                $(box_body).append(html);
                                $('#tradeReferenceModal').modal('hide');
                                $('#tradeReferenceModal').find('form')[0].reset();
                            }
                            if($(stepInput).val() == 'new_customer_sale'){
                                let newCustomerSale = response.data;
                                let html = `
                                    <div class="row">
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name[${newCustomerSale.id}]" value="${newCustomerSale.name}" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address[${newCustomerSale.id}]" value="${newCustomerSale.address}" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <label class="form-label">Telephone</label>
                                                <input type="text" class="form-control" name="telephone[${newCustomerSale.id}]" value="${newCustomerSale.telephone}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-1 mt-1">
                                            <button class="btn btn-danger btn-sm delete-data mt-4" data-url="/customers/${newCustomerSale.id}" data-table="customer_partnerships">DELETE</button>
                                        </div>
                                    </div>
                                    `;
                                $(box_body).append(html);
                                $('#customerSaleModal').modal('hide');
                                $('#customerSaleModal').find('form')[0].reset();
                            }
                            console.log(stepInput);
                            console.log(previous_step);
                        }
                        console.log(response);
                        if(response.success){
                            form.prepend('<div class="alert alert-success">' + response.message + '</div>');
                        }
                        
                        submitButton.prop('disabled', false);
                    },
                    error: function (xhr) {
                        // Validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, messages) {
                                $.each(messages, function (index, message) {
                                    console.log(message);
                                    form.prepend('<div class="alert alert-danger">' + message + '</div>');
                                });
                            });
                        } else {
                            // General error
                            form.prepend('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                        }
                        submitButton.prop('disabled', false);
                    }
                });
            });
        });

        // Delete URL
        $(document).ready(function () {
            let deleteUrl = '';
            let deleteButton = null;
            let deleteTable = null;

            // Open modal on delete button click
            $(document).on('click', '.delete-data', function (e) {
                e.preventDefault();
                deleteUrl = $(this).data('url'); // store URL
                deleteTable = $(this).data('table'); // store URL
                deleteButton = $(this); // store the button (to remove row later)
                $('#deleteConfirmModal').modal('show');
            });

            // Confirm delete
            $('#confirmDeleteBtn').on('click', function () {
                if (!deleteUrl) return;

                $.ajax({
                    url: deleteUrl,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE',
                        table_name: deleteTable
                    },
                    success: function (response) {
                        if(response.success){
                            $('#deleteConfirmModal').modal('hide');
                            deleteButton.closest('.row').slideUp();
                            deleteUrl = '';
                            deleteButton = null;
                        }
                    },
                    error: function (xhr) {
                        $('#deleteConfirmModal').modal('hide');
                        alert('An error occurred while deleting.');
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
</body>
</html>
