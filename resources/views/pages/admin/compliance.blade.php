@extends('layouts.auth')
@section('title')
    Compliance Center
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
                class="bg-white py-2 px-4 text-sm rounded-full font-medium text-black shadow-sm transition-all">
                All Credentials
            </button>
            <button onclick="window.location.href=`{{ route('admin.compliances.pending') }}`"
                class="py-2 px-4 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
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

        <!-- Search and Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6 shadow-sm">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search credentials..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                    <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <select
                    class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[140px]">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="expiring">Expiring Soon</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">All Credentials</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Staff Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Document Type</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Upload Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Expiry Date</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Days Until Expiry</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($documents as $document)
                        
                        
                        <!-- Row 1 - Active -->
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $document->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        @php
                                            $name = $document->user->full_name;
                                            $initials = collect(explode(' ', $name))
                                                            ->map(fn($n) => strtoupper(substr($n, 0, 1)))
                                                            ->implode('');
                                        @endphp

                                        {{ $initials }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $document->user->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $document->type }}</td>
                            @php
                                $isExpired = false;
                                
                                $createdAt = $document->created_at;
                                $expiryDate = $document->created_at->copy()->addYear();
                                $remainingDays = (int)now()->diffInDays($expiryDate, false);
                                
                            @endphp
                            <td class="px-6 py-4 text-sm text-gray-600"> {{ $createdAt->format('Y-m-d') }} </td>
                            <td class="px-6 py-4 text-sm text-gray-600"> {{ $expiryDate->format('Y-m-d') }} </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($remainingDays > 0)
                                    @if($remainingDays < 50)
                                        <span class="text-yellow-600">{{ $remainingDays }} days</span>
                                    @else
                                        {{ $remainingDays }} days
                                    @endif
                                @else
                                    <span style="display: block;padding: 0;" class="px-6 py-4 text-sm text-red-600 font-semibold">{{ abs($remainingDays) }} days overdue</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($remainingDays > 50)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Active
                                    </span>
                                @elseif($remainingDays > 0)
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                        Expiring Soon
                                    </span>
                                @else
                                    @php
                                        $isExpired = true;
                                    @endphp
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Expired
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center flex items-center">
                                <a href="{{ asset('storage/' . $document->document) }}" download class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                                @if ($isExpired)
                                
                                
                                    <form action="{{ route('admin.compliances.reject', $document->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this compliance?');">
                                        @csrf
                                        <button
                                            title="Delete"
                                            class="cursor-pointer px-3 py-1.5 bg-gray-600 text-black rounded-md text-xs font-medium hover:bg-gray-700 transition-colors flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        
                                        </button>
                                    </form>

                                @endif
                            </td>
                        </tr>

                        @endforeach

                       
                    </tbody>
                     <div class="px-6 py-4">
                    {{ $documents->links() }}
                </div>
                </table>
            </div>
        </div>
    </main>
@endsection
