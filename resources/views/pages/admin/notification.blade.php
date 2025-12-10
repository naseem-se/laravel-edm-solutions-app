@extends('layouts.auth')
@section('title')
    Notifications Management
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Notifications Management</h1>
                <p class="text-gray-500 text-base">Send and manage system notifications to users</p>
            </div>
            <div>
                <button onclick="openModal()"
                    class="bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Send New Notification
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Push Notification -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Push Notification</h3>
                <p class="text-sm text-gray-600">Instant mobile alert</p>
            </div>

            <!-- Email Alert -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Alert</h3>
                <p class="text-sm text-gray-600">Detailed email message</p>
            </div>

            <!-- SMS Alert -->
            <div
                class="bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-600 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">SMS Alert</h3>
                <p class="text-sm text-gray-600">Quick text message</p>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Recent Notifications <span
                        class="text-gray-500 font-normal">(5)</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Recipients</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Type</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sent Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Delivery Rate</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-semibold text-xs">N001</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">Welcome to the Platform</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">All
                                    Users</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">Push</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Sep 28, 2025
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[80px]">
                                        <div class="bg-gradient-to-r from-green-500 to-teal-600 h-2 rounded-full"
                                            style="width: 90%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">90%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                    Sent
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Scheduled Announcements -->
        <div class="p-6 border border-gray-200 bg-white rounded-xl shadow-sm mb-6">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-800">Scheduled System Announcements</h2>
            </div>

            <div class="space-y-4">
                <div
                    class="border border-gray-200 rounded-xl bg-gradient-to-br from-white to-gray-50 p-4 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">Holiday Hours Update</h3>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        Oct 15, 2025
                                    </span>
                                    <span
                                        class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">All
                                        Users</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                class="bg-white text-gray-700 border-2 border-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-all text-sm">
                                Edit
                            </button>
                            <button
                                class="bg-white text-gray-700 border-2 border-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-all text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="border border-gray-200 rounded-xl bg-gradient-to-br from-white to-gray-50 p-4 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">New Features Launch</h3>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <span class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        Oct 20, 2025
                                    </span>
                                    <span
                                        class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">All
                                        Facilities</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button
                                class="bg-white text-gray-700 border-2 border-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-all text-sm">
                                Edit
                            </button>
                            <button
                                class="bg-white text-gray-700 border-2 border-gray-300 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-all text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed Delivery Logs -->
        <div class="p-6 border-2 border-red-200 bg-red-50 rounded-xl shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="white" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900">Failed Delivery Logs <span class="text-red-600">(1)</span>
                </h2>
            </div>

            <div class="border border-red-200 rounded-xl bg-white p-4 hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-700 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 mb-1">Payment Processed - SMS</h3>
                            <div class="flex items-center gap-3 text-sm text-gray-600">
                                <span class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Sarah Johnson
                                </span>
                                <span>Sep 30, 2025</span>
                            </div>
                        </div>
                    </div>
                    <button
                        class="bg-white text-gray-700 border-2 border-gray-300 px-5 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-all">
                        Retry
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="notificationModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Send New Notification</h2>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-5">
                    <!-- Notification Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notification Type</label>
                        <select
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                            <option>Push Notification</option>
                            <option>Email Alert</option>
                            <option>SMS Alert</option>
                        </select>
                    </div>

                    <!-- Audience -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Audience</label>
                        <select
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                            <option>All Users</option>
                            <option>Premium Users</option>
                            <option>Free Users</option>
                            <option>Specific Group</option>
                        </select>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Title</label>
                        <input type="text" placeholder="Enter notification title..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                        <textarea rows="4" placeholder="Enter your message here..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all resize-none"></textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200">
                    <button onclick="closeModal()"
                        class="px-5 py-2.5 border-2 border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button
                        class="px-5 py-2.5 bg-black text-white rounded-lg font-semibold hover:bg-gray-900 transition-all flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                        </svg>
                        Send Now
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script>
        function openModal() {
            const modal = document.getElementById('notificationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            const modal = document.getElementById('notificationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('notificationModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection
