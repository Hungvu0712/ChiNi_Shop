<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo">
                <img src="{{ asset('admin/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
                <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
            </div>
            <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-section">
                    <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
                    <h4 class="text-section">Trang quản trị</h4>
                </li>

                <!-- Quản lý tài khoản -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#users"
                       aria-expanded="{{ request()->routeIs('permissions.*') || request()->routeIs('roles.*') || request()->routeIs('admin.users.*') ? 'true' : 'false' }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Quản lý tài khoản</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('permissions.*') || request()->routeIs('roles.*') || request()->routeIs('admin.users.*') ? 'show' : '' }}" id="users">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <a href="{{ route('permissions.index') }}"><span class="sub-item">Quyền truy cập</span></a>
                            </li>
                            <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <a href="{{ route('roles.index') }}"><span class="sub-item">Vai trò</span></a>
                            </li>
                            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.index') }}"><span class="sub-item">Người dùng</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý sản phẩm -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#products"
                       aria-expanded="{{ request()->routeIs('products.*') ? 'true' : 'false' }}">
                        <i class="fas fa-box"></i>
                        <p>Quản lý sản phẩm</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('products.*') ? 'show' : '' }}" id="products">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                                <a href="{{ route('products.index') }}">
                                    <span class="sub-item">Danh sách sản phẩm</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('products.create') ? 'active' : '' }}">
                                <a href="{{ route('products.create') }}">
                                    <span class="sub-item">Thêm mới sản phẩm</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý danh mục -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#categories"
                       aria-expanded="{{ request()->routeIs('categories.*') ? 'true' : 'false' }}">
                        <i class="fas fa-th-large"></i>
                        <p>Quản lý danh mục</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('categories.*') ? 'show' : '' }}" id="categories">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <a href="{{ route('categories.index') }}"><span class="sub-item">Danh sách</span></a>
                            </li>
                            <li class="{{ request()->routeIs('categories.create') ? 'active' : '' }}">
                                <a href="{{ route('categories.create') }}"><span class="sub-item">Thêm danh mục</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý thương hiệu -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#brand"
                       aria-expanded="{{ request()->routeIs('brands.*') ? 'true' : 'false' }}">
                        <i class="fas fa-tags"></i>
                        <p>Quản lý thương hiệu</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('brands.*') ? 'show' : '' }}" id="brand">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('brands.index') ? 'active' : '' }}">
                                <a href="{{ route('brands.index') }}"><span class="sub-item">Danh sách thương hiệu</span></a>
                            </li>
                            <li class="{{ request()->routeIs('brands.create') ? 'active' : '' }}">
                                <a href="{{ route('brands.create') }}"><span class="sub-item">Thêm thương hiệu</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý danh mục bài viết -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#postCategories"
                       aria-expanded="{{ request()->routeIs('post-categories.*') ? 'true' : 'false' }}">
                        <i class="fas fa-folder"></i>
                        <p>Danh mục bài viết</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('post-categories.*') ? 'show' : '' }}" id="postCategories">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('post-categories.index') ? 'active' : '' }}">
                                <a href="{{ route('post-categories.index') }}"><span class="sub-item">Danh sách</span></a>
                            </li>
                            <li class="{{ request()->routeIs('post-categories.create') ? 'active' : '' }}">
                                <a href="{{ route('post-categories.create') }}"><span class="sub-item">Thêm danh mục</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý bài viết -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#posts"
                       aria-expanded="{{ request()->routeIs('posts.*') ? 'true' : 'false' }}">
                        <i class="fas fa-pen"></i>
                        <p>Quản lý bài viết</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('posts.*') ? 'show' : '' }}" id="posts">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('posts.index') ? 'active' : '' }}">
                                <a href="{{ route('posts.index') }}"><span class="sub-item">Danh sách</span></a>
                            </li>
                            <li class="{{ request()->routeIs('posts.create') ? 'active' : '' }}">
                                <a href="{{ route('posts.create') }}"><span class="sub-item">Thêm bài viết</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý menu -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#menu"
                       aria-expanded="{{ request()->routeIs('menus.*') ? 'true' : 'false' }}">
                        <i class="fas fa-list"></i>
                        <p>Quản lý Menus</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('menus.*') ? 'show' : '' }}" id="menu">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('menus.index') ? 'active' : '' }}">
                                <a href="{{ route('menus.index') }}"><span class="sub-item">Danh sách</span></a>
                            </li>
                            <li class="{{ request()->routeIs('menus.create') ? 'active' : '' }}">
                                <a href="{{ route('menus.create') }}"><span class="sub-item">Thêm mới</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý Attributes -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#attributes"
                       aria-expanded="{{ request()->routeIs('attributes.*') ? 'true' : 'false' }}">
                        <i class="fas fa-cog"></i>
                        <p>Quản lý Attributes</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('attributes.*') ? 'show' : '' }}" id="attributes">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('attributes.index') ? 'active' : '' }}">
                                <a href="{{ route('attributes.index') }}"><span class="sub-item">Danh sách</span></a>
                            </li>
                            <li class="{{ request()->routeIs('attributes.create') ? 'active' : '' }}">
                                <a href="{{ route('attributes.create') }}"><span class="sub-item">Thêm</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Quản lý Attribute Values -->
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#attribute_values"
                       aria-expanded="{{ request()->routeIs('attribute_values.*') ? 'true' : 'false' }}">
                        <i class="fas fa-sliders-h"></i>
                        <p>Attribute Values</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->routeIs('attribute_values.*') ? 'show' : '' }}" id="attribute_values">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->routeIs('attribute_values.index') ? 'active' : '' }}">
                                <a href="{{ route('attribute_values.index') }}"><span class="sub-item">Danh sách</span></a>
                            </li>
                            <li class="{{ request()->routeIs('attribute_values.create') ? 'active' : '' }}">
                                <a href="{{ route('attribute_values.create') }}"><span class="sub-item">Thêm</span></a>
                            </li>
                        </ul>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
