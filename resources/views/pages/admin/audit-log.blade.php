@extends('layouts.auth')
@section('title')
    Audit Logs
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Audit Logs</h1>
                <p class="text-gray-500 text-base">Track all admin actions, edits, deletions, and logins</p>
            </div>
            <div>
                <button
                    class="bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Logs
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Actions Today -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-gray-600">Total Actions Today</span>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalActionsToday }}</div>
                <div class="text-xs text-gray-500">Last 24 hours</div>
            </div>

            <!-- Logins -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-gray-600">Logins</span>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $logins }}</div>
                <div class="text-xs text-gray-500">Successful logins</div>
            </div>

            <!-- Edits -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-gray-600">Edits</span>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $edits }}</div>
                <div class="text-xs text-gray-500">Recent modifications</div>
            </div>

            <!-- Deletions -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-semibold text-gray-600">Deletions</span>
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $deletions }}</div>
                <div class="text-xs text-gray-500">Items deleted</div>
            </div>
        </div>

        <!-- Activity Log Table -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">Activity Log</h2>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <input type="text" placeholder="Search logs..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent">
                        </div>
                        <select
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent">
                            <option>All Actions</option>
                            <option>Login</option>
                            <option>Edit</option>
                            <option>Delete</option>
                        </select>
                        <select
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-black focus:border-transparent">
                            <option>All Status</option>
                            <option>Success</option>
                            <option>Failed</option>
                            <option>Pending</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Timestamp</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">User</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Action</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Target</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">IP Address</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Details</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <!-- Timestamp -->
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-900">
                                        {{ $log->created_at }}
                                    </span>
                                </td>

                                <!-- User -->
                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-gray-900">
                                        {{ $log->causer->full_name ?? 'System' }}
                                    </span>
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 
                                        @if(str_contains(strtolower($log->log_name), 'login'))
                                            bg-green-100 text-green-700 
                                        @elseif(str_contains(strtolower($log->log_name), 'edit') || $log->event === 'updated')
                                            bg-orange-100 text-orange-700
                                        @else
                                            bg-blue-100 text-blue-700
                                        @endif
                                        rounded-full text-xs font-medium flex items-center gap-1.5 w-fit">

                                        {{ ucfirst($log->log_name) }}
                                    </span>
                                </td>

                                <!-- Target Model -->
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600">
                                        {{ class_basename($log->subject_type ?? "Unknown" ) }} 
                                        {{-- @if($log->subject) : {{ $log->subject->id }} @endif --}}
                                    </span>
                                </td>

                                <!-- IP -->
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 font-mono">
                                        {{ $log->properties['ip'] ?? 'N/A' }}
                                    </span>
                                </td>

                                <!-- Status (Always Success unless error logged) -->
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Success
                                    </span>
                                </td>

                                <!-- Details -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if(isset($log->properties['attributes']) && is_array($log->properties['attributes']))
                                            @foreach($log->properties['attributes'] as $key => $value)
                                                <div>
                                                    <strong class="text-gray-700">{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                                    <span>{{ $value }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span>{{ $log->description }}</span>
                                        @endif
                                    </div>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-6 text-gray-500">
                                    No activity logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="px-6 py-4">
                    {{ $logs->links() }}
                </div>
            </div>

        </div>
    </main>
@endsection
