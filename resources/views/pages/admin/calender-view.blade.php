@extends('layouts.auth')
@section('title')
    Shifts Management - Calendar View
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Shift Calendar View</h1>
                <p class="text-sm text-gray-500">Visual calendar showing open and filled shifts</p>
            </div>
            <div class="flex items-center justify-between space-x-3">
                <a href="?view=week"
                    class="px-4 py-2 {{ $view == 'week' ? 'bg-[#2B4A99] text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg text-sm font-medium hover:bg-gray-800 hover:text-white transition-colors">
                    Week View
                </a>
                <a href="?view=month"
                    class="px-4 py-2 {{ $view == 'month' ? 'bg-[#2B4A99] text-white' : 'bg-gray-200 text-gray-700' }} rounded-lg text-sm font-medium hover:bg-gray-800 hover:text-white transition-colors">
                    Month View
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Total Shifts</span>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $totalShifts }}</div>
                <p class="text-xs text-gray-500 mt-1">{{ $view == 'week' ? 'This Week' : 'This Month' }}</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Open Shifts</span>
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $openShifts }}</div>
                <p class="text-xs text-gray-500 mt-1">Need assignment</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-600">Filled Shifts</span>
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $filledShifts }}</div>
                <p class="text-xs text-gray-500 mt-1">Staff assigned</p>
            </div>
        </div>

        <!-- Calendar -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="?view={{ $view }}&{{ $view == 'week' ? 'week=' . $prevWeek : 'month=' . $prevMonth }}"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <h2 class="text-lg font-semibold text-gray-900">
                        @if ($view == 'week')
                            Week of {{ $weekStart->format('F d, Y') }}
                        @else
                            {{ $monthStart->format('F Y') }}
                        @endif
                    </h2>
                    <a href="?view={{ $view }}&{{ $view == 'week' ? 'week=' . $nextWeek : 'month=' . $nextMonth }}"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="flex items-center gap-2">
                    <a href="?view={{ $view }}&{{ $view == 'week' ? 'week=' . $currentWeek : 'month=' . $currentMonth }}"
                        class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">
                        Today
                    </a>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-7 gap-4">
                    @foreach (['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <div class="text-center pb-4">
                            <div class="text-sm font-semibold text-gray-700">{{ $day }}</div>
                        </div>
                    @endforeach

                    @foreach ($days as $day)
                        <div
                            class="{{ isset($day['isCurrentMonth']) && !$day['isCurrentMonth'] ? 'bg-gray-100 opacity-50' : 'bg-gray-50' }} rounded-lg p-3 {{ $view == 'month' ? 'min-h-[120px]' : 'min-h-[180px]' }} border {{ $day['date']->isToday() ? 'border-2 border-blue-500 bg-blue-50' : 'border-gray-200' }}">
                            <div
                                class="text-sm font-medium {{ $day['date']->isToday() ? 'text-blue-900 font-semibold' : 'text-gray-900' }} mb-2">
                                {{ $day['date']->format('d') }}
                            </div>

                            @foreach ($day['shifts'] as $shift)
                                <button onclick="showShiftModal({{ $shift->id }})"
                                    class="w-full @if ($shift->status == 0) bg-green-100 border-green-500 hover:bg-green-200 @elseif($shift->status == 1) bg-yellow-100 border-yellow-500 hover:bg-yellow-200 @elseif($shift->status == -1) bg-red-100 border-red-500 hover:bg-red-200 @else bg-purple-100 border-purple-500 hover:bg-purple-200 @endif border-l-4 rounded p-2 mb-2 transition-colors cursor-pointer text-left">
                                    <div class="flex items-start justify-between mb-1">
                                        <span
                                            class="text-xs font-semibold @if ($shift->status == 0) text-green-800 @elseif($shift->status == 1) text-yellow-800 @elseif($shift->status == -1) text-red-700 @else  text-purple-800 @endif">
                                            {{ Carbon\Carbon::parse($shift->start_time)->format('g:i A') }}
                                        </span>
                                    </div>
                                    @if ($view == 'week')
                                        <div class="text-xs font-medium text-gray-900">
                                            {{ $shift->user?->full_name ?? 'Unassigned' }}</div>
                                        <div
                                            class="text-xs @if ($shift->status == 0) text-green-700 @elseif($shift->status == 1) text-yellow-700 @elseif($shift->status == -1) text-red-700 @else @endif font-medium">
                                            {{ $shift->license_type ?? 'N/A' }}
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="px-6 pb-6">
                <div class="flex items-center gap-6 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-green-500 rounded"></div>
                        <span class="text-gray-700">Filled - Staff Assigned</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                        <span class="text-gray-700">Open - Need Assignment</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-red-500 rounded"></div>
                        <span class="text-gray-700">Cancelled</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Shift Details Modal -->
    <div id="shiftModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Shift Details</h3>
                <button onclick="closeShiftModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div id="shiftModalContent" class="p-6">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showShiftModal(shiftId) {
            const modal = document.getElementById('shiftModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            fetch(`/admin/shifts/${shiftId}`)
                .then(response => response.json())
                .then(data => {
                    const statusColors = {
                        0: {
                            bg: 'bg-green-100',
                            text: 'text-green-800',
                            label: 'Filled'
                        },
                        1: {
                            bg: 'bg-yellow-100',
                            text: 'text-yellow-800',
                            label: 'Open'
                        },
                        2: {
                            bg: 'bg-red-100',
                            text: 'text-red-800',
                            label: 'Cancelled'
                        }
                    };

                    const status = statusColors[data.status] || statusColors[1];

                    document.getElementById('shiftModalContent').innerHTML = `
                        <div class="space-y-6">
                            <!-- Status Badge -->
                            <div class="flex items-center justify-between">
                                <span class="px-4 py-2 ${status.bg} ${status.text} rounded-full text-sm font-semibold">
                                    ${status.label}
                                </span>
                                <span class="text-sm text-gray-500">ID: #${data.id}</span>
                            </div>

                            <!-- Worker Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3">Assigned Worker</h4>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        ${data.user ? data.user.full_name.substring(0, 2).toUpperCase() : 'NA'}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">${data.user ? data.user.full_name : 'Unassigned'}</div>
                                        <div class="text-sm text-gray-500">${data.user ? data.user.email : 'No worker assigned'}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Shift Details Grid -->
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Date</span>
                                    </div>
                                    <div class="text-base font-semibold text-gray-900">${new Date(data.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Time</span>
                                    </div>
                                    <div class="text-base font-semibold text-gray-900">${formatTime(data.start_time)} - ${formatTime(data.end_time)}</div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Pay Rate</span>
                                    </div>
                                    <div class="text-base font-semibold text-gray-900">$${parseFloat(data.pay_per_hour).toFixed(2)}/hr</div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">License Type</span>
                                    </div>
                                    <div class="text-base font-semibold text-gray-900">${data.license_type || 'N/A'}</div>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Location</span>
                                </div>
                                <div class="text-base text-gray-900">${data.location || 'No location specified'}</div>
                            </div>

                            <!-- Special Instructions -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-600">Special Instructions</span>
                                </div>
                                <div class="text-base text-gray-700">${data.special_instruction || 'No special instructions'}</div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3 pt-4 border-t border-gray-200">
                                <button onclick="window.location.href='/admin/shifts/${data.id}/edit'" 
                                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                    Edit Shift
                                </button>
                                <button onclick="deleteShift(${data.id})" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                                    Delete
                                </button>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    document.getElementById('shiftModalContent').innerHTML = `
                        <div class="text-center py-8">
                            <p class="text-red-600">Error loading shift details</p>
                        </div>
                    `;
                });
        }

        function closeShiftModal() {
            const modal = document.getElementById('shiftModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function formatTime(time) {
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour % 12 || 12;
            return `${displayHour}:${minutes} ${ampm}`;
        }

        function deleteShift(shiftId) {
            if (confirm('Are you sure you want to delete this shift?')) {
                fetch(`/admin/shifts/${shiftId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        closeShiftModal();
                        location.reload();
                    }
                });
            }
        }

        // Close modal on outside click
        document.getElementById('shiftModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeShiftModal();
            }
        });
    </script>
@endsection
