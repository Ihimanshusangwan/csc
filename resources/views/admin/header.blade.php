<nav class="bg-white p-4 shadow-md fixed w-full top-0">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-light">
            <a href="{{ route('admin.dashboard') }}" class="scrollto">
                <span><strong>DOKUMENT <span style="color:red;">GURU</span></strong></span>
            </a>
        </h1>
        <a href="{{ route('admin.dashboard') }}" >Home</a>
        <div class="navbar-nav">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link btn btn-link text-red-500 ml-4">Logout</button>
            </form>
        </div>
    </div>
    </div>
</nav>