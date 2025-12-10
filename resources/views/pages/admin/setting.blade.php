@extends('layouts.auth')
@section('title')
    Settings & Admin Controls
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
                <button 
                onclick="window.location.href=`{{ route('admin.setting.index') }}`"
                class="px-4 py-3 text-sm font-semibold text-white bg-black rounded-t-lg">
                    Profile & Account
                </button>
                <button
                onclick="window.location.href=`{{ route('admin.setting.roles.index') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
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

        <!-- Profile Information Section -->
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-800">Profile Information</h2>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Profile Photo -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Change Photo</label>
                        <div class="flex items-center gap-4">

                            <!-- Avatar -->
                            <div class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-bold bg-gray-200 overflow-hidden">
                                <img id="avatarPreview" 
                                    src="{{ auth()->guard('admin')->user()->image ? asset('storage/' . auth()->guard('admin')->user()->image) : '' }}" 
                                    alt="Profile Photo" 
                                    class="w-full h-full object-cover" 
                                    onerror="this.style.display='none';">
                                @if(!auth()->guard('admin')->user()->image)
                                    {{ strtoupper(substr(auth()->guard('admin')->user()->full_name ?? 'AU', 0, 2)) }}
                                @endif
                            </div>

                            <!-- Hidden File Input -->
                            <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewAvatar(event)">

                            <!-- Change Photo Button -->
                            <button type="button" onclick="document.getElementById('photoInput').click()"
                                class="px-4 py-2 bg-black text-white rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors">
                                Change Photo
                            </button>

                            <span class="text-sm text-gray-500">JPG, PNG or GIF (max. 2MB)</span>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="full_name"
                            value="{{ old('full_name', auth()->guard('admin')->user()->full_name) }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            <input type="email" name="email"
                                value="{{ old('email', auth()->guard('admin')->user()->email) }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            <input type="tel" name="phone"
                                value="{{ old('phone', auth()->guard('admin')->user()->phone_number) }}"
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all">
                        </div>
                    </div>

                    <!-- Role -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                        <input type="text" value="{{ auth()->guard('admin')->user()->role ?? 'Admin' }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed"
                            disabled>
                    </div>

                    <!-- Save Button -->
                    <button type="submit"
                        class="w-full bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                        Save Profile Changes
                    </button>
                </div>
            </div>
        </form>


        <!-- Security Settings Section -->
        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-gray-800">Security Settings</h2>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Current Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" placeholder="Enter current password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all"
                            required>
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <input type="password" name="new_password" placeholder="Enter new password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all"
                            required>
                        @error('new_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm New Password -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" placeholder="Confirm new password"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent transition-all"
                            required>
                    </div>

                    <!-- Two-Factor Authentication -->
                    <div class="mb-6 p-4 bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-1">Two-Factor Authentication</h3>
                                <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="two_factor" class="sr-only peer" 
                                    {{ auth()->guard('admin')->user()->two_factor ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-black/10 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-black">
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Update Password Button -->
                    <button type="submit"
                        class="w-full bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 transition-all duration-200 shadow-lg hover:shadow-xl">
                        Update Password
                    </button>
                </div>
            </div>
        </form>

    </main>

<script>
function previewAvatar(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('avatarPreview');
        output.src = reader.result;
        output.style.display = 'block';
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection

