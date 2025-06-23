<?php
$current_page = basename($_SERVER['PHP_SELF']);
// Get the base URL for the admin panel
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/admin';
?>

<!-- Sidebar Overlay (for mobile) -->
<div class="sidebar-overlay"></div>

<!-- Main Sidebar -->
<aside class="admin-sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <h2>
            <i class="fas fa-futbol"></i>
            <span>KitsNepal</span>
        </h2>
    </div>

    <!-- User Profile -->
    <div class="user-panel">
        <div class="user-avatar">
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-info">
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></div>
            <div class="user-role">Administrator</div>
        </div>
    </div>

    <!-- Main Menu -->
    <ul class="sidebar-menu">
        <!-- Dashboard -->
        <li class="menu-item <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
            <a href="dashboard.php">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Products -->
        <li class="menu-item <?php echo $current_page === 'products.php' ? 'active' : ''; ?>">
            <a href="products.php">
                <i class="fas fa-tshirt"></i>
                <span>View Products</span>
            </a>
        </li>

        <!-- Add Product -->
        <li class="menu-item <?php echo $current_page === 'add_product.php' ? 'active' : ''; ?>">
            <a href="add_product.php">
                <i class="fas fa-plus-circle"></i>
                <span>Add Product</span>
            </a>
        </li>
    </ul>

    <!-- Bottom Menu -->
    <div class="sidebar-bottom">
        <ul class="sidebar-menu">
            <!-- View Site -->
            <li class="menu-item">
                <a href="../../index.php" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="menu-item">
                <a href="logout.php" class="text-danger">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

<!-- Mobile Menu Toggle -->
<button class="menu-toggle">
    <i class="fas fa-bars"></i>
</button>

<!-- JavaScript for sidebar functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const hasSubmenu = document.querySelectorAll('.has-submenu > a');
    const mediaQuery = window.matchMedia('(max-width: 992px)');

    // Toggle sidebar
    function toggleSidebar() {
        const isOpen = sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active', isOpen);
        document.body.classList.toggle('sidebar-open', isOpen);

        // Store sidebar state in localStorage
        if (mediaQuery.matches) {
            localStorage.setItem('sidebarCollapsed', !isOpen);
        }
    }

    // Toggle submenus
    function toggleSubmenu(e) {
        if (mediaQuery.matches) {
            e.preventDefault();
            const parent = this.parentElement;
            const wasActive = parent.classList.contains('active');

            // Close all submenus first
            document.querySelectorAll('.has-submenu').forEach(item => {
                item.classList.remove('active');
            });

            // Toggle the clicked submenu if it wasn't active
            if (!wasActive) {
                parent.classList.add('active');
            }
        }
    }

    // Close sidebar when clicking on overlay
    function closeSidebar() {
        if (sidebar.classList.contains('active')) {
            toggleSidebar();
        }
    }

    // Handle window resize
    function handleResize() {
        if (!mediaQuery.matches) {
            // Reset styles on desktop
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');

            // Reset all submenus
            document.querySelectorAll('.has-submenu').forEach(item => {
                item.classList.remove('active');
            });
        } else {
            // Apply saved state on mobile
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            } else {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.classList.add('sidebar-open');
            }
        }
    }

    // Event Listeners
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    hasSubmenu.forEach(item => {
        item.addEventListener('click', toggleSubmenu);
    });

    // Initialize on load
    handleResize();
    window.addEventListener('resize', handleResize);

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (mediaQuery.matches &&
            !sidebar.contains(e.target) &&
            !menuToggle.contains(e.target) &&
            sidebar.classList.contains('active')) {
            toggleSidebar();
        }
    });

    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
