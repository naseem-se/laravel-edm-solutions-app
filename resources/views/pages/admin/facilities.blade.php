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
                <input type="text" placeholder="Search by name or location..."
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
                    class="bg-white py-2.5 px-8 text-sm rounded-full font-medium text-black shadow-sm hover:shadow transition-all">
                    All Facilities
                </a>
                <a href="{{ route('admin.facilities.pending') }}"
                    class="py-2.5 px-8 text-sm rounded-full font-medium text-gray-600 hover:text-black hover:bg-white/50 transition-all">
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
                <h2 class="text-lg font-semibold text-gray-800">Facility List <span
                        class="text-gray-500 font-normal">(5)</span></h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Facility Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Contact Person</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Phone</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Location</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Active Shifts</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">F001</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->full_name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $user->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">Dr. Robert Smith</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        New York, NY
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 {{ $user->email_verified_at ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} rounded-full text-xs font-medium flex items-center gap-1 w-fit">
                                        <span
                                            class="w-1.5 h-1.5 {{ $user->email_verified_at ? 'bg-green-500' : 'bg-gray-500' }} rounded-full"></span>
                                        {{ $user->email_verified_at ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-semibold text-gray-900">{{ $user->active_shifts_count }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.facilities.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 outline-none hover:bg-red-50 w-full text-left">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
