@extends('layouts.auth')
@section('title')
    Timesheet Exceptions
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-white">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 mb-1">Timesheet Exceptions</h1>
                <p class="text-sm text-gray-500">Review and approve flagged timesheet entries</p>
            </div>
        </div>

        <!-- Stats Cards -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Pending Review -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">Pending Review</span>
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-orange-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">5</div>
                <p class="text-xs text-gray-500">Awaiting approval</p>
            </div>

            <!-- High Priority -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">High Priority</span>
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">1</div>
                <p class="text-xs text-gray-500">Urgent exceptions</p>
            </div>

            <!-- Total Discrepancy -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">Total Discrepancy</span>
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-purple-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">+1.00h</div>
                <p class="text-xs text-gray-500">Hours variance</p>
            </div>

            <!-- Approved Today -->
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-700">Approved Today</span>
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">1</div>
                <p class="text-xs text-gray-500">Processed entries</p>
            </div>
        </div> --}}

        <!-- Search and Filters -->

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search credentials..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                    <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[140px]">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="expiring">Expiring Soon</option>
                    <option value="expired">Expired</option>
                </select>
                <select
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[140px]">
                    <option value="">All Severity</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High Priority</option>
                </select>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Flagged Timesheet Entries</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Staff Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Facility</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Shift Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Scheduled</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Actual</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Discrepancy</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Severity</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600">Status</th>
                          
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($shifts as $shift)
                        
                        
                        <!-- Row 1 -->
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $shift->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $shift->claimShift->user->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $shift->user->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $shift->date }}</td>
                            @php
                                $shiftStart = \Carbon\Carbon::parse($shift->start_time);
                                $shiftEnd = \Carbon\Carbon::parse($shift->end_time);
                                $claimStart = \Carbon\Carbon::parse($shift->claimShift->check_in);
                                $claimEnd = \Carbon\Carbon::parse($shift->claimShift->check_out);
                                
                                // Calculate discrepancy in hours
                                $scheduledDuration = $shiftStart->diffInMinutes($shiftEnd) / 60;
                                $claimedDuration = $claimStart->diffInMinutes($claimEnd) / 60;
                                $discrepancy = $claimedDuration - $scheduledDuration;
                                
                                // Determine severity based on discrepancy
                                if (abs($discrepancy) <= 0.25) {
                                    $severity = 'Low';
                                    $severityColor = 'bg-blue-100 text-blue-700';
                                } elseif (abs($discrepancy) <= 1) {
                                    $severity = 'Medium';
                                    $severityColor = 'bg-yellow-100 text-yellow-700';
                                } else {
                                    $severity = 'High';
                                    $severityColor = 'bg-red-100 text-red-700';
                                }
                            @endphp

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $shiftStart->format('g:i A') }} - {{ $shiftEnd->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $claimStart->format('g:i A') }} - {{ $claimEnd->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-green-600 font-medium">
                                {{ $discrepancy >= 0 ? '+' : '' }}{{ number_format($discrepancy, 2) }}h
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 {{ $severityColor }} rounded-full text-xs font-medium">
                                    {{ $severity }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1 w-fit
                                    {{ $shift->status == 6 ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                    
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        {{ $shift->status == 6 ? 'bg-blue-500' : 'bg-orange-500' }}">
                                    </span>

                                    {{ $shift->status == 5 ? 'Completed' : 'Paid' }}
                                </span>
                            </td>
                            
                        </tr>

                        @endforeach

                    </tbody>
                     <div class="px-6 py-4">
                    {{ $shifts->links() }}
                </div>
                </table>
            </div>
        </div>
    </main>
@endsection
