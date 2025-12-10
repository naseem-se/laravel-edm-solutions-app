@extends('layouts.auth')
@section('title')
    Shifts Management
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Shifts Management</h1>
                <p class="text-gray-500 text-base">Manage user accounts, documents, and activity</p>
            </div>
            {{-- <div>
                <button
                    class="bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Shift
                </button>
            </div> --}}
        </div>

        <div class="px-6 py-5 flex items-center gap-4 border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="relative flex-1">
                <input type="text" placeholder="Search by license_type or special_instruction or location..."
                    value="{{ request()->search }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select
                class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[180px]">
                <option value="">All Statuses</option>
                <option value="Opened">Confirmed</option>
                <option value="pending">Pending</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Search
            </button>

            <a href="{{ route('pages.shifts') }}"
                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Reset
            </a>
        </div>



        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Shifts List <span class="text-gray-500 font-normal">(
                        {{ \App\Models\Shift::count() }} )</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Facility</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Time</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Assigned User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Location</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            {{-- <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th> --}}
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($shifts as $shift)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">S{{ $shift->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                            </svg>
                                        </div>
                                        <span
                                            class="text-sm font-medium text-gray-900">{{ $shift->user?->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                        </svg>
                                        {{ $shift->date }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($shift->end_time)->format('g:i A') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($shift->claimShift && $shift->claimShift->user)
                                        @php
                                            $name = $shift->claimShift->user->full_name;
                                            $initials = collect(explode(' ', $name))
                                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                                ->take(2)
                                                ->implode('');
                                        @endphp

                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                                {{ $initials }}
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $shift->claimShift->user->full_name }}
                                            </span>
                                        </div>
                                    @endif

                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1 text-sm text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        {{ $shift->location }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($shift->status == 0)
                                        <span
                                            class="px-3 py-1 bg-[#FFEDD4] text-[#9F2D00] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#FFEDD4] rounded-full"></span>
                                            Pending
                                        </span>
                                    @endif
                                    @if ($shift->status == 1)
                                        <span
                                            class="px-3 py-1 bg-[#FEF9C2] text-[#894B00] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#FEF9C2] rounded-full"></span>
                                            Opened
                                        </span>
                                    @endif
                                    @if ($shift->status == 2)
                                        <span
                                            class="px-3 py-1 bg-[#FFEDD4] text-[#9F2D00] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#FFEDD4] rounded-full"></span>
                                            Pending Approval
                                        </span>
                                    @endif
                                    @if ($shift->status == 3)
                                        <span
                                            class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                                            Confirmed
                                        </span>
                                    @endif
                                    @if ($shift->status == 4)
                                        <span
                                            class="px-3 py-1 bg-[#DBEAFE]  text-[#193CB8] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#DBEAFE] rounded-full"></span>
                                            In Progress
                                        </span>
                                    @endif
                                    @if ($shift->status == 5)
                                        <span
                                            class="px-3 py-1 bg-[#DCFCE7]  text-[#016630] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#DCFCE7] rounded-full"></span>
                                            Completed
                                        </span>
                                    @endif
                                    @if ($shift->status == 6)
                                        <span
                                            class="px-3 py-1 bg-[#DCFCE7]  text-[#016630] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#DCFCE7] rounded-full"></span>
                                            Paid
                                        </span>
                                    @endif
                                    @if ($shift->status == -1)
                                        <span
                                            class="px-3 py-1 bg-[#FFE2E2] text-[#9F0712] rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                            <span class="w-1.5 h-1.5 bg-[#FFE2E2] rounded-full"></span>
                                            Cancelled
                                        </span>
                                    @endif
                                </td>
                                {{-- <td class="px-6 py-4 text-center">
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false"
                                            class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                            </svg>
                                        </button>

                                        <!-- Dropdown Menu -->
                                        <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                            x-transition:enter-start="transform opacity-0 scale-95"
                                            x-transition:enter-end="transform opacity-100 scale-100"
                                            x-transition:leave="transition ease-in duration-75"
                                            x-transition:leave-start="transform opacity-100 scale-100"
                                            x-transition:leave-end="transform opacity-0 scale-95"
                                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10"
                                            style="display: none;">

                                            <!-- Approve Button -->
                                            <form action="{{ route('pages.shifts.approve', ['id' => $shift->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors first:rounded-t-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                    </svg>
                                                    Approve
                                                </button>
                                            </form>

                                            <!-- Cancel Button -->
                                            <form action="{{ route('pages.shifts.cancel', ['id' => $shift->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-700 transition-colors border-t border-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                    Cancel
                                                </button>
                                            </form>

                                            <!-- Delete Button -->
                                            <form action="{{ route('pages.shifts.delete', ['id' => $shift->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this shift?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors border-t border-gray-100 last:rounded-b-lg">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="my-6 border border-gray-200 rounded-xl bg-white shadow-sm p-6">
            <h3 class="text-sm font-medium text-gray-700 mb-4">Real-time Clock Status</h3>

            @foreach ($claimedShifts as $shift)
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex items-start justify-between mb-2">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $shift->user->full_name }} - {{ $shift->shift->location ?? 'Unknown Location' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Clocked in at {{ \Carbon\Carbon::parse($shift->check_in)->format('h:i A') }}
                                —
                                <span class="text-blue-600 font-medium" id="duration-{{ $shift->id }}"
                                    data-checkin="{{ \Carbon\Carbon::parse($shift->check_in) }}">
                                    {{ $shift->duration }}
                                </span>
                            </p>

                            @if ($shift->status == 'Awaiting Checkout')
                                <p class="text-xs text-red-500 mt-1">
                                    Shift ended {{ $shift->overtime }} ago
                                </p>
                            @endif
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 {{ $shift->status == 'In Progress' ? 'bg-blue-600' : 'bg-red-600' }} text-white rounded-md text-xs font-medium whitespace-nowrap">
                        {{ $shift->status }}
                    </span>
                </div>
            @endforeach
        </div>

    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(() => {
                document.querySelectorAll('[id^="duration-"]').forEach(el => {
                    let checkInTime = el.dataset.checkin;
                    if (!checkInTime) return;

                    const checkIn = new Date(checkInTime);
                    const now = new Date();
                    const diffMs = now - checkIn;

                    const hours = Math.floor(diffMs / (1000 * 60 * 60));
                    const mins = Math.floor((diffMs / (1000 * 60)) % 60);

                    el.innerText = `${hours}h ${mins}m`;
                });
            }, 60000); // update every 60 seconds
        });
    </script>
@endpush
