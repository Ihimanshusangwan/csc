@extends('layouts.staff_manager')

@section('content')
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded p-6">
        <h2 class="text-2xl font-bold mb-6">Applications</h2>
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