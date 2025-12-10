@extends('layouts.auth')
@section('title')
    Dashboard
@endsection

@section('content')
    <main class="flex-1  p-4 lg:p-6 overflow-auto">
        <div class="">
            <div class=" text-zinc-900 text-2xl font-normal leading-7">Dashboard Overview</div>
            <div class="font-[Arial] text-[#717182] font-normal text-[16px] leading-[24px] tracking-[0px]">Welcome back!
                Here's your system
                snapshot</div>
        </div>

        <div class="my-6 flex items-center space-x-4">
            <x-outline-button title="Add Facility"
                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                ' />
            <x-outline-button title="Approve User"
                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
</svg>' />
            <x-outline-button title="Resolve Complaint"
                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
</svg>' />
            <x-outline-button title="Post Announcement"
                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
</svg>' />
        </div>
        <div class="">
            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

                <x-card title="Active Users" count="450" />
                <x-card title="Active Facilities" count="150" />
                <x-card title="Open Shifts" count="250" />
                <x-card title="Completed Shifts" count="156" />
                <x-card title="Pending Payments" count="28" />
                <x-card title="Total Revenue" count="$248,560" />
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Revenue and Fulfillment Chart -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Revenue and Fulfillment</h3>
                    <div class="flex items-end space-x-2 h-64">
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 120px; --delay: 0.1s; height: 120px;"></div>
                            <span class="text-xs text-gray-600 mt-2">17 Sun</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 100px; --delay: 0.2s; height: 100px;"></div>
                            <span class="text-xs text-gray-600 mt-2">18 Mon</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 80px; --delay: 0.3s; height: 80px;"></div>
                            <span class="text-xs text-gray-600 mt-2">19 Tue</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 140px; --delay: 0.4s; height: 140px;"></div>
                            <span class="text-xs text-gray-600 mt-2">20 Wed</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 160px; --delay: 0.5s; height: 160px;"></div>
                            <span class="text-xs text-gray-600 mt-2">21 Thu</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 180px; --delay: 0.6s; height: 180px;"></div>
                            <span class="text-xs text-gray-600 mt-2">22 Fri</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-500 w-full rounded-t"
                                style="--bar-height: 150px; --delay: 0.7s; height: 150px;"></div>
                            <span class="text-xs text-gray-600 mt-2">23 Sat</span>
                        </div>
                    </div>
                </div>

                <!-- Shift Progress Chart -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6">Shift Progress</h3>
                    <div class="flex items-end space-x-2 h-64">
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 180px; --delay: 0.1s; height: 180px;"></div>
                            <span class="text-xs text-gray-600 mt-2">17 Sun</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 120px; --delay: 0.2s; height: 120px;"></div>
                            <span class="text-xs text-gray-600 mt-2">18 Mon</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 90px; --delay: 0.3s; height: 90px;"></div>
                            <span class="text-xs text-gray-600 mt-2">19 Tue</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 140px; --delay: 0.4s; height: 140px;"></div>
                            <span class="text-xs text-gray-600 mt-2">20 Wed</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 160px; --delay: 0.5s; height: 160px;"></div>
                            <span class="text-xs text-gray-600 mt-2">21 Thu</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 170px; --delay: 0.6s; height: 170px;"></div>
                            <span class="text-xs text-gray-600 mt-2">22 Fri</span>
                        </div>
                        <div class="flex flex-col items-center flex-1">
                            <div class="chart-bar bg-blue-600 w-full rounded-t"
                                style="--bar-height: 150px; --delay: 0.7s; height: 150px;"></div>
                            <span class="text-xs text-gray-600 mt-2">23 Sat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
