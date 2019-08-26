
    <div class="main-menu menu-fixed menu-dark menu-accordion    menu-shadow " data-scroll-to-active="true">
        <div class="main-menu-content">
            <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
                <li class="nav-item">
                    <a href="{{ route('manager.dashboard') }}">
                        <i class="la la-home" ></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class=" nav-item"><a href="{{ route('manager.show.products') }}"><i class="la la-gift" ></i><span class="menu-title">Products</span></a>
                    <ul class="menu-content">
                        <li>
                            <a class="menu-item" href="{{ route('manager.show.products') }}">
                                <span>Manage Products</span>
                            </a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('manager.show.add.product') }}">
                                <span>Add Product</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="{{ route('manager.show.categories') }}"><i class="la la-sitemap" ></i><span class="menu-title">Categories</span></a>
                    <ul class="menu-content">
                        <li>
                            <a class="menu-item" href="{{ route('manager.show.categories') }}">
                                <span>Manage Categories</span>
                            </a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('manager.show.add.category') }}">
                                <span>Add Category</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class=" nav-item"><a href="{{ route('manager.show.users') }}"><i class="la la-user" ></i><span class="menu-title">Users</span></a>
                    <ul class="menu-content">
                        <li>
                            <a class="menu-item" href="{{ route('manager.show.users') }}">
                                <span>Manage Users</span>
                            </a>
                        </li>
                        <li>
                            <a class="menu-item" href="{{ route('manager.show.add.user') }}">
                                <span>Add User</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route("manager.show.settings") }}">
                        <i class="la la-cogs" ></i>
                        <span class="menu-title">Site Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("manager.show.activity-log") }}">
                        <i class="la la-server" ></i>
                        <span class="menu-title">Activity Log</span>
                    </a>
                </li>
                @if (Auth::user())
                    <li class="nav-item">
                        <a href="{{ route("manager.logout") }}">
                            <i class="la la-lock"></i>
                            <span class="menu-title">Logout</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
    