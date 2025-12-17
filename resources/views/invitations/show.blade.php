@extends('layouts.guest')

@section('content')
    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-blue-900">Shift Invitation</h1>
            <p class="text-blue-700 mt-2">{{ $invitation->facility->full_name }}</p>
        </div>

        <!-- Invitation Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 mb-8">

            <!-- Details -->
            <div class="space-y-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15a23.931 23.931 0 00-9-1.255m18-8.356A6.711 6.711 0 0012 20.25a6.711 6.711 0 01-6-8.104M7.5 11.25a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Shift Title</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $invitation->shift->title }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Date</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $invitation->shift->date }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Time</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $invitation->shift->start_time }} -
                            {{ $invitation->shift->end_time }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Expires</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $invitation->expires_at }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <form action="/api/shift-invitations/{{ $token }}/accept" method="POST" class="flex-1"
                    id="acceptForm">
                    @csrf
                    <button type="submit"
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition"
                        id="acceptBtn">
                        ✓ Accept Shift
                    </button>
                </form>

                <form action="/api/shift-invitations/{{ $token }}/reject" method="POST" class="flex-1"
                    id="rejectForm">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition"
                        id="rejectBtn">
                        ✗ Reject
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('acceptForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('acceptBtn');
            btn.disabled = true;
            btn.textContent = 'Processing...';

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer {{ $userToken }}',
                    },
                })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.message || 'Request failed');
                    }

                    return data;
                })
                .then(data => {
                    alert(data.message);
                    window.location.href = '/';
                })
                .catch(error => {
                    alert(error.message);
                    btn.disabled = false;
                    btn.textContent = '✓ Accept Shift';
                });
        });

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('rejectBtn');
            btn.disabled = true;
            btn.textContent = 'Processing...';

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer {{ $userToken }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '/';
                    } else {
                        alert(data.message);
                        btn.disabled = false;
                        btn.textContent = '✗ Reject';
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                    btn.disabled = false;
                    btn.textContent = '✗ Reject';
                });
        });
    </script>
@endsection
