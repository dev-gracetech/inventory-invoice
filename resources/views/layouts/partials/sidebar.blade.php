<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    {{-- @if(App\Models\SystemSetting::first()->company_logo)
                        <img src="{{ asset('storage/' . App\Models\SystemSetting::first()->company_logo) }}" alt="Company Logo" class="img-fluid" style="width: 60px; height: 60px;">
                    @else --}}
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 60px; height: 60px;">
                    {{-- @endif
                    <a href="#"> {{ App\Models\SystemSetting::first()->company_name }}</a> --}}
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-clipboard-data"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-bank"></i>
                        <span>Transaction</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('products.index') }}" class="submenu-link">Product</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('customers.index') }}" class="submenu-link">Customer</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('quotations.index') }}" class="submenu-link">Quotation</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('invoices.index') }}" class="submenu-link">Invoice</a>
                        </li>
                    </ul>
                </li>
                @can('report_manage')
                <li class="sidebar-item has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-newspaper"></i>
                        <span>Reports</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="#" class="submenu-link">Products</a>
                        </li>
                        {{-- <li class="submenu-item">
                            <a href="{{route('reports.branch-stock')}}" class="submenu-link">Branch Stock</a>
                        </li> --}}
                    </ul>
                </li>
                @endcan
                @can('user_manage')
                <li class="sidebar-item  has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people"></i>
                        <span>User Management</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{route('users.index')}}" class="submenu-link">Users</a>
                        </li>
                        @can('role_manage')
                        <li class="submenu-item">
                            <a href="{{route('roles.index')}}" class="submenu-link">Roles</a>
                        </li>
                        @endcan
                        @can('permission_manage')
                        <li class="submenu-item">
                            <a href="{{route('permissions.index')}}" class="submenu-link">Permissions</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>
        </div>
    </div>  
</div>