@extends('layouts.auth')
@section('title')
    Report Management
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Reports & Analytics</h1>
                <p class="text-gray-500 text-base">Insights on growth, revenue, and activity</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    class="bg-white text-gray-700 border-2 border-gray-300 px-6 py-2 rounded-xl font-semibold hover:bg-gray-50 hover:border-gray-400 transform hover:scale-105 transition-all duration-200 shadow-sm hover:shadow-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export CSV
                </button>
                <button
                    class="bg-black text-white px-6 py-2 rounded-xl font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="white" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Total Users</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="text-4xl font-bold mb-2 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    450</div>
                <div class="text-sm text-gray-600">+15.5% vs last month</div>
            </div>

            <!-- Shift Fill Rate Card -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Shift Fill Rate</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="text-4xl font-bold mb-2 bg-gradient-to-r from-green-600 to-teal-600 bg-clip-text text-transparent">
                    92.5%</div>
                <div class="text-sm text-gray-600">+2.3% vs last month</div>
            </div>

            <!-- Cancellation Rate Card -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Cancellation Rate</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="text-4xl font-bold mb-2 bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                    5.2%</div>
                <div class="text-sm text-gray-600">-1.1% vs last month</div>
            </div>

            <!-- Total Revenue Card -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Total Revenue</h3>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </div>
                </div>
                <div
                    class="text-4xl font-bold mb-2 bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                    $602K</div>
                <div class="text-sm text-gray-600">+18.2% vs last month</div>
            </div>
        </div>

        <!-- Chart Containers -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">User Growth Trend</h2>
            </div>
            <div class="p-6">
                <div class="relative" style="height: 400px;">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Facility Usage Statistics</h2>
            </div>
            <div class="p-6">
                <div class="relative" style="height: 400px;">
                    <canvas id="facilityChart"></canvas>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Revenue & Payouts Comparison</h2>
            </div>
            <div class="p-6">
                <div class="relative" style="height: 400px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Key Takeaways</h2>
            </div>
            <div class="p-6">
                <ul class="space-y-3">
                    <li class="flex items-start gap-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">User base grew by <span class="font-semibold">15.5%</span> this
                            month, with consistent new user acquisition</p>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-green-50 rounded-lg border border-green-100">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">Shift fill rate remains strong at <span
                                class="font-semibold">92.5%</span>, indicating high platform utilization</p>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-orange-50 rounded-lg border border-orange-100">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">Cancellation rate decreased to <span
                                class="font-semibold">5.2%</span>, showing improved commitment from both parties</p>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-purple-50 rounded-lg border border-purple-100">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">Revenue increased by <span class="font-semibold">18.2%</span>
                            compared to last month, driven by increased facility usage</p>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-4 h-4 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700">General Hospital and Sunset Care Home are the most active
                            facilities</p>
                    </li>
                </ul>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('userGrowthChart').getContext('2d');

        const userGrowthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                        label: 'Total Users',
                        data: [133, 175, 233, 300, 367, 450],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'New Users',
                        data: [50, 67, 58, 67, 67, 67],
                        borderColor: '#14b8a6',
                        backgroundColor: 'rgba(20, 184, 166, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#14b8a6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' users';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 600,
                        ticks: {
                            stepSize: 150,
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        const ctx1 = document.getElementById('facilityChart').getContext('2d');

        const facilityChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['General Hospital', 'City Medical', 'Skilled Care', 'Metro Health'],
                datasets: [{
                        label: 'Shifts Posted',
                        data: [45, 37, 57, 30],
                        backgroundColor: '#3b82f6',
                        borderColor: '#2563eb',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Shifts Filled',
                        data: [40, 34, 52, 27],
                        backgroundColor: '#14b8a6',
                        borderColor: '#0d9488',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Shifts Cancelled',
                        data: [5, 3, 5, 3],
                        backgroundColor: '#ef4444',
                        borderColor: '#dc2626',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' shifts';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 60,
                        ticks: {
                            stepSize: 15,
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 12
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('revenueChart').getContext('2d');

        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                        label: 'Revenue',
                        data: [5500, 7000, 8500, 10500, 13000, 15500],
                        borderColor: '#14b8a6',
                        backgroundColor: 'rgba(20, 184, 166, 0)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#14b8a6',
                        pointBorderColor: '#14b8a6',
                        pointBorderWidth: 2
                    },
                    {
                        label: 'Payouts',
                        data: [5000, 6500, 8000, 9500, 12000, 14000],
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#f59e0b',
                        pointBorderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 16000,
                        ticks: {
                            stepSize: 4000,
                            font: {
                                size: 11
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
