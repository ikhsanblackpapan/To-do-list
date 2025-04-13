<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background-color: #5DADEC;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
        }
        .content {
            margin-left: 270px;
            padding: 20px;
        }
        .menu-item {
            padding: 10px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .menu-item:hover, .menu-item.active {
            background-color: white;
            color: #5DADEC;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-white">Menu</h4>
        <a href="{{ route('tasks.index') }}" class="menu-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
            <i class="bi bi-calendar-week"></i> Hari ini
        </a>
        <a href="{{ route('tasks.priority') }}" class="menu-item {{ request()->routeIs('tasks.priority') ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i> Prioritas
        </a>
        <a href="{{ route('tasks.completed') }}" class="menu-item {{ request()->routeIs('tasks.completed') ? 'active' : '' }}">
            <i class="bi bi-check2-circle"></i> Tugas Selesai
        </a>
    </div>

    <!-- Content -->
    <div class="content">
        @yield('content')
    </div>

</body>
</html>
