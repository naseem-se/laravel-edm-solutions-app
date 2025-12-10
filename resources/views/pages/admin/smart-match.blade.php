@extends('layouts.auth')
@section('title')
    Shift Orchestration
@endsection

@section('content')
    <main class="flex-1 p-6 lg:p-8 overflow-auto bg-gray-50">
        <!-- Header Section -->
        <div class="mb-6">
            <h1 class="text-xl font-semibold text-gray-900 mb-1">Shift Orchestration</h1>
            <p class="text-sm text-gray-500">Re-broadcast unfilled shifts, smart match, and manage waitlists</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Unfilled Shifts Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Unfilled Shifts</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-orange-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="text-3xl font-semibold text-gray-900 mb-1">3</div>
                <div class="text-xs text-gray-500">Require attention</div>
            </div>

            <!-- Smart Matches Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Smart Matches</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                    </svg>
                </div>
                <div class="text-3xl font-semibold text-gray-900 mb-1">9</div>
                <div class="text-xs text-gray-500">AI-powered suggestions</div>
            </div>

            <!-- Waitlist Entries Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Waitlist Entries</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
                <div class="text-3xl font-semibold text-gray-900 mb-1">3</div>
                <div class="text-xs text-gray-500">Staff waiting</div>
            </div>

            <!-- Broadcasts Today Card -->
            <div class="bg-white border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-medium text-gray-600">Broadcasts Today</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                    </svg>
                </div>
                <div class="text-3xl font-semibold text-gray-900 mb-1">12</div>
                <div class="text-xs text-gray-500">Shift notifications sent</div>
            </div>
        </div>

        <!-- Navigation Tabs -->

        <div class="py-6 flex items-center space-x-3">
            <div class="bg-gray-100 p-1.5 rounded-full flex items-center gap-1 shadow-sm">
                <a href="/shift-orchestration"
                    class="py-2.5 px-6 text-sm rounded-full font-medium  hover:shadow text-black  transition-all">
                    Unfilled Shifts
                </a>
                <a href="/smart-match"
                    class="py-2.5 bg-white  px-6 text-sm rounded-full font-medium text-gray-600 hover:text-black shadow-sm hover:shadow transition-all">
                    Smart Match </a>
                <a href="/waitlist"
                    class="py-2.5 px-6 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
                    Waitlist
                </a>
            </div>
        </div>

        <!-- Section Title -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <!-- Header -->
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">AI-Powered Staff Recommendations</h2>
            </div>

            <!-- Staff Card -->
            <div class="p-6">
                <div class="border border-gray-200 rounded-xl hover:shadow-md transition-shadow">
                    <!-- Card Content -->
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <!-- Left Section -->
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div
                                    class="w-14 h-14 bg-[#1F3C88] rounded-full flex items-center justify-center text-white font-semibold text-lg flex-shrink-0">
                                    SJ
                                </div>

                                <!-- Info Section -->
                                <div class="flex-1">
                                    <!-- Name and Match Score -->
                                    <div class="flex items-center gap-3 mb-3">
                                        <h3 class="text-base font-semibold text-gray-900">Sarah Johnson</h3>
                                        <div class="flex items-center gap-1">
                                            <span class="text-sm text-gray-600">Match Score:</span>
                                            <span class="text-sm font-semibold text-green-600">95%</span>
                                        </div>
                                    </div>

                                    <!-- Distance and Certifications -->
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="flex items-center gap-1.5 text-sm text-gray-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                            </svg>
                                            <span>2.3 mi away</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            Certifications:
                                        </div>
                                    </div>

                                    <!-- Badges -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">RN
                                            License</span>
                                        <span
                                            class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">BLS</span>
                                        <span
                                            class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded text-xs font-medium">ACLS</span>
                                    </div>

                                    <!-- Availability Badge -->
                                    <div>
                                        <span
                                            class="px-2.5 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Available</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="flex flex-col items-end gap-4">
                                <!-- Rating -->
                                <div class="flex items-center gap-1.5 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="w-4 h-4 text-yellow-400">
                                        <path fill-rule="evenodd"
                                            d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-gray-600">Rating:</span>
                                    <span class="font-semibold text-gray-900">4.8/5.0</span>
                                </div>

                                <!-- Assign Button -->
                                <button
                                    class="px-4 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-sm font-medium flex items-center gap-2 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z" />
                                    </svg>
                                    Assign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
