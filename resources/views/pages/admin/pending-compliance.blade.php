@extends('layouts.auth')
@section('title')
    Compliance Center - Pending Approvals
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Compliance Center</h1>
                <p class="text-sm text-gray-500">Manage credentials, licenses, and certificates</p>
            </div>
            {{-- <button
                class="bg-gray-900 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-gray-800 transition-all duration-200 flex items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                </svg>
                Upload Document
            </button> --}}
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Expiring Soon -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Expiring Soon</span>
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-yellow-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $expiringSoonCount }}</div>
                <p class="text-xs text-gray-500">Within 30 days</p>
            </div>

            <!-- Expired -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Expired</span>
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-red-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $expiredCount }}</div>
                <p class="text-xs text-gray-500">Requires immediate action</p>
            </div>

            <!-- Pending Approval -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-medium text-gray-600">Pending Approval</span>
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-blue-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mb-1">{{ $total_pending }}</div>
                <p class="text-xs text-gray-500">Awaiting review</p>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-gray-100 p-1.5 rounded-lg flex items-center gap-1 shadow-sm mb-6 w-fit">
            <button onclick="window.location.href=`{{ route('admin.compliances.index') }}`"
                class="py-2 px-4 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
                All Credentials
            </button>
            <button onclick="window.location.href=`{{ route('admin.compliances.pending') }}`"
                class="bg-white py-2 px-4 text-sm rounded-full font-medium text-black shadow-sm transition-all">
                Pending Approval
            </button>
            <button onclick="window.location.href=`{{ route('admin.compliances.expiring.soon') }}`"
                class="py-2 px-4 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
                Expiring Soon
            </button>
            <button onclick="window.location.href=`{{ route('admin.compliances.expired') }}`"
                class="py-2 px-4 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
                Expired
            </button>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Pending Approvals</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Staff Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Document Type</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Upload Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($documents as $document)
                        
                       
                        <!-- Row 1 -->
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $document->id }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-gray-900">{{ $document->user->full_name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $document->type }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $document->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium inline-flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    Pending
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <form action="{{ route('admin.compliances.approve', $document->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to approve this compliance?');">
                                    @csrf
                                    <button
                                        class="px-3 py-1.5 bg-gray-900 text-white rounded-md text-xs font-medium hover:bg-gray-800 transition-colors flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        Approve
                                    </button>
                                    </form>

                                    <form action="{{ route('admin.compliances.reject', $document->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to approve this compliance?');">
                                    @csrf
                                    <button
                                        class="px-3 py-1.5 bg-red-600 text-white rounded-md text-xs font-medium hover:bg-red-700 transition-colors flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        Reject
                                    </button>
                                    </form>

                                    <a href="{{ asset('storage/' . $document->document) }}" download class="px-3 py-1.5 bg-green-600 text-white rounded-md text-xs font-medium hover:bg-green-700 transition-colors flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-white-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </td>
                        </tr>

                         @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
