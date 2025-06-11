<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
                <img src="{{ asset('admin/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a href="{{ route('dashboard') }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Trang quản trị</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#users">
                        <i class="fas fa-layer-group"></i>
                        <p>Quản lý tài khoản</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="users">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('permissions.index') }}">
                                    <span class="sub-item">Quyền truy cập</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('roles.index') }}">
                                    <span class="sub-item">Vai trò</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}">
                                    <span class="sub-item">Người dùng</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#categories">
                        <i class="fas fa-th-large"></i>
                        <p>Quản lý danh mục</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="categories">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('categories.index') }}">
                                    <span class="sub-item">Danh sách danh mục</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.create') }}">
                                    <span class="sub-item">Thêm danh mục</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#brand">
                        <i class="far fa-chart-bar"></i>
                        <p>Quản lý thương hiệu</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="brand">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('brands.index') }}">
                                    <span class="sub-item">Danh sách thương hiệu</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('brands.create') }}">
                                    <span class="sub-item">Thêm thương hiệu</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#postCategories">
                        <i class="far fa-chart-bar"></i>
                        <p>Quản lý danh mục bài viết</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="postCategories">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('post-categories.index') }}">
                                    <span class="sub-item">Danh sách danh mục bài viết</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('post-categories.create') }}">
                                    <span class="sub-item">Thêm danh mục bài viết</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
