@extends('layouts.admin')

@section('content')
    <div class="bg-white shadow-md rounded mt-16 p-4"> 
        <h2 class="text-2xl font-bold mb-6">Registered Staff Managers</h2>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach 
                </ul>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">ID</th>
                        <th class="py-2 px-4 border-b text-left">Name</th>
                        <th class="py-2 px-4 border-b text-left">Username</th>
                        <th class="py-2 px-4 border-b text-left">Action ( Update Password)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($staffManagers as $staffManager)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $staffManager->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $staffManager->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $staffManager->username }}</td>
                            <td class="py-2 px-4 border-b">
                                <form method="POST" action="{{ route('admin.reset_password', $staffManager->id) }}">
                                    @csrf
                                    <input type="password" name="new_password" placeholder="New Password" class="border p-2 rounded mb-2 w-full" required>
                                    <input type="password" name="new_password_confirmation" placeholder="Confirm New Password" class="border p-2 rounded mb-2 w-full" required>
                                    <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Update Password</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection