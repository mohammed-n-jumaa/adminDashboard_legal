<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <!-- Dashboard -->
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
  
        <!-- Manage Users -->
        <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users menu-icon"></i>
                <span class="menu-title">Manage Users</span>
            </a>
        </li>
  
        <!-- Manage Lawyers -->
        <li class="nav-item {{ request()->routeIs('admin.lawyers.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.lawyers.index') }}">
                <i class="fas fa-gavel menu-icon"></i>
                <span class="menu-title">Manage Lawyers</span>
            </a>
        </li>
  
        <!-- Manage Consultations -->
        <li class="nav-item {{ request()->routeIs('admin.consultations.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.consultations.index') }}">
                <i class="fas fa-comment-alt menu-icon"></i>
                <span class="menu-title">Manage Consultations</span>
            </a>
        </li>
  
        <!-- Manage Comments -->
        <li class="nav-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.comments.index') }}">
                <i class="fas fa-comments menu-icon"></i>
                <span class="menu-title">Manage Comments</span>
            </a>
        </li>
  
        <!-- Manage Category -->
        <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-list menu-icon"></i>
                <span class="menu-title">Manage Categories</span>
            </a>
        </li>
  
        <!-- Manage Subscriptions -->
        <li class="nav-item {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.subscriptions.index') }}">
                <i class="fas fa-credit-card menu-icon"></i>
                <span class="menu-title">Manage Subscriptions</span>
            </a>
        </li>
    </ul>
  </nav>
  