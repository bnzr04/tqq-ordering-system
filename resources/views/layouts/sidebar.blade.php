<style>
    .sidebar {
        width: 200px;
        min-height: 100%;
        background-color: #4d4d4d;
        position: fixed;
    }

    ul {
        padding: 0;
    }

    li {
        list-style: none;
        margin: 0;
    }

    li a {
        text-decoration: none;
        color: white;
        display: block;
        /* border: white 1px solid; */
        padding: 10px 20px;
        letter-spacing: 2px;
    }

    li>a:hover {
        background-color: gray;
    }
</style>
<aside class="sidebar navbar-dark">
    <nav>
        <ul>
            @if(auth()->user()->type == 'admin')
            <li><a href="{{ route('dashboard.admin') }}">Dashboard</a></li>
            <li><a href="#">Users</a></li>
            <li><a href="{{ route('menu.admin') }}">Menu</a></li>
            <li><a href="{{ route('orders.admin') }}">Orders</a></li>
            <li><a href="{{ route('kitchen.admin') }}">Kitchen</a></li>
            <li><a href="#">Inventory</a></li>
            <li><a href="#">Sales</a></li>
            <li><a href="{{ route('logs.admin') }}">Logs</a></li>
            @endif

            @if(auth()->user()->type == 'cashier')
            <li><a href="{{ route('dashboard.cashier') }}">Dashboard</a></li>
            <li><a href="#">Menu</a></li>
            <li><a href="#">Orders</a></li>
            <li><a href="#">Kitchen</a></li>
            <li><a href="#">Inventory</a></li>
            <li><a href="#">Sales</a></li>
            @endif


            @if(Auth::user())
            <li>
                <a style="letter-spacing: 2px;" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            @endif
        </ul>
    </nav>
</aside>