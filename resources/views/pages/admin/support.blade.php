@extends('layouts.auth')
@section('title')
    Support & Complaints
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Support & Complaints</h1>
                <p class="text-gray-500 text-base">Ticketing system to handle user and facility issues</p>
            </div>
            <div>
                <button
                    class="bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                    Create Ticket
                </button>
            </div>
        </div>

        <div class="p-6 mb-6 border-2 border-red-200 bg-red-50 rounded-xl shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="white" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Escalations Requiring Attention <span
                        class="text-red-600">(2)</span></h2>
            </div>

            <div class="space-y-3">
                <div class="border border-red-200 rounded-xl bg-white p-4 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">T004</span>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Profile verification issue</h3>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        Emma Davis
                                    </span>
                                    <span
                                        class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Needs
                                        assignment</span>
                                </div>
                            </div>
                        </div>
                        <button
                            class="bg-black text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gray-900 transition-all">
                            Review
                        </button>
                    </div>
                </div>

                <div class="border border-red-200 rounded-xl bg-white p-4 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-sm">T005</span>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Account access problem</h3>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                        </svg>
                                        Riverside Clinic
                                    </span>
                                    <span
                                        class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">Critical
                                        priority</span>
                                </div>
                            </div>
                        </div>
                        <button
                            class="bg-black text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gray-900 transition-all">
                            Review
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-5 flex items-center gap-4 border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="relative flex-1">
                <input type="text" placeholder="Search by user or issue..."
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
                <option value="open">Open</option>
                <option value="resolved">Resolved</option>
            </select>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Support Tickets <span
                        class="text-gray-500 font-normal">(5)</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Ticket ID</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">User/Facility</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Issue</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Assigned Agent</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Priority</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-4 py-4 text-left text-sm font-semibold text-gray-700">Created</th>
                            <th class="px-4 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-semibold text-xs">T001</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        SJ</div>
                                    <span class="text-sm font-medium text-gray-900">Sarah Johnson</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-900">Payment delay issue</span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">Admin-1</span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                    High
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                    Open
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Sep 28, 2025
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
