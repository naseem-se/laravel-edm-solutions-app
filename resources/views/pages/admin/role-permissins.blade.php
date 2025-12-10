@extends('layouts.auth')
@section('title')
    Roles & Permissions
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="pb-8">
            <h1 class="text-3xl font-bold text-black mb-1">Settings & Admin Controls</h1>
            <p class="text-gray-500 text-base">Configure system rules, roles, and track admin activity</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6">
            <div class="flex items-center gap-2 border-b border-gray-200">
                <button onclick="window.location.href=`{{ route('admin.setting.index') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Profile & Account
                </button>
                <button onclick="window.location.href=`{{ route('admin.setting.roles.index') }}`"
                    class="px-4 py-3 text-sm font-semibold text-white bg-black rounded-t-lg">
                    Roles & Permissions
                </button>
                <button
                onclick="window.location.href=`{{ route('admin.setting.platform.config') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Platform Config
                </button>
                <button
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Content Management
                </button>
            </div>
        </div>

        <div class="card mt-6 bg-white p-8" style="border: 1px solid #d9c8c8b8;border-radius: 10px;">
            <div class="card-heading flex flex-1">
                <img width="24" height="24" src="https://img.icons8.com/material-outlined/24/shield.png" alt="shield"/>
                <p class="ml-2">Admin Roles and Permissions</p>
            </div>
            <div class="card-body mt-6">
                <table class="w-full">
                    <thead class="border-b border-gray-100">
                        <tr>
                            <th class="text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="px-6 py-4 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-4 text-right text-sm font-medium text-gray-700">Actions</th>

                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="text-left">
                                <span class="text-sm font-medium">Admin-1</span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <span class="text-sm font-medium">admin1@example.com</span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <span
                                    class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium  w-fit">
                                    <span class="w-1.5 h-1.5 bg-[#FFEDD4] rounded-full"></span>
                                    Super Admin
                                </span>
                            </td>

                            <td class="px-6 py-4 text-left">
                                <span
                                    class="px-3 py-1 bg-green-100 text-green-700  rounded-full text-sm font-medium w-fit">
                                    <span class="w-1.5 h-1.5 bg-[#FFEDD4] rounded-full"></span>
                                    Active
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="px-2 py-1 font-medium text-sm border-2 border-gray-100 bg-white">
                                    Edit Role
                                </button>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('avatarPreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
