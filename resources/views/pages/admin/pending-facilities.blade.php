@extends('layouts.auth')
@section('title')
    Facilities Management
@endsection

@section('content')

    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">Facilities Management</h1>
                <p class="text-gray-500 text-base">Oversee facility accounts and their activities</p>
            </div>
            {{-- <div>
                <button
                    class="bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                    </svg>
                    Add Facility
                </button>
            </div> --}}
        </div>

        <div class="px-6 py-5 border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="relative w-full">
                <input type="text" placeholder="Search by name or email..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="py-6 flex items-center space-x-3">
            <div class="bg-gray-100 p-1.5 space-x-1 rounded-full flex items-center shadow-sm">
                <a href="/facilities"
                    class="py-2.5 px-8 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
                    All Facilities
                </a>
                <a href="/pending-facilities"
                    class="bg-white py-2.5 px-8 text-sm rounded-full font-medium text-black shadow-sm hover:shadow transition-all">
                    Pending Approvals
                </a>
            </div>
            <div class="text-yellow-800 bg-yellow-100 px-3 py-1.5 rounded-lg font-semibold text-sm shadow-sm">
                {{ $total_pending }}
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Pending Facility Approvals</h2>
            </div>

            <div class="p-6">
                @foreach ($users as $user)
                
                
                <div class="border border-gray-200 rounded-xl bg-gradient-to-br from-white to-gray-50 p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center text-white font-semibold shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-lg font-semibold text-black mb-1">{{ $user->address.' '.$user->city }}</h1>
                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                        </svg>
                                        {{ $user->full_name }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                        </svg>
                                        {{ $user->phone_number }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <form action="{{ route('admin.facilities.approve', $user->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to approve this user?');">
                            @csrf
                                <button
                                    class="bg-black text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-900 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="white" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Approve
                                
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.facilities.reject', $user->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to reject this user?');">
                            @csrf
                            <button
                                class="bg-white border-2 border-gray-300 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-50 hover:border-gray-400 transform hover:scale-105 transition-all duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Reject
                            </button>
                            </form>
                        </div>
                    </div>
                </div>

                @endforeach
            </div>
        </div>
    </main>
@endsection
