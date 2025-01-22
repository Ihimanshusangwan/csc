<nav class="bg-white p-4">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">
            <h1 class="text-light">
                <a href="{{ route('home') }}" class="scrollto">
                    <span><strong>DOKUMENT <span style="color:red;">GURU</span></strong></span>
                </a>
            </h1>
            <div>
                <a href="{{ route('staff_manager.dashboard') }}" >Home</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500 ml-4">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>