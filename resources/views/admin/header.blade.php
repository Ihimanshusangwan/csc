<nav class="bg-white p-4 shadow-md fixed w-full top-0">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-light">
            <a href="{{ route('admin.dashboard') }}" class="scrollto">
                <span><strong>DOKUMENT <span style="color:red;">GURU</span></strong></span>
            </a>
        </h1>
        <div class="block lg:hidden">
            <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-gray-700 hover:border-gray-700">
                <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <title>Menu</title>
                    <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
                </svg>
            </button>
        </div>
        <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block pt-6 lg:pt-0" id="nav-content">
            <ul class="list-reset lg:flex justify-end flex-1 items-center space-x-4">
                <li class="nav-item relative group">
                    <a class="nav-link" href="#">Manage Users</a>
                    <ul class="absolute hidden text-gray-700 pt-1 group-hover:block bg-white shadow-lg">
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.registered-agents') }}">Registered Agents</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.registered-staff') }}">Registered Staffs</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.registered-fieldboy') }}">Registered Field Boys</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.staff_managers') }}">Registered Staff Managers</a></li>
                    </ul>
                </li>
                <li class="nav-item relative group">
                    <a class="nav-link" href="#">Manage Services</a>
                    <ul class="absolute hidden text-gray-700 pt-1 group-hover:block bg-white shadow-lg">
                        <li><a class="nav-link block px-4 py-2" href="{{ route('service-groups.index') }}">Manage Service Groups</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('services.index') }}">Manage Services</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('locations.index') }}">Manage Locations</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('plans.index') }}">Manage Plans</a></li>
                    </ul>
                </li>
                <li class="nav-item relative group">
                    <a class="nav-link" href="#">History</a>
                    <ul class="absolute hidden text-gray-700 pt-1 group-hover:block bg-white shadow-lg">
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.recharge-history') }}">Recharge History</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.appointment-history') }}">Appointments</a></li>
                    </ul>
                </li>
                <li class="nav-item relative group">
                    <a class="nav-link" href="#">Register</a>
                    <ul class="absolute hidden text-gray-700 pt-1 group-hover:block bg-white shadow-lg">
                        <li><a class="nav-link block px-4 py-2" href="{{ route('staffs.create') }}">Register Staff</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('register-manager') }}">Register Staff Manager</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('fieldboy.create') }}">Register Field Boy</a></li>
                    </ul>
                </li>
                <li class="nav-item relative group">
                    <a class="nav-link" href="#">Billing</a>
                    <ul class="absolute hidden text-gray-700 pt-1 group-hover:block bg-white shadow-lg">
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.bill') }}">Create Bill</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.bill-filter') }}">View Bill</a></li>
                    </ul>
                </li>
                <li class="nav-item relative group">
                    <a class="nav-link" href="#">Tools</a>
                    <ul class="absolute hidden text-gray-700 pt-1 group-hover:block bg-white shadow-lg">
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.troubleshoot') }}">Run Troubleshooter</a></li>
                        <li><a class="nav-link block px-4 py-2" href="{{ route('admin.delete-form') }}">Clear Database</a></li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-nav">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-red-500 ml-4">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('nav-toggle').onclick = function() {
        document.getElementById("nav-content").classList.toggle("hidden");
    }
</script>
       