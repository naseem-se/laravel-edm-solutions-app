@extends('layouts.auth')
@section('title')
    Dashboard
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <h1 class="text-3xl font-bold mb-8 text-black">Dashboard</h1>

        <!-- Top Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Shifts Today -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Shifts Today</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div
                    class="text-5xl font-bold mb-2 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    124</div>
                <div class="flex gap-4 text-sm text-gray-600 mt-3">
                    <span class="flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>110
                        filled</span>
                    <span class="flex items-center"><span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>14
                        unfilled</span>
                </div>
            </div>

            <!-- Active Staff -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Active Staff</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div
                    class="text-5xl font-bold mb-2 bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    560</div>
                <div class="text-sm text-gray-600 mt-3">
                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">24 Pending
                        Approval</span>
                </div>
            </div>

            <!-- Expiring Credentials -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Expiring Credentials</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div
                    class="text-5xl font-bold mb-2 bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    15</div>
                <div class="text-sm text-gray-600 mt-3">within 30 days</div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <button
                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Post a Shift
                </span>
            </button>
            <button
                class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Approve Timesheets
                </span>
            </button>
            <button
                class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-green-700 hover:to-teal-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                        </path>
                    </svg>
                    Upload Credential
                </span>
            </button>
        </div>

        <!-- Charts Row -->
        <div class="grid  grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Shift Fill Rate -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg">
                <h3 class="text-base font-semibold mb-4 text-gray-800">Shift Fill Rate</h3>
                <div style="height: 200px;">
                    <canvas id="fillRateChart"></canvas>
                </div>
                <div class="text-sm text-gray-500 mt-3 flex items-center">
                    <span class="w-3 h-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mr-2"></span>
                    Weekly Performance
                </div>
            </div>

            <!-- Revenue vs. Payroll -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg">
                <h3 class="text-base font-semibold mb-4 text-gray-800">Revenue vs. Payroll</h3>
                <div style="height: 200px;">
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="text-sm text-gray-500 mt-3">Quarterly Overview</div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="grid  grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Real-Time Alerts -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg">
                <h3 class="text-base font-semibold mb-4 text-gray-800 flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                    Real-Time Alerts
                </h3>
                <ul class="space-y-3">
                    <li
                        class="flex items-start p-3 bg-red-50 rounded-lg border border-red-100 hover:bg-red-100 transition-colors">
                        <span class="text-red-500 mr-3 mt-1">⚠️</span>
                        <span class="text-sm text-gray-700">Nurse Jane Doe license expiring in 6 days</span>
                    </li>
                    <li
                        class="flex items-start p-3 bg-orange-50 rounded-lg border border-orange-100 hover:bg-orange-100 transition-colors">
                        <span class="text-orange-500 mr-3 mt-1">⚠️</span>
                        <span class="text-sm text-gray-700">Facility ABC has 3 unfilled shifts for tonight</span>
                    </li>
                    <li
                        class="flex items-start p-3 bg-yellow-50 rounded-lg border border-yellow-100 hover:bg-yellow-100 transition-colors">
                        <span class="text-yellow-600 mr-3 mt-1">⚠️</span>
                        <span class="text-sm text-gray-700">Invoice #5567 is overdue by 7 days</span>
                    </li>
                </ul>
            </div>

            <!-- Staff Utilization -->
            <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg">
                <h3 class="text-base font-semibold mb-4 text-gray-800">Staff Utilization</h3>
                <div style="height: 150px;">
                    <canvas id="utilizationChart"></canvas>
                </div>
                <div class="text-sm text-gray-500 text-center mt-4">
                    <div class="flex justify-center gap-4">
                        <span class="flex items-center"><span
                                class="w-3 h-3 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full mr-2"></span>Assigned
                            (75%)</span>
                        <span class="flex items-center"><span
                                class="w-3 h-3 bg-gray-300 rounded-full mr-2"></span>Available (25%)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg">
            <h3 class="text-base font-semibold mb-4 text-gray-800">Calendar</h3>
            <div class="grid grid-cols-7 gap-2">
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Sun</div>
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Mon</div>
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Tue</div>
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Wed</div>
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Thu</div>
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Fri</div>
                <div class="text-center text-sm font-semibold py-2 text-gray-700">Sat</div>
                <div class="border border-gray-200 rounded-lg p-3 h-20 hover:bg-gray-50 transition-colors cursor-pointer">
                </div>
                <div class="border border-gray-200 rounded-lg p-3 h-20 hover:bg-gray-50 transition-colors cursor-pointer">
                </div>
                <div
                    class="border border-gray-200 rounded-lg p-3 h-20 bg-gradient-to-br from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 transition-colors cursor-pointer">
                    <div class="w-16 h-2 rounded-full mx-auto bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                </div>
                <div class="border border-gray-200 rounded-lg p-3 h-20 hover:bg-gray-50 transition-colors cursor-pointer">
                </div>
                <div class="border border-gray-200 rounded-lg p-3 h-20 hover:bg-gray-50 transition-colors cursor-pointer">
                </div>
                <div class="border border-gray-200 rounded-lg p-3 h-20 hover:bg-gray-50 transition-colors cursor-pointer">
                </div>
                <div class="border border-gray-200 rounded-lg p-3 h-20 hover:bg-gray-50 transition-colors cursor-pointer">
                </div>
            </div>
        </div>

    </main>
@endsection
@push('scripts')
    <script>
        // Shift Fill Rate Chart
        const fillRateCtx = document.getElementById('fillRateChart').getContext('2d');
        new Chart(fillRateCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    data: [65, 75, 72, 85],
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 3,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
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
                        max: 100,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Revenue vs Payroll Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    data: [45000, 52000, 58000, 62000],
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.8)',
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(129, 140, 248, 0.8)',
                        'rgba(165, 180, 252, 0.8)'
                    ],
                    borderRadius: 8,
                    borderWidth: 0
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
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Staff Utilization Chart
        const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
        new Chart(utilizationCtx, {
            type: 'doughnut',
            data: {
                labels: ['Assigned', 'Available'],
                datasets: [{
                    data: [75, 25],
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.8)',
                        'rgba(229, 231, 235, 0.8)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
