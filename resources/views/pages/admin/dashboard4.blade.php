@extends('layouts.auth')
@section('title')
    Dashboard
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gray-50">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Overview</h1>
            <p class="text-sm text-gray-500 mt-1">Welcome back! Here's your system snapshot</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Content (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Top Stats Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Shifts Today -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">Shifts Today</span>
                            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">32</div>
                        <div class="text-xs text-green-600 mt-1">↑ 8% from last week</div>
                    </div>

                    <!-- Active Staff -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">Active Staff</span>
                            <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">450</div>
                        <div class="text-xs text-green-600 mt-1">↑ 12% from last week</div>
                    </div>

                    <!-- Expiring Credentials -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">Expiring Credentials</span>
                            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">12</div>
                        <div class="text-xs text-green-600 mt-1">↓ 2% from last week</div>
                    </div>

                    <!-- Pending Timesheets -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">Pending Timesheets</span>
                            <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">28</div>
                        <div class="text-xs text-green-600 mt-1">↓ 5% from last week</div>
                    </div>

                    <!-- Revenue MTD -->
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-gray-500">Rev MTD</span>
                            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">$248.5K</div>
                        <div class="text-xs text-green-600 mt-1">↑ 18% from last month</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <button
                            class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Post Shift
                        </button>
                        <button
                            class="bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Approve Timesheets
                        </button>
                        <button
                            class="bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Upload Credential
                        </button>
                        <button
                            class="bg-orange-600 hover:bg-orange-700 text-white py-3 px-4 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Generate Invoice
                        </button>
                    </div>
                </div>

                <!-- Command Center: Demand vs Supply Overview -->
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Command Center: Demand vs Supply Overview</h2>
                    <div style="height: 300px;">
                        <canvas id="demandSupplyChart"></canvas>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-red-50 rounded-lg p-6 border border-red-100">
                            <div class="text-sm text-gray-600 mb-2">Total Demand</div>
                            <div class="text-3xl font-bold text-red-600">340</div>
                            <div class="text-sm text-red-600 mt-1">shifts</div>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                            <div class="text-sm text-gray-600 mb-2">Total Supply</div>
                            <div class="text-3xl font-bold text-blue-600">340</div>
                            <div class="text-sm text-blue-600 mt-1">staff</div>
                        </div>

                        <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                            <div class="text-sm text-gray-600 mb-2">Fill Rate</div>
                            <div class="text-3xl font-bold text-green-600">100</div>
                            <div class="text-sm text-green-600 mt-1">.0%</div>
                        </div>
                    </div>
                </div>

                <!-- Fill Risk Alerts -->
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <h2 class="text-sm font-semibold text-gray-900">Fill Risk Alerts</h2>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <span class="text-sm font-semibold text-gray-900">St. Mary's Hospital</span>
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-red-600 text-white rounded">HIGH
                                        RISK</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <div class="text-xs text-gray-600">8 open shifts for 2024-10-17 in</div>
                            <div class="mt-2 bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-red-600 h-full" style="width: 95%"></div>
                            </div>
                        </div>

                        <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <span class="text-sm font-semibold text-gray-900">General Medical Center</span>
                                    <span
                                        class="ml-2 px-2 py-0.5 text-xs font-medium bg-yellow-600 text-white rounded">MEDIUM
                                        RISK</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <div class="text-xs text-gray-600">5 open shifts for 2024-10-18 in</div>
                            <div class="mt-2 bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-yellow-500 h-full" style="width: 70%"></div>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <span class="text-sm font-semibold text-gray-900">City Hospital</span>
                                    <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-600 text-white rounded">LOW
                                        RISK</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                            <div class="text-xs text-gray-600">3 open shifts for 2024-10-19 in General</div>
                            <div class="mt-2 bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-blue-500 h-full" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Growth Trend -->
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">User Growth Trend</h3>
                        <div style="height: 200px;">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>

                    <!-- Shift Distribution -->
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Shift Distribution</h3>
                        <div style="height: 200px;">
                            <canvas id="shiftDistributionChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-900">Today's Schedule</h3>
                        <div class="flex gap-2">
                            <button
                                class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Weekly</button>
                            <button
                                class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Monthly</button>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">Morning Shift - St. Mary's Hospital</div>
                                <div class="text-xs text-gray-500 mt-1">08:00 AM</div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-gray-900 text-white rounded">open</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">Evening Shift - General Medical Center</div>
                                <div class="text-xs text-gray-500 mt-1">04:00 PM</div>
                            </div>
                            <span
                                class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded">pending</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">Night Shift - City Clinic</div>
                                <div class="text-xs text-gray-500 mt-1">10:00 PM</div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-gray-900 text-white rounded">open</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">Day Shift - County Hospital</div>
                                <div class="text-xs text-gray-500 mt-1">07:00 AM</div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded">urgent</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-900">Afternoon Shift - Care Home</div>
                                <div class="text-xs text-gray-500 mt-1">12:00 PM</div>
                            </div>
                            <span class="px-2 py-1 text-xs font-medium bg-gray-900 text-white rounded">open</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (1/3 width) -->
            <div class="space-y-6">
                <!-- Real-Time Alerts -->
                <div class="bg-white rounded-lg p-5 border border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-900">Real-Time Alerts</h3>
                        <button class="text-xs text-blue-600 hover:text-blue-700">×</button>
                    </div>
                    <div class="space-y-3">
                        <div class="p-3 bg-red-50 rounded-lg border border-red-100">
                            <div class="flex items-start">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-red-600 text-white rounded mr-2 mt-0.5">HIGH</span>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-900">License Expired</div>
                                    <div class="text-xs text-gray-600 mt-1">Dr. John is Q. Bay's Hospital in 2 hours</div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-100">
                            <div class="flex items-start">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-yellow-600 text-white rounded mr-2 mt-0.5">MEDIUM</span>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-900">Credential Expiring</div>
                                    <div class="text-xs text-gray-600 mt-1">5 staff have expiry credential within 30 days
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-start">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-gray-900 text-white rounded mr-2 mt-0.5">LOW</span>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-900">Timesheet Pending</div>
                                    <div class="text-xs text-gray-600 mt-1">15 timesheet pending approval for last week
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <div class="flex items-start">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-gray-900 text-white rounded mr-2 mt-0.5">LOW</span>
                                <div class="flex-1">
                                    <div class="text-xs font-medium text-gray-900">New Hire Assigned</div>
                                    <div class="text-xs text-gray-600 mt-1">3 new staff onboarded this week</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Feed or Additional Widget -->
                <div class="bg-white rounded-lg p-5 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-xs text-gray-900 font-medium">New staff registered</p>
                                <p class="text-xs text-gray-500 mt-1">Sarah Johnson joined as RN</p>
                                <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-xs text-gray-900 font-medium">Shift filled</p>
                                <p class="text-xs text-gray-500 mt-1">Evening shift at City Hospital</p>
                                <p class="text-xs text-gray-400 mt-1">3 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-xs text-gray-900 font-medium">Invoice generated</p>
                                <p class="text-xs text-gray-500 mt-1">Invoice #5789 for $12,450</p>
                                <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Command Center: Demand vs Supply Chart
        const demandSupplyCtx = document.getElementById('demandSupplyChart').getContext('2d');
        new Chart(demandSupplyCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Demand (Shifts Needed)',
                    data: [40, 45, 42, 48, 52, 45, 38],
                    backgroundColor: '#EF4444',
                    borderRadius: 4,
                    barThickness: 20
                }, {
                    label: 'Filled Shifts',
                    data: [45, 42, 43, 43, 43, 40, 38],
                    backgroundColor: '#14B8A6',
                    borderRadius: 4,
                    barThickness: 20
                }, {
                    label: 'Supply (Available Staff)',
                    data: [43, 45, 44, 45, 48, 43, 40],
                    backgroundColor: '#3B82F6',
                    borderRadius: 4,
                    barThickness: 20
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 60,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // User Growth Trend Chart
        const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
        new Chart(userGrowthCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    data: [120, 185, 265, 310, 425, 520],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointBackgroundColor: '#3B82F6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Shift Distribution Chart
        const shiftDistributionCtx = document.getElementById('shiftDistributionChart').getContext('2d');
        new Chart(shiftDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed 70%', 'Canceled 10%', 'Pending 20%'],
                datasets: [{
                    data: [70, 10, 20],
                    backgroundColor: [
                        '#10B981',
                        '#EF4444',
                        '#F59E0B'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
