@extends('layouts.staff_manager')

@section('content')
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded p-6">
        <h2 class="text-2xl font-bold mb-6">Applications</h2>

      <!-- Search Form -->
<form method="GET" action="{{ route('staff_manager.dashboard') }}" class="mb-6">
    <div class="flex items-center space-x-4">
        <div class="w-1/3">
            <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
            <input type="text" name="customer_name" id="customer_name" value="{{ request('customer_name') }}" placeholder="Enter customer name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg">
        </div>
        <div class="w-1/3">
            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" name="date" id="date" value="{{ request('date') }}" placeholder="Select date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg">
        </div>
        <div class="w-1/3 flex items-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Search</button>
        </div>
    </div>
</form>


        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Customer Name</th>
                    <th class="py-2 px-4 border-b">Date of Application</th>
                    <th class="py-2 px-4 border-b">Service Name</th>
                    <th class="py-2 px-4 border-b">Delivery Date</th>
                    <th class="py-2 px-4 border-b">Assigned Staff</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Action(Change Staff)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($applications as $application)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $application->customer_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $application->created_at }}</td>
                        <td class="py-2 px-4 border-b">{{ $application->service_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $application->delivery_date ?? 'Not Yet Determined' }}</td>
                        <td class="py-2 px-4 border-b">{{ $application->staff_name }}</td>
                        <td class="py-2 px-4 border-b">
                            @php
                                $statusText = '';
                                switch ($application->status) {
                                    case -1:
                                        $statusText = 'Rejected';
                                        break;
                                    case 0:
                                        $statusText = 'Initiated';
                                        break;
                                    case 1:
                                        $statusText = 'In Progress';
                                        break;
                                    case 2:
                                        $statusText = 'Completed';
                                        break;
                                    default:
                                        $statusesArray = explode(',', $application->statuses);
                                        foreach ($statusesArray as $status) {
                                            $statusParts = explode(':', $status);
                                            if ($statusParts[0] == $application->status) {
                                                $statusText = $statusParts[1] ?? '';
                                                break;
                                            }
                                        }
                                        break;
                                }
                            @endphp
                            {{ $statusText }}
                        </td>
                        <td class="py-2 px-4 border-b">
                            <form method="POST" action="{{ route('staff_manager.update_staff') }}">
                                @csrf
                                <input type="hidden" name="application_id" value="{{ $application->id }}">
                                <select name="staff_id" class="border rounded p-2">
                                    @foreach ($staff as $staff_member)
                                        <option value="{{ $staff_member->id }}" {{ $staff_member->id == $application->staff_id ? 'selected' : '' }}>
                                            {{ $staff_member->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $applications->links() }}
        </div>
    </div>
@endsection