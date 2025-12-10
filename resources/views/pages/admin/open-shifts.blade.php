@extends('layouts.auth')
@section('title')
    Open Shifts Board
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100" x-data="{
        showModal: false,
        selectedShift: null,
        openAssignModal(shift) {
            this.selectedShift = shift;
            this.showModal = true;
        }
    }">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Open Shifts Board</h1>
                <p class="text-sm text-gray-500">Available shifts waiting for staff assignment</p>
            </div>
            <button
                class="bg-gray-900 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gray-800 transition-all duration-200 flex items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                Post New Shift
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Open -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Total Open</span>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-[#1F3C88]">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $shifts->count() }}</div>
                <p class="text-xs text-gray-500">Shifts available</p>
            </div>

            <!-- Urgent -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Urgent</span>
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $shifts->where('is_emergency', 1)->count() }}</div>
                <p class="text-xs text-gray-500">Need immediate attention</p>
            </div>

            <!-- Available Staff -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Available Staff</span>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $availableStaff }}</div>
                <p class="text-xs text-gray-500">Ready for assignment</p>
            </div>

            <!-- Avg. Rate -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Avg. Rate</span>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-purple-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">${{ $avgRate }}/hr</div>
                <p class="text-xs text-gray-500">Across all shifts</p>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search by facility, role, or department..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                    <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[140px]">
                    <option value="">All Roles</option>
                    <option value="rn">RN</option>
                    <option value="lpn">LPN</option>
                    <option value="cna">CNA</option>
                </select>
                <select
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[160px]">
                    <option value="">All Urgency</option>
                    <option value="urgent">Urgent</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>

        <!-- Shifts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            <!-- Shift Card 1 - Urgent -->
            @foreach ($shifts as $shift)
            
            
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $shift->title }}</h3>
                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ $shift->location }}
                        </div>
                    </div>
                    @if ($shift->is_emergency)
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Urgent</span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Medium</span>
                    @endif
                    
                </div>

                @php
                    $start = \Carbon\Carbon::parse($shift->start_time);
                    $end = \Carbon\Carbon::parse($shift->end_time);
                    $totalHours = abs($end->diffInHours($start));
                @endphp

                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            {{ $shift->date }}
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            {{ $totalHours }} hours
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            {{ $start->format('h:i A') }} - {{ $end->format('h:i A') }}

                        </div>
                        <div class="flex items-center gap-1 text-green-600 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            ${{ $shift->pay_per_hour }}/hr
                        </div>
                    </div>
                </div>
{{-- 
                <div class="mb-4">
                    <div class="text-xs font-medium text-gray-600 mb-2">Department:</div>
                    <span
                        class="inline-block px-3 py-1 bg-blue-50 text-[#1F3C88]/90 rounded-full text-xs font-medium">ICU</span>
                </div> --}}

                <div class="mb-4">
                    <div class="text-xs font-medium text-gray-600 mb-2">Requirements:</div>
                    <p class="text-sm text-gray-700">{{ $shift->license_type }}</p>
                </div>

                {{-- <button
                    @click="openAssignModal({
                        facility: 'St. Mary\'s Hospital',
                        role: 'Registered Nurse (RN)',
                        date: '2024-10-16',
                        time: '08:00 AM - 04:00 PM',
                        rate: '$45/hr',
                        department: 'ICU'
                    })"
                    class="w-full bg-[#1F3C88] text-white py-2.5 rounded-lg font-medium hover:bg-[#1F3C88]/90 transition-colors flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Assign Staff
                </button> --}}
            </div>

            @endforeach


        </div>

        <!-- Assign Staff Modal -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
            style="display: none;">
            <div @click.away="showModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Assign Staff to Shift</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5">
                    <!-- Shift Details -->
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Shift Details</h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Facility</div>
                                    <div class="text-sm font-medium text-gray-900" x-text="selectedShift?.facility"></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Position</div>
                                    <div class="text-sm font-medium text-gray-900" x-text="selectedShift?.role"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Date</div>
                                    <div class="text-sm font-medium text-gray-900" x-text="selectedShift?.date"></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Time</div>
                                    <div class="text-sm font-medium text-gray-900" x-text="selectedShift?.time"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Rate</div>
                                    <div class="text-sm font-semibold text-green-600" x-text="selectedShift?.rate"></div>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 mb-1">Department</div>
                                    <div class="text-sm font-medium text-gray-900" x-text="selectedShift?.department">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Select Staff Member -->
                    <div class="mb-6">
                        <label class="text-sm font-semibold text-gray-900 mb-2 block">Select Staff Member</label>
                        <select
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            <option value="">Choose available staff...</option>
                            <option value="1">John Smith - RN</option>
                            <option value="2">Sarah Johnson - LPN</option>
                            <option value="3">Michael Chen - CNA</option>
                            <option value="4">Emily Davis - RN</option>
                        </select>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3">
                        <button @click="showModal = false"
                            class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button @click="showModal = false"
                            class="flex-1 px-4 py-2.5 bg-[#1F3C88] text-white rounded-lg font-medium hover:bg-[#1F3C88]/90 transition-colors">
                            Confirm Assignment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
