@extends('layouts.auth')
@section('title')
    User Management
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="flex items-center justify-between pb-8">
            <div>
                <h1 class="text-3xl font-bold text-black mb-1">User Management</h1>
                <p class="text-gray-500 text-base">Manage user accounts, documents, and activity</p>
            </div>
            {{-- <div>
                <button
                    class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white"
                        class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Add User
                </button>
            </div> --}}
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}"
            class="px-6 py-5 flex items-center gap-4 border border-gray-200 rounded-xl bg-white shadow-sm mb-6">
            <div class="relative flex-1">
                <input type="text" placeholder="Search by name or email..." name="search"
                    value="{{ request('search') }}"
                    class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" />
                <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select name="status"
                class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all min-w-[180px]">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Search
            </button>

            <a href="{{ route('admin.users.index') }}"
                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Reset
            </a>
        </form>

        <!-- Table Container -->
        <div class="overflow-x-auto border border-gray-200 rounded-xl bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Users List <span
                        class="text-gray-500 font-normal">({{ \App\Models\User::where('role', 'worker_mode')->count() }})</span>
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Phone</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Role</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Verified</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Join Date</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->full_name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">{{ $user->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->phone_number }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">
                                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                    </span>
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
                                        class="px-3 py-1 {{ $user->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} rounded-full text-xs font-medium">
                                        {{ $user->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ date('Y-m-d', strtotime($user->created_at)) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="relative inline-block text-left" x-data="{ open: false }">
                                        <button @click="open = !open"
                                            class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                            </svg>
                                        </button>

                                        <div x-show="open" @click.away="open = false"
                                            class="absolute right-0 z-10 w-48 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                            style="display: none;">
                                            <div class="">
                                                {{-- <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                    </svg>
                                                    Edit
                                                </a> --}}
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 outline-none hover:bg-red-50 w-full text-left">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                     <div class="px-6 py-4">
                    {{ $users->links() }}
                </div>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        </div>
    </main>
@endsection
