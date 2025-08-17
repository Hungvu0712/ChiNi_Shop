<nav class="admin-navbar navbar navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <!-- Brand/Home Link -->
        <div class="navbar-brand">
            <a href="{{ route('home.index') }}" class="home-link" data-bs-toggle="tooltip" data-bs-placement="right" title="Trang khách hàng">
                <i class="fas fa-external-link-alt"></i>
                <span class="d-none d-sm-inline">Về trang khách hàng</span>
            </a>
        </div>

        <!-- User Info & Logout Button -->
        <div class="d-flex align-items-center ms-auto">
            <div class="user-info me-3">
                <span class="welcome-text d-none d-md-inline">Xin chào,</span>
                <span class="username">{{ Auth::user()->name }}</span>
                <img src="{{ Auth::user()->profile->avatar ?? 'https://via.placeholder.com/40' }}" alt="User Avatar" class="user-avatar rounded-circle ms-2">
            </div>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="btn logout-btn" data-bs-toggle="tooltip" title="Đăng xuất">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="d-none d-md-inline ms-1">Đăng xuất</span>
                </button>
            </form>
        </div>
    </div>
</nav>

<style>
    /* Navbar Container */
    .admin-navbar {
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 0.5rem 1rem;
    }

    /* Home Link Styling */
    .home-link {
        color: #4e73df;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .home-link:hover {
        background-color: #f8f9fa;
        color: #2e59d9;
    }

    .home-link i {
        font-size: 1.1rem;
    }

    /* User Info */
    .user-info {
        display: flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .user-info:hover {
        background-color: #e9ecef;
    }

    .welcome-text {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .username {
        color: #343a40;
        font-weight: 600;
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-avatar {
        width: 32px;
        height: 32px;
        object-fit: cover;
        border: 2px solid #dee2e6;
    }

    /* Logout Button */
    .logout-btn {
        color: #dc3545;
        background-color: transparent;
        border: none;
        padding: 0.5rem 0.75rem;
        border-radius: 4px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
    }

    .logout-btn:hover {
        background-color: #f8d7da;
        color: #dc3545;
    }

    .logout-btn:focus {
        box-shadow: none;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .username {
            max-width: 100px;
        }
    }

    @media (max-width: 576px) {
        .welcome-text {
            display: none;
        }

        .username {
            max-width: 80px;
            font-size: 0.9rem;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
        }

        .logout-btn {
            padding: 0.5rem;
        }

        .logout-btn span {
            display: none;
        }
    }
</style>

<!-- Required Libraries -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
