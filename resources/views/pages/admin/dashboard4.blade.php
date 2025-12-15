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
                    @php
                        $colorMap = [
                            'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
                            'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                            'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                            'yellow' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600'],
                            'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-600'],
                        ];

                        $iconMap = [
                            'clock' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                            'users' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>',
                            'alert' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
                            'document' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>',
                            'currency' =>
                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                        ];
                    @endphp

                    @foreach ($stats as $key => $stat)
                        @php
                            $colors = $colorMap[$stat['color']] ?? $colorMap['blue'];
                            $icon = $iconMap[$stat['icon']] ?? $iconMap['clock'];
                            $arrow = $stat['changeType'] === 'increase' ? '↑' : '↓';
                            $changeColor = $stat['changeType'] === 'increase' ? 'text-green-600' : 'text-red-600';
                        @endphp

                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-500">{{ $stat['label'] }}</span>
                                <div class="w-8 h-8 {{ $colors['bg'] }} rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 {{ $colors['text'] }}" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        {!! $icon !!}
                                    </svg>
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stat['value'] }}</div>
                            <div class="text-xs {{ $changeColor }} mt-1">
                                {{ $arrow }} {{ abs($stat['change']) }}% from last week
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Quick Actions -->
                {{-- <div class="bg-white rounded-lg p-6 border border-gray-200">
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
                </div> --}}

                <!-- Command Center: Demand vs Supply Overview -->
                <div class="bg-white rounded-lg p-6 border border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-900 mb-4">Command Center: Demand vs Supply Overview</h2>
                    <div style="height: 300px;">
                        <canvas id="demandSupplyChart"></canvas>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-red-50 rounded-lg p-6 border border-red-100">
                            <div class="text-sm text-gray-600 mb-2">Total Demand</div>
                            <div class="text-3xl font-bold text-red-600">{{ $demandSupplyData['totalDemand'] }}</div>
                            <div class="text-sm text-red-600 mt-1">shifts</div>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-6 border border-blue-100">
                            <div class="text-sm text-gray-600 mb-2">Total Supply</div>
                            <div class="text-3xl font-bold text-blue-600">{{ $demandSupplyData['totalSupply'] }}</div>
                            <div class="text-sm text-blue-600 mt-1">staff</div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-6 border border-green-100">
                            <div class="text-sm text-gray-600 mb-2">Fill Rate</div>
                            <div class="text-3xl font-bold text-green-600">{{ floor($demandSupplyData['fillRate']) }}</div>
                            <div class="text-sm text-green-600 mt-1">
                                .{{ intval(($demandSupplyData['fillRate'] - floor($demandSupplyData['fillRate'])) * 10) }}%
                            </div>
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
                        @forelse($fillRiskAlerts as $alert)
                            @php
                                $bgColor =
                                    $alert['riskColor'] === 'red'
                                        ? 'bg-red-50'
                                        : ($alert['riskColor'] === 'yellow'
                                            ? 'bg-yellow-50'
                                            : 'bg-blue-50');
                                $borderColor =
                                    $alert['riskColor'] === 'red'
                                        ? 'border-red-500'
                                        : ($alert['riskColor'] === 'yellow'
                                            ? 'border-yellow-500'
                                            : 'border-blue-500');
                            @endphp

                            <div class="p-4 {{ $bgColor }} rounded-lg border-l-4 {{ $borderColor }}">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <span
                                            class="text-sm font-semibold text-gray-900">{{ $alert['facilityName'] }}</span>
                                        <span
                                            class="ml-2 px-2 py-0.5 text-xs font-medium {{ $alert['badgeColor'] }} text-white rounded">
                                            {{ $alert['riskLevel'] }}
                                        </span>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                                <div class="text-xs text-gray-600">
                                    {{ $alert['unfilledShifts'] }} open shifts for {{ $alert['shiftDate'] }}
                                </div>
                                <div class="mt-2 bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="{{ $alert['progressBarColor'] }} h-full"
                                        style="width: {{ $alert['fillPercentage'] }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                <p class="text-sm text-green-700 font-medium">✓ All shifts are well covered!</p>
                                <p class="text-xs text-green-600 mt-1">No fill risk alerts at this time.</p>
                            </div>
                        @endforelse
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
                        <h3 class="text-sm font-semibold text-gray-900">
                            <span id="scheduleTitle">Today's Schedule</span>
                        </h3>
                        <div class="flex gap-2">
                            <button id="weeklyBtn" onclick="loadWeeklySchedule()"
                                class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Weekly
                            </button>
                            <button id="monthlyBtn" onclick="loadMonthlySchedule()"
                                class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Monthly
                            </button>
                        </div>
                    </div>

                    <div id="scheduleContainer" class="space-y-3">
                        @forelse($todaysSchedule as $item)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $item['facilityName'] }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $item['shiftTime'] }}</div>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium {{ $item['statusColor'] }} rounded capitalize">
                                    {{ $item['statusLabel'] }}
                                </span>
                            </div>
                        @empty
                            <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-blue-700 font-medium">No shifts scheduled for today</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar (1/3 width) -->
            <div class="space-y-6">
                <!-- Real-Time Alerts -->
                {{-- <div class="bg-white rounded-lg p-5 border border-gray-200">
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
                </div> --}}

                <!-- Activity Feed -->
                <div class="bg-white rounded-lg p-5 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        @forelse($recentActivity as $activity)
                            @php
                                $iconMap = [
                                    'user' =>
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
                                    'check' =>
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
                                    'document' =>
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>',
                                    'cancel' =>
                                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                                ];
                                $icon = $iconMap[$activity['icon']] ?? $iconMap['document'];
                            @endphp

                            <div class="flex items-start">
                                <div
                                    class="w-8 h-8 {{ $activity['iconBgColor'] }} rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 {{ $activity['iconColor'] }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        {!! $icon !!}
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-xs text-gray-900 font-medium">{{ $activity['title'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $activity['description'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $activity['timeAgo'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-xs text-gray-600 text-center font-medium">No recent activity</p>
                            </div>
                        @endforelse
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
                labels: {!! json_encode($demandSupplyData['labels']) !!},
                datasets: [{
                    label: 'Demand (Shifts Needed)',
                    data: {!! json_encode($demandSupplyData['demand']) !!},
                    backgroundColor: '#EF4444',
                    borderRadius: 4,
                    barThickness: 20
                }, {
                    label: 'Filled Shifts',
                    data: {!! json_encode($demandSupplyData['filled']) !!},
                    backgroundColor: '#14B8A6',
                    borderRadius: 4,
                    barThickness: 20
                }, {
                    label: 'Supply (Available Staff)',
                    data: {!! json_encode($demandSupplyData['supply']) !!},
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
                        max: {{ $demandSupplyData['maxValue'] }},
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
                labels: {!! json_encode($userGrowthTrendData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($userGrowthTrendData['data']) !!},
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
                        callbacks: {
                            label: function(context) {
                                return 'Total Users: ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {{ $userGrowthTrendData['maxValue'] }},
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
                labels: {!! json_encode($shiftDistributionData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($shiftDistributionData['data']) !!},
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
                        callbacks: {
                            label: function(context) {
                                const dataIndex = context.dataIndex;
                                const datasets = context.chart.data;
                                const value = context.parsed;

                                let shiftCount = 0;
                                if (dataIndex === 0) {
                                    shiftCount = {{ $shiftDistributionData['completedShifts'] }};
                                    return 'Completed: ' + value + '% (' + shiftCount + ' shifts)';
                                } else if (dataIndex === 1) {
                                    shiftCount = {{ $shiftDistributionData['canceledShifts'] }};
                                    return 'Canceled: ' + value + '% (' + shiftCount + ' shifts)';
                                } else {
                                    shiftCount = {{ $shiftDistributionData['pendingShifts'] }};
                                    return 'Pending: ' + value + '% (' + shiftCount + ' shifts)';
                                }
                            }
                        }
                    }
                }
            }
        });
    </script>

    <script>
        // Load weekly schedule
        async function loadWeeklySchedule() {
            try {
                const response = await fetch('/schedule/weekly');
                const data = await response.json();

                if (data.success) {
                    updateScheduleDisplay(data.scheduleItems, 'Weekly Schedule');
                    updateButtonStates('weekly');
                }
            } catch (error) {
                console.error('Error loading weekly schedule:', error);
            }
        }

        // Load monthly schedule
        async function loadMonthlySchedule() {
            try {
                const response = await fetch('/schedule/monthly');
                const data = await response.json();

                if (data.success) {
                    updateScheduleDisplay(data.scheduleItems, 'Monthly Schedule');
                    updateButtonStates('monthly');
                }
            } catch (error) {
                console.error('Error loading monthly schedule:', error);
            }
        }

        // Update schedule display with new items
        function updateScheduleDisplay(items, title) {
            const container = document.getElementById('scheduleContainer');
            const titleElement = document.getElementById('scheduleTitle');

            titleElement.textContent = title;

            let html = '';

            if (items.length === 0) {
                html =
                    '<div class="p-4 bg-blue-50 rounded-lg border border-blue-200"><p class="text-sm text-blue-700 font-medium">No shifts scheduled for this period</p></div>';
            } else {
                items.forEach(item => {
                    const dateDisplay = item.shiftDate ?
                        `<div class="text-xs text-gray-500 mt-1">${item.shiftDate} - ${item.shiftTime}</div>` :
                        `<div class="text-xs text-gray-500 mt-1">${item.shiftTime}</div>`;

                    html += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">${item.facilityName}</div>
                            ${dateDisplay}
                        </div>
                        <span class="px-2 py-1 text-xs font-medium ${item.statusColor} rounded capitalize">
                            ${item.statusLabel}
                        </span>
                    </div>
                `;
                });
            }

            container.innerHTML = html;
        }

        // Update button active states
        function updateButtonStates(activeView) {
            const weeklyBtn = document.getElementById('weeklyBtn');
            const monthlyBtn = document.getElementById('monthlyBtn');

            if (activeView === 'weekly') {
                weeklyBtn.classList.add('bg-blue-50', 'border-blue-300');
                monthlyBtn.classList.remove('bg-blue-50', 'border-blue-300');
            } else if (activeView === 'monthly') {
                monthlyBtn.classList.add('bg-blue-50', 'border-blue-300');
                weeklyBtn.classList.remove('bg-blue-50', 'border-blue-300');
            }
        }
    </script>
@endpush
