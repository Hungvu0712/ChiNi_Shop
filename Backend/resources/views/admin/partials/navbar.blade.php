<div class="main-header-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="index.html" class="logo">
            <img src="{{ asset('admin/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
                class="navbar-brand" height="20" />
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
<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="d-flex align-items-center">
        <span>Xin chào</span> <p>{{ Auth::user()->name }}</p>
    </div>
</nav>
<!-- End Navbar -->
<style>
    .navbar-header {
    background-color: transparent;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    margin-right: 30px;
}

.navbar-header-transparent {
    background-color: rgba(255, 255, 255, 0.95);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar-header .d-flex {
    margin-left: auto;
    align-items: center;
}

.navbar-header span {
    color: #333;
    font-size: 0.9rem;
    margin-right: 0.5rem;
}

.navbar-header p {
    color: #2c3e50;
    font-weight: 600;
    margin: 0;
    font-size: 0.95rem;
}

/* Responsive styles */
@media (max-width: 991.98px) {
    .navbar-header {
        padding: 0.5rem;
    }

    .navbar-header span,
    .navbar-header p {
        font-size: 0.85rem;
    }
}
</style>
