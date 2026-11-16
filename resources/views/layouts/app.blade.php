<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FurHouse') — Community Market</title>

    @if(request()->routeIs('support.*'))
    <link rel="stylesheet" href="{{ asset('css/support.css') }}">
    @endif

    @if(request()->routeIs('profile'))
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endif

    @if(request()->routeIs('admin.announcements*'))
    <link rel="stylesheet" href="{{ asset('css/announcements.css') }}">
    @endif
    
    @if(request()->routeIs('rules'))
    <link rel="stylesheet" href="{{ asset('css/rules.css') }}">
    @endif

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --fh-primary: #7c5c9c;
            --fh-primary-dark: #5a3d7a;
            --fh-primary-light: #9b7fbf;
            --fh-bg: #f5f0fa;
            --fh-bg-light: #faf7fd;
            --fh-card: #ffffff;
            --fh-text: #2d2d2d;
            --fh-text-muted: #6b6b6b;
            --fh-border: #e8e0f0;
            --fh-success: #6b9e7a;
            --fh-warning: #d4a853;
            --fh-danger: #c45c5c;
            --fh-radius: 16px;
            --fh-radius-sm: 10px;
            --fh-shadow: 0 2px 12px rgba(124, 92, 156, 0.08);
            --fh-shadow-lg: 0 8px 32px rgba(124, 92, 156, 0.12);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background-color: var(--fh-bg);
            color: var(--fh-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .fh-navbar {
            background: var(--fh-bg-light);
            border-bottom: 1px solid var(--fh-border);
            padding: 12px 0;
        }

        .fh-navbar .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--fh-primary-dark);
            text-decoration: none;
        }

        .fh-navbar .navbar-brand .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--fh-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .fh-navbar .navbar-brand small {
            display: block;
            font-size: 0.65rem;
            font-weight: 500;
            color: var(--fh-text-muted);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .fh-nav-link {
            color: var(--fh-text-muted) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 14px !important;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .fh-nav-link:hover,
        .fh-nav-link.active {
            color: var(--fh-primary-dark) !important;
            background: rgba(124, 92, 156, 0.08);
        }

        .fh-nav-link.active {
            position: relative;
        }

        .fh-nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: var(--fh-primary);
            border-radius: 2px;
        }

        .fh-btn {
            border-radius: 24px;
            padding: 8px 20px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .fh-btn-primary {
            background: var(--fh-primary);
            color: white;
            border: none;
        }

        .fh-btn-primary:hover {
            background: var(--fh-primary-dark);
            color: white;
        }

        .fh-btn-outline {
            background: white;
            color: var(--fh-primary-dark);
            border: 1.5px solid var(--fh-border);
        }

        .fh-btn-outline:hover {
            border-color: var(--fh-primary);
            color: var(--fh-primary);
        }

        .fh-btn-ghost {
            background: transparent;
            color: var(--fh-text);
            border: 1.5px solid var(--fh-border);
        }

        .fh-btn-ghost:hover {
            background: var(--fh-bg);
        }

        .fh-btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        /* Main content */
        .fh-main {
            flex: 1;
        }

        /* Cards */
        .fh-card {
            background: var(--fh-card);
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            box-shadow: var(--fh-shadow);
            overflow: hidden;
            transition: all 0.3s;
        }

        .fh-card:hover {
            box-shadow: var(--fh-shadow-lg);
            transform: translateY(-2px);
        }

        .fh-card-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .fh-card-body {
            padding: 16px;
        }

        /* Badges */
        .fh-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .fh-badge-primary {
            background: rgba(124, 92, 156, 0.12);
            color: var(--fh-primary-dark);
        }

        .fh-badge-success {
            background: rgba(107, 158, 122, 0.12);
            color: var(--fh-success);
        }

        .fh-badge-warning {
            background: rgba(212, 168, 83, 0.15);
            color: #a07820;
        }

        /* Section headers */
        .fh-section-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--fh-primary);
            margin-bottom: 8px;
        }

        .fh-section-title {
            font-size: 2rem;
            font-weight: 300;
            color: var(--fh-text);
            margin-bottom: 12px;
        }

        .fh-section-subtitle {
            color: var(--fh-text-muted);
            font-size: 1rem;
            max-width: 600px;
        }

        /* Search */
        .fh-search {
            background: white;
            border: 1.5px solid var(--fh-border);
            border-radius: 24px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fh-search input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 0.9rem;
            background: transparent;
        }

        .fh-search input::placeholder {
            color: #aaa;
        }

        /* Footer */
        .fh-footer {
            background: var(--fh-primary-dark);
            color: rgba(255, 255, 255, 0.85);
            padding: 60px 0 30px;
            margin-top: auto;
        }

        .fh-footer h6 {
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }

        .fh-footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 8px;
            transition: color 0.2s;
        }

        .fh-footer a:hover {
            color: white;
        }

        .fh-footer .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .fh-footer .footer-brand .brand-icon {
            width: 44px;
            height: 44px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fh-primary-dark);
            font-size: 1.3rem;
        }

        .fh-footer .footer-brand strong {
            color: white;
            font-size: 1.2rem;
        }

        .fh-footer .footer-brand small {
            display: block;
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .fh-footer .social-links {
            display: flex;
            gap: 10px;
        }

        .fh-footer .social-links a {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin: 0;
        }

        .fh-footer .social-links a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .fh-footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 24px;
            margin-top: 40px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .fh-footer-bottom a {
            color: rgba(255, 255, 255, 0.6);
            display: inline;
            margin: 0 4px;
        }

        /* Alerts */
        .fh-alert {
            border-radius: var(--fh-radius-sm);
            border: none;
            padding: 14px 20px;
            font-size: 0.9rem;
        }

        /* Stat cards */
        .fh-stat-card {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 20px;
            position: relative;
        }

        .fh-stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(124, 92, 156, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fh-primary);
            font-size: 1.2rem;
            margin-bottom: 12px;
        }

        .fh-stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--fh-text);
        }

        .fh-stat-card .stat-label {
            font-size: 0.85rem;
            color: var(--fh-text-muted);
            margin-top: 4px;
        }

        .fh-stat-card .stat-trend {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 0.75rem;
            color: var(--fh-success);
        }

        /* User avatar */
        .fh-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--fh-border);
        }

        .fh-avatar-lg {
            width: 64px;
            height: 64px;
        }

        .fh-avatar-xl {
            width: 80px;
            height: 80px;
        }

        /* Page header */
        .fh-page-header {
            padding: 40px 0 30px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .fh-section-title {
                font-size: 1.5rem;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--fh-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--fh-primary-light);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--fh-primary);
        }

        /* Filter sidebar */
        .fh-filter-panel {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 24px;
        }

        .fh-filter-panel h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .fh-filter-group {
            margin-bottom: 24px;
        }

        .fh-filter-group label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--fh-text-muted);
            margin-bottom: 10px;
            display: block;
        }

        .fh-checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            cursor: pointer;
        }

        .fh-checkbox-item input[type="checkbox"] {
            accent-color: var(--fh-primary);
            width: 16px;
            height: 16px;
        }

        .fh-checkbox-item .count {
            margin-left: auto;
            font-size: 0.8rem;
            color: var(--fh-text-muted);
        }

        /* Price range slider placeholder */
        .fh-price-range {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .fh-price-range input {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid var(--fh-border);
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .fh-price-range input:focus {
            outline: none;
            border-color: var(--fh-primary);
        }

        /* Pagination */
        .fh-pagination .page-link {
            border: 1.5px solid var(--fh-border);
            color: var(--fh-text);
            border-radius: 50% !important;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            font-size: 0.9rem;
        }

        .fh-pagination .page-item.active .page-link {
            background: var(--fh-primary);
            border-color: var(--fh-primary);
            color: white;
        }

        /* Forum specific */
        .fh-board-item {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            color: var(--fh-text);
        }

        .fh-board-item:hover,
        .fh-board-item.active {
            background: rgba(124, 92, 156, 0.08);
        }

        .fh-board-item .board-prefix {
            font-weight: 700;
            color: var(--fh-primary);
            font-size: 0.85rem;
            min-width: 50px;
        }

        .fh-board-item .board-name {
            flex: 1;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .fh-board-item .board-count {
            font-size: 0.8rem;
            color: var(--fh-text-muted);
        }

        .fh-thread-card {
            background: white;
            border-radius: var(--fh-radius-sm);
            border: 1px solid var(--fh-border);
            padding: 16px;
            display: flex;
            gap: 16px;
            margin-bottom: 12px;
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
        }

        .fh-thread-card:hover {
            box-shadow: var(--fh-shadow);
            border-color: var(--fh-primary-light);
        }

        .fh-thread-card img {
            width: 120px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .fh-thread-card .thread-meta {
            font-size: 0.8rem;
            color: var(--fh-text-muted);
        }

        .fh-thread-card .thread-stats {
            display: flex;
            gap: 16px;
            font-size: 0.8rem;
            color: var(--fh-text-muted);
            margin-top: 8px;
        }

        /* Event featured card */
        .fh-featured-event {
            background: var(--fh-primary-dark);
            border-radius: var(--fh-radius);
            overflow: hidden;
            color: white;
        }

        .fh-featured-event .event-image {
            height: 100%;
            min-height: 300px;
            object-fit: cover;
        }

        .fh-featured-event .event-info {
            padding: 32px;
        }

        .fh-featured-event h3 {
            font-size: 1.6rem;
            font-weight: 400;
            margin-bottom: 12px;
        }

        /* Team member card */
        .fh-team-card {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 24px;
        }

        .fh-team-card .member-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .fh-team-card .member-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .fh-team-card .member-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--fh-border);
        }

        .fh-team-card .member-actions {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .fh-team-card .member-actions .btn {
            flex: 1;
        }

        /* Admin sidebar */
        .fh-admin-sidebar {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 20px;
        }

        .fh-admin-nav-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--fh-text);
            margin-bottom: 4px;
            transition: background 0.2s;
        }

        .fh-admin-nav-item:hover,
        .fh-admin-nav-item.active {
            background: rgba(124, 92, 156, 0.08);
        }

        .fh-admin-nav-item .badge-count {
            background: var(--fh-warning);
            color: white;
            border-radius: 12px;
            padding: 2px 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Activity log */
        .fh-activity-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--fh-border);
        }

        .fh-activity-item:last-child {
            border-bottom: none;
        }

        .fh-activity-item .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(124, 92, 156, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fh-primary);
            flex-shrink: 0;
        }

        .fh-activity-item .activity-text {
            flex: 1;
            font-size: 0.9rem;
        }

        .fh-activity-item .activity-time {
            font-size: 0.8rem;
            color: var(--fh-text-muted);
            white-space: nowrap;
        }

        /* Auth forms */
        .fh-auth-card {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 40px;
            max-width: 480px;
            margin: 40px auto;
            box-shadow: var(--shadow-lg);
        }

        .fh-form-control {
            border: 1.5px solid var(--fh-border);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .fh-form-control:focus {
            border-color: var(--fh-primary);
            box-shadow: 0 0 0 3px rgba(124, 92, 156, 0.1);
        }

        /* Hero section */
        .fh-hero {
            padding: 60px 0;
        }

        .fh-hero-image {
            border-radius: var(--fh-radius);
            overflow: hidden;
            box-shadow: var(--fh-shadow-lg);
        }

        .fh-hero-image img {
            width: 100%;
            height: auto;
        }

        /* Category cards */
        .fh-category-card {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 24px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
        }

        .fh-category-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .fh-category-card .cat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 12px;
        }

        .fh-category-card h6 {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .fh-category-card small {
            color: var(--fh-text-muted);
            font-size: 0.8rem;
        }

        /* Product card */
        .fh-product-card {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .fh-product-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .fh-product-card .product-image-wrap {
            position: relative;
            height: 220px;
            overflow: hidden;
        }

        .fh-product-card .product-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fh-product-card .favorite-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .fh-product-card .favorite-btn.active {
            color: var(--fh-danger);
        }

        .fh-product-card .product-info {
            padding: 16px;
        }

        .fh-product-card .product-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .fh-product-card .product-author {
            font-size: 0.8rem;
            color: var(--fh-text-muted);
            margin-bottom: 10px;
        }

        .fh-product-card .product-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .fh-product-card .product-price {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--fh-primary-dark);
        }

        .fh-product-card .product-rating {
            font-size: 0.85rem;
            color: var(--fh-warning);
        }

        .fh-product-card .add-to-cart-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--fh-primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* Event card */
        .fh-event-card {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none;
            color: inherit;
        }

        .fh-event-card:hover {
            box-shadow: var(--shadow-lg);
        }

        .fh-event-card .event-image-wrap {
            position: relative;
            height: 200px;
        }

        .fh-event-card .event-image-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .fh-event-card .event-date-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .fh-event-card .event-info {
            padding: 16px;
        }

        .fh-event-card .event-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .fh-event-card .event-meta {
            font-size: 0.85rem;
            color: var(--fh-text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }

        .fh-event-card .event-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--fh-border);
        }

        /* Quick action buttons */
        .fh-quick-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: white;
            border-radius: 12px;
            border: 1px solid var(--fh-border);
            text-decoration: none;
            color: var(--fh-text);
            margin-bottom: 10px;
            transition: all 0.2s;
        }

        .fh-quick-action:hover {
            border-color: var(--fh-primary);
            background: rgba(124, 92, 156, 0.03);
        }

        .fh-quick-action .qa-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(124, 92, 156, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fh-primary);
            margin-right: 12px;
        }

        .fh-quick-action .qa-text {
            flex: 1;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Promo banners */
        .fh-promo-card {
            border-radius: var(--fh-radius);
            padding: 32px;
            color: white;
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .fh-promo-card.dark {
            background: var(--fh-primary-dark);
        }

        .fh-promo-card.light {
            background: #d4c4e8;
            color: var(--fh-text);
        }

        .fh-promo-card h4 {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .fh-promo-card p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 16px;
        }

        .fh-promo-card .promo-image {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 180px;
            height: 180px;
            border-radius: 16px;
            object-fit: cover;
        }

        /* Trust badges */
        .fh-trust-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .fh-trust-item .trust-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(124, 92, 156, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--fh-primary);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .fh-trust-item h6 {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .fh-trust-item small {
            color: var(--fh-text-muted);
            font-size: 0.85rem;
        }

        /* Info panels (forum sidebar) */
        .fh-info-panel {
            background: var(--fh-primary-dark);
            border-radius: var(--fh-radius);
            padding: 24px;
            color: white;
        }

        .fh-info-panel h5 {
            font-weight: 500;
            margin-bottom: 12px;
        }

        .fh-info-panel p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .fh-info-panel-light {
            background: white;
            border-radius: var(--fh-radius);
            border: 1px solid var(--fh-border);
            padding: 20px;
        }

        .fh-info-panel-light h6 {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--fh-text-muted);
            margin-bottom: 12px;
        }

        .fh-rule-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 0;
            font-size: 0.9rem;
        }

        .fh-rule-item i {
            color: var(--fh-success);
        }

        /* Status indicators */
        .fh-status-online {
            background: rgba(107, 158, 122, 0.15);
            color: var(--fh-success);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .fh-status-pending {
            background: rgba(212, 168, 83, 0.15);
            color: #a07820;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Table styles */
        .fh-table {
            background: white;
            border-radius: var(--fh-radius);
            overflow: hidden;
            border: 1px solid var(--fh-border);
        }

        .fh-table thead th {
            background: var(--fh-bg);
            border: none;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--fh-text-muted);
            padding: 14px 16px;
        }

        .fh-table tbody td {
            padding: 14px 16px;
            border-color: var(--fh-border);
            vertical-align: middle;
            font-size: 0.9rem;
        }

        /* Toast notifications */
        .fh-toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
        }

        .fh-toast {
            background: white;
            border-radius: 12px;
            padding: 14px 20px;
            box-shadow: var(--shadow-lg);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="fh-navbar sticky-top">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between w-100">
                <!-- Brand -->
                <a href="{{ route('home') }}" class="navbar-brand">
                    <div class="brand-icon">
                        <i class="bi bi-house-heart"></i>
                    </div>
                    <div>
                        <strong>FurHouse</strong>
                        <small>Community Market</small>
                    </div>
                </a>

                <!-- Navigation -->
                <div class="d-none d-lg-flex align-items-center gap-1">
                    <a href="{{ route('home') }}" class="fh-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
                    <a href="{{ route('products.index') }}" class="fh-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">Товары</a>
                    <a href="{{ route('masters.index') }}" class="fh-nav-link {{ request()->routeIs('masters.*') ? 'active' : '' }}">Мастера</a>
                    <a href="{{ route('events.index') }}" class="fh-nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">Встречи</a>
                    <a href="{{ route('forum.index') }}" class="fh-nav-link {{ request()->routeIs('forum.*') ? 'active' : '' }}">Форум</a>
                    <a href="{{ route('team') }}" class="fh-nav-link {{ request()->routeIs('team') ? 'active' : '' }}">Команда</a>
                </div>

                <!-- Right side -->
                <div class="d-flex align-items-center gap-2">
                    <button class="fh-btn fh-btn-icon fh-btn-ghost" title="Поиск">
                        <i class="bi bi-search"></i>
                    </button>

                    @auth
                        <div class="dropdown">
                            <button class="fh-btn fh-btn-outline dropdown-toggle" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar_url }}" alt="" class="fh-avatar me-2" style="width:28px;height:28px;">
                                {{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('support.index') }}"><i class="bi bi-life-preserver me-2"></i>Мои обращения</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Профиль</a></li>
                                @if(auth()->user()->isAdmin())
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-check me-2"></i>Админ-панель</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right me-2"></i>Выйти</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="fh-btn fh-btn-outline">Войти</a>
                        <a href="{{ route('register') }}" class="fh-btn fh-btn-primary">+ Разместить</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="fh-toast-container">
            <div class="fh-toast" id="successToast">
                <i class="bi bi-check-circle-fill text-success"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="fh-toast-container">
            <div class="fh-toast" id="errorToast">
                <i class="bi bi-x-circle-fill text-danger"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div class="fh-toast-container">
            <div class="fh-toast" id="infoToast">
                <i class="bi bi-info-circle-fill text-primary"></i>
                <span>{{ session('info') }}</span>
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
            </div>
        </div>
    @endif

    <!-- Main content -->
    <main class="fh-main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="fh-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <div class="brand-icon">
                            <i class="bi bi-house-heart"></i>
                        </div>
                        <div>
                            <strong>FurHouse</strong>
                            <small>Community Market</small>
                        </div>
                    </div>
                    <p style="font-size:0.9rem; opacity:0.8; max-width:300px;">
                        Место, где идеи, мастерство и добрые встречи находят свой дом.
                    </p>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h6>Маркетплейс</h6>
                    <a href="{{ route('products.index') }}">Товары</a>
                    <a href="{{ route('products.index') }}">Мастера</a>
                    <a href="{{ route('events.index') }}">Встречи</a>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h6>Сообщество</h6>
                    <a href="{{ route('forum.index') }}">Мини-форум</a>
                    <a href="{{ route('team') }}">Команда</a>
                    <a href="#">Изображения</a>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h6>Помощь</h6>
                    <a href="{{ route('rules') }}">Правила и безопасность</a>
                    <a href="{{ route('support.create') }}">Поддержка</a>
                    <a href="{{ route('support.index') }}">Обратная связь</a>
                </div>

                <div class="col-lg-2">
                    <h6>Мы на связи</h6>
                    <div class="social-links">
                        <a href="#" title="Telegram"><i class="bi bi-telegram"></i></a>
                        <a href="#" title="Discord"><i class="bi bi-chat-dots"></i></a>
                        <a href="#" title="Email"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
            </div>

            <div class="fh-footer-bottom d-flex justify-content-between flex-wrap">
                <div>© 2026 FurHouse. Сделано сообществом.</div>
                <div>
                    <a href="#">Конфиденциальность</a> ·
                    <a href="#">Условия</a> ·
                    <a href="#">18+</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSRF Token for AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide toasts
            setTimeout(function() {
                const toasts = document.querySelectorAll('.fh-toast');
                toasts.forEach(function(toast) {
                    toast.style.transition = 'opacity 0.5s';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 500);
                });
            }, 4000);

            // Setup AJAX headers
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) {
                const token = meta.getAttribute('content');
                if (window.axios) {
                    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
                }
            }
        });
    </script>

    @stack('scripts')
</body>
</html>