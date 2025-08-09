<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kos Management System</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    
    <!-- CSS Files -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #8b5cf6;
            --accent-color: #06b6d4;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --white: #ffffff;
            --sidebar-width: 280px;
            --topbar-height: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--gray-800);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--white);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
            border-right: 1px solid var(--gray-200);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 30px 25px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .sidebar-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .logo-container {
            position: relative;
            z-index: 2;
        }

        .logo-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-header h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .sidebar-header p {
            font-size: 13px;
            opacity: 0.8;
            font-weight: 400;
        }

        .sidebar-menu {
            padding: 25px 0;
        }

        .menu-section {
            margin-bottom: 35px;
        }

        .menu-section-title {
            padding: 0 25px 15px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-400);
        }

        .menu-item {
            margin: 3px 0;
        }

        .menu-link {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
        }

        .menu-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            border-radius: 0 4px 4px 0;
        }

        .menu-link:hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, transparent 100%);
            color: var(--primary-color);
            padding-left: 30px;
        }

        .menu-link:hover::before {
            transform: scaleY(1);
        }

        .menu-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0.05) 100%);
            color: var(--primary-color);
            font-weight: 600;
        }

        .menu-link.active::before {
            transform: scaleY(1);
        }

        .menu-link i {
            width: 20px;
            margin-right: 15px;
            font-size: 16px;
            text-align: center;
        }

        .menu-text {
            flex: 1;
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
            font-size: 12px;
            opacity: 0.7;
        }

        .dropdown-arrow.open {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            background: var(--gray-50);
            transition: max-height 0.3s ease;
            margin: 3px 0;
        }

        .submenu.open {
            max-height: 300px;
        }

        .submenu-item {
            padding: 12px 25px 12px 60px;
            color: var(--gray-500);
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            font-size: 13px;
            font-weight: 500;
            position: relative;
        }

        .submenu-item::before {
            content: '•';
            position: absolute;
            left: 45px;
            color: var(--gray-300);
            font-size: 16px;
        }

        .submenu-item:hover {
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
            padding-left: 65px;
        }

        /* Topbar Styles */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--white);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--gray-200);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .mobile-menu-toggle {
            display: none;
            color: var(--gray-600);
            font-size: 18px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: var(--gray-100);
            color: var(--primary-color);
        }

        .breadcrumb {
            color: var(--gray-500);
            font-size: 14px;
            font-weight: 500;
        }

        .breadcrumb .current {
            color: var(--gray-800);
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: var(--gray-100);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--gray-600);
            font-size: 16px;
            position: relative;
        }

        .action-btn:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: var(--white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
        }

        .admin-profile:hover {
            background: var(--gray-100);
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }

        .profile-avatar::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .profile-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-800);
            line-height: 1.2;
        }

        .profile-role {
            font-size: 12px;
            color: var(--gray-500);
            font-weight: 500;
        }

        /* Content Area */
        .content-area {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 35px;
            min-height: calc(100vh - var(--topbar-height));
            background: var(--gray-50);
        }

        .content-header {
            margin-bottom: 35px;
        }

        .content-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .content-subtitle {
            color: var(--gray-500);
            font-size: 16px;
            font-weight: 500;
        }

        /* Dashboard Cards */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 35px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--white);
            position: relative;
        }

        .stat-icon.users {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .stat-icon.rooms {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .stat-icon.bookings {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .stat-icon.revenue {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 20px;
        }

        .stat-trend.up {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .stat-trend.down {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: var(--gray-900);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-label {
            color: var(--gray-500);
            font-size: 14px;
            font-weight: 500;
        }

        /* Dashboard Content */
        .dashboard-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 35px;
        }

        .content-card {
            background: var(--white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--gray-200);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .card-action {
            color: var(--primary-color);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .card-action:hover {
            color: var(--secondary-color);
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 35px;
        }

        .action-card {
            background: var(--white);
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--gray-200);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--white);
            margin: 0 auto 15px;
        }

        .action-icon.add-user {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .action-icon.add-room {
            background: linear-gradient(135deg, #f093fb, #f5576c);
        }

        .action-icon.settings {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
        }

        .action-icon.reports {
            background: linear-gradient(135deg, #43e97b, #38f9d7);
        }

        .action-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 5px;
        }

        .action-desc {
            font-size: 12px;
            color: var(--gray-500);
        }

        /* Recent Activity */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 600;
            font-size: 14px;
            margin-right: 15px;
        }

        .activity-content {
            flex: 1;
        }

        .activity-text {
            font-size: 14px;
            color: var(--gray-700);
            margin-bottom: 4px;
        }

        .activity-time {
            font-size: 12px;
            color: var(--gray-500);
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .topbar {
                left: 0;
            }

            .content-area {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block !important;
            }

            .dashboard-stats {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: none;
            width: 40px;
            height: 40px;
            border: 4px solid var(--gray-200);
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 50px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* AOS Animation Override */
        [data-aos] {
            pointer-events: auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-home"></i>
                </div>
                <h3>Kos Manager Pro</h3>
                <p>Admin Dashboard v2.0</p>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <!-- Main Menu -->
            <div class="menu-section">
                <div class="menu-section-title">Main Menu</div>
                
                <div class="menu-item">
                    <a href="#" class="menu-link active" onclick="setActiveMenu(this, 'Dashboard')" data-aos="fade-right" data-aos-delay="100">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-link" onclick="toggleDropdown(this)" data-aos="fade-right" data-aos-delay="150">
                        <i class="fas fa-database"></i>
                        <span class="menu-text">Master Data</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Master Data', 'Fasilitas')">Fasilitas</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Master Data', 'Lantai')">Lantai</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Master Data', 'Lokasi')">Lokasi (Kota)</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Master Data', 'Tipe Kamar')">Tipe Kamar</a>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="menu-section">
                <div class="menu-section-title">Settings</div>
                
                <div class="menu-item">
                    <div class="menu-link" onclick="toggleDropdown(this)" data-aos="fade-right" data-aos-delay="200">
                        <i class="fas fa-cog"></i>
                        <span class="menu-text">Website Settings</span>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="submenu">
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Website Settings', 'Banner Home')">Banner Home</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Website Settings', 'Title Sistem')">Title Sistem</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Website Settings', 'Nama Perusahaan')">Nama Perusahaan</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Website Settings', 'Alamat Perusahaan')">Alamat Perusahaan</a>
                        <a href="#" class="submenu-item" onclick="setActiveSubmenu(this, 'Website Settings', 'Nomor WA')">Nomor WhatsApp</a>
                    </div>
                </div>
            </div>

            <!-- Management -->
            <div class="menu-section">
                <div class="menu-section-title">Management</div>
                
                <div class="menu-item">
                    <a href="#" class="menu-link" onclick="setActiveMenu(this, 'User Management')" data-aos="fade-right" data-aos-delay="250">
                        <i class="fas fa-users"></i>
                        <span class="menu-text">User</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="#" class="menu-link" onclick="setActiveMenu(this, 'Bookings')" data-aos="fade-right" data-aos-delay="300">
                        <i class="fas fa-calendar-check"></i>
                        <span class="menu-text">Bookings</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a href="#" class="menu-link" onclick="setActiveMenu(this, 'Reports')" data-aos="fade-right" data-aos-delay="350">
                        <i class="fas fa-chart-line"></i>
                        <span class="menu-text">Reports</span>
                    </a>
                </div>
            </div>

            <!-- System -->
            <div class="menu-section">
                <div class="menu-section-title">System</div>
                
                <div class="menu-item">
                    <a href="#" class="menu-link" onclick="logout()" data-aos="fade-right" data-aos-delay="400">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="menu-text">Logout</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <i class="fas fa-bars mobile-menu-toggle" onclick="toggleMobileSidebar()"></i>
            <div class="breadcrumb">
                <span id="breadcrumb-text">Admin Panel / <span class="current">Dashboard</span></span>
            </div>
        </div>
        
        <div class="topbar-right">
            <div class="topbar-actions">
                <button class="action-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">5</span>
                </button>
                
                <button class="action-btn" title="Messages">
                    <i class="fas fa-envelope"></i>
                    <span class="notification-badge">2</span>
                </button>
                
                <button class="action-btn" title="Settings">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
            
            <div class="admin-profile" onclick="toggleProfileMenu()">
                <div class="profile-avatar">AD</div>
                <div class="profile-info">
                    <div class="profile-name">Admin User</div>
                    <div class="profile-role">Super Administrator</div>
                </div>
                <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px; color: var(--gray-400);"></i>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="content-area">
        <div class="content-header">
            <h1 class="content-title" id="page-title" data-aos="fade-up">Dashboard</h1>
            <p class="content-subtitle" id="page-subtitle" data-aos="fade-up" data-aos-delay="100">Selamat datang di sistem manajemen kos terpadu</p>