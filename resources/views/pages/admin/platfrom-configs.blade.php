@extends('layouts.auth')
@section('title')
    Plateform Configuration
@endsection

<style>
    .switch {
        font-size: 17px;
        position: relative;
        display: inline-block;
        width: 3em;
        height: 1.5em;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #B0B0B0;
        border: 1px solid #B0B0B0;
        transition: .4s;
        border-radius: 32px;
        outline: none;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 1.5rem;
        width: 1.5rem;
        border-radius: 50%;
        outline: 2px solid #B0B0B0;
        left: -1px;
        bottom: -1px;
        background-color: #fff;
        transition: transform .25s ease-in-out 0s;
    }

    .slider-icon {
        opacity: 0;
        height: 12px;
        width: 12px;
        stroke-width: 8;
        position: absolute;
        z-index: 999;
        stroke: #222222;
        right: 60%;
        top: 30%;
        transition: right ease-in-out .3s, opacity ease-in-out .15s;
    }

    input:checked+.slider {
        background-color: #222222;
    }

    input:checked+.slider .slider-icon {
        opacity: 1;
        right: 20%;
    }

    input:checked+.slider:before {
        transform: translateX(1.5em);
        outline-color: #181818;
    }
</style>

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
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Roles & Permissions
                </button>
                <button onclick="window.location.href=`{{ route('admin.setting.platform.config') }}`"
                    class="px-4 py-3 text-sm font-semibold text-white bg-black rounded-t-lg">
                    Platform Config
                </button>
                <button
                onclick="window.location.href=`{{ route('admin.setting.content.management') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Content Management
                </button>
            </div>
        </div>

        <div class="card mt-6 bg-white p-8" style="border: 1px solid #d9c8c8b8;border-radius: 10px;">
            <div class="card-heading flex flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="24" height="24" viewBox="0 0 48 48">
                    <path
                        d="M 24 4 C 22.423103 4 20.902664 4.1994284 19.451172 4.5371094 A 1.50015 1.50015 0 0 0 18.300781 5.8359375 L 17.982422 8.7382812 C 17.878304 9.6893592 17.328913 10.530853 16.5 11.009766 C 15.672739 11.487724 14.66862 11.540667 13.792969 11.15625 L 13.791016 11.15625 L 11.125 9.9824219 A 1.50015 1.50015 0 0 0 9.4257812 10.330078 C 7.3532865 12.539588 5.7626807 15.215064 4.859375 18.201172 A 1.50015 1.50015 0 0 0 5.4082031 19.845703 L 7.7734375 21.580078 C 8.5457929 22.147918 9 23.042801 9 24 C 9 24.95771 8.5458041 25.853342 7.7734375 26.419922 L 5.4082031 28.152344 A 1.50015 1.50015 0 0 0 4.859375 29.796875 C 5.7625845 32.782665 7.3519262 35.460112 9.4257812 37.669922 A 1.50015 1.50015 0 0 0 11.125 38.015625 L 13.791016 36.841797 C 14.667094 36.456509 15.672169 36.511947 16.5 36.990234 C 17.328913 37.469147 17.878304 38.310641 17.982422 39.261719 L 18.300781 42.164062 A 1.50015 1.50015 0 0 0 19.449219 43.460938 C 20.901371 43.799844 22.423103 44 24 44 C 25.576897 44 27.097336 43.800572 28.548828 43.462891 A 1.50015 1.50015 0 0 0 29.699219 42.164062 L 30.017578 39.261719 C 30.121696 38.310641 30.671087 37.469147 31.5 36.990234 C 32.327261 36.512276 33.33138 36.45738 34.207031 36.841797 L 36.875 38.015625 A 1.50015 1.50015 0 0 0 38.574219 37.669922 C 40.646713 35.460412 42.237319 32.782983 43.140625 29.796875 A 1.50015 1.50015 0 0 0 42.591797 28.152344 L 40.226562 26.419922 C 39.454197 25.853342 39 24.95771 39 24 C 39 23.04229 39.454197 22.146658 40.226562 21.580078 L 42.591797 19.847656 A 1.50015 1.50015 0 0 0 43.140625 18.203125 C 42.237319 15.217017 40.646713 12.539588 38.574219 10.330078 A 1.50015 1.50015 0 0 0 36.875 9.984375 L 34.207031 11.158203 C 33.33138 11.54262 32.327261 11.487724 31.5 11.009766 C 30.671087 10.530853 30.121696 9.6893592 30.017578 8.7382812 L 29.699219 5.8359375 A 1.50015 1.50015 0 0 0 28.550781 4.5390625 C 27.098629 4.2001555 25.576897 4 24 4 z M 24 7 C 24.974302 7 25.90992 7.1748796 26.847656 7.3398438 L 27.035156 9.0644531 C 27.243038 10.963375 28.346913 12.652335 30 13.607422 C 31.654169 14.563134 33.668094 14.673009 35.416016 13.904297 L 37.001953 13.207031 C 38.219788 14.669402 39.183985 16.321182 39.857422 18.130859 L 38.451172 19.162109 C 36.911538 20.291529 36 22.08971 36 24 C 36 25.91029 36.911538 27.708471 38.451172 28.837891 L 39.857422 29.869141 C 39.183985 31.678818 38.219788 33.330598 37.001953 34.792969 L 35.416016 34.095703 C 33.668094 33.326991 31.654169 33.436866 30 34.392578 C 28.346913 35.347665 27.243038 37.036625 27.035156 38.935547 L 26.847656 40.660156 C 25.910002 40.82466 24.973817 41 24 41 C 23.025698 41 22.09008 40.82512 21.152344 40.660156 L 20.964844 38.935547 C 20.756962 37.036625 19.653087 35.347665 18 34.392578 C 16.345831 33.436866 14.331906 33.326991 12.583984 34.095703 L 10.998047 34.792969 C 9.7799772 33.330806 8.8159425 31.678964 8.1425781 29.869141 L 9.5488281 28.837891 C 11.088462 27.708471 12 25.91029 12 24 C 12 22.08971 11.087719 20.290363 9.5488281 19.160156 L 8.1425781 18.128906 C 8.8163325 16.318532 9.7814501 14.667839 11 13.205078 L 12.583984 13.902344 C 14.331906 14.671056 16.345831 14.563134 18 13.607422 C 19.653087 12.652335 20.756962 10.963375 20.964844 9.0644531 L 21.152344 7.3398438 C 22.089998 7.1753403 23.026183 7 24 7 z M 24 16 C 19.599487 16 16 19.59949 16 24 C 16 28.40051 19.599487 32 24 32 C 28.400513 32 32 28.40051 32 24 C 32 19.59949 28.400513 16 24 16 z M 24 19 C 26.779194 19 29 21.220808 29 24 C 29 26.779192 26.779194 29 24 29 C 21.220806 29 19 26.779192 19 24 C 19 21.220808 21.220806 19 24 19 z">
                    </path>
                </svg>
                <p class="ml-2">Platform Configurations</p>
            </div>
            <form action="{{ route('admin.platform.config.update') }}" method="POST">
                @csrf
                <div class="card-body mt-6">
                    <!-- Commission Percentage -->
                    <div
                        class="bg-white rounded-lg p-6 mb-4 border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5">
                                    <path fill="#50892a"
                                        d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Commission Percentage</h3>
                                <p class="text-sm text-gray-500">Platform fee on each transaction</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 flex items-center gap-2">
                            <input type="number" name="commission_percentage"
                                value="{{ old('commission_percentage', $settings->commission_percentage ?? 10) }}"
                                min="0" max="100" step="1"
                                class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-right text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" />
                            <span class="text-gray-600 font-semibold text-lg">%</span>
                        </div>
                    </div>

                    <!-- Cancellation Policy -->
                    <div
                        class="bg-white rounded-lg p-6 mb-4 border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4 flex-1">
                            <div
                                class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5">
                                    <path fill="#c9372c"
                                        d="M528 320C528 434.9 434.9 528 320 528C205.1 528 112 434.9 112 320C112 205.1 205.1 112 320 112C434.9 112 528 205.1 528 320zM64 320C64 461.4 178.6 576 320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320zM296 184L296 320C296 328 300 335.5 306.7 340L402.7 404C413.7 411.4 428.6 408.4 436 397.3C443.4 386.2 440.4 371.4 429.3 364L344 307.2L344 184C344 170.7 333.3 160 320 160C306.7 160 296 170.7 296 184z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Cancellation Policy</h3>
                                <p class="text-sm text-gray-500">Hours before shift start for free cancellation</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 flex items-center gap-2">
                            <input type="number" name="cancellation_policy"
                                value="{{ old('cancellation_policy', $settings->cancellation_policy ?? 24) }}"
                                min="0" step="1"
                                class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-right text-lg font-semibold focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" />
                            <span class="text-gray-600 font-semibold text-lg">hours</span>
                        </div>
                    </div>

                    <!-- Payment Cycle -->
                    <div
                        class="bg-white rounded-lg p-6 mb-4 border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-5 h-5">
                                    <path fill="#667fff"
                                        d="M296 88C296 74.7 306.7 64 320 64C333.3 64 344 74.7 344 88L344 128L400 128C417.7 128 432 142.3 432 160C432 177.7 417.7 192 400 192L285.1 192C260.2 192 240 212.2 240 237.1C240 259.6 256.5 278.6 278.7 281.8L370.3 294.9C424.1 302.6 464 348.6 464 402.9C464 463.2 415.1 512 354.9 512L344 512L344 552C344 565.3 333.3 576 320 576C306.7 576 296 565.3 296 552L296 512L224 512C206.3 512 192 497.7 192 480C192 462.3 206.3 448 224 448L354.9 448C379.8 448 400 427.8 400 402.9C400 380.4 383.5 361.4 361.3 358.2L269.7 345.1C215.9 337.5 176 291.4 176 237.1C176 176.9 224.9 128 285.1 128L296 128L296 88z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Payment Cycle</h3>
                                <p class="text-sm text-gray-500">How often users receive payouts</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <select name="payment_cycle"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-900 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white cursor-pointer">>
                                <option value="">— Select —</option>
                                <option value="hourly" {{ ($settings->payment_cycle ?? '') == 'hourly' ? 'selected' : '' }}>
                                    Hourly</option>
                                <option value="daily" {{ ($settings->payment_cycle ?? '') == 'daily' ? 'selected' : '' }}>
                                    Daily</option>
                                <option value="weekly" {{ ($settings->payment_cycle ?? '') == 'weekly' ? 'selected' : '' }}>
                                    Weekly</option>
                                <option value="monthly"
                                    {{ ($settings->payment_cycle ?? '') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="Annually"
                                    {{ ($settings->payment_cycle ?? '') == 'annually' ? 'selected' : '' }}>Annually</option>
                                <option value="semi-annually"
                                    {{ ($settings->payment_cycle ?? '') == 'semi-annually' ? 'selected' : '' }}>
                                    Semi-Annually</option>
                            </select>
                        </div>
                    </div>

                    <!-- Auto-approve Facilities -->
                    <div
                        class="bg-white rounded-lg p-6 border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Auto-approve New Facilities</h3>
                                <p class="text-sm text-gray-500">Automatically approve facility registrations</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <label class="switch">
                                <input type="checkbox" name="auto_approve_facility">
                                <span class="slider">
                                    <svg class="slider-icon" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"
                                        aria-hidden="true" role="presentation">
                                        <path fill="none" d="m4 16.5 8 8 16-16"></path>
                                    </svg>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="mt-6 w-full bg-black text-white px-6 py-3 rounded-xl font-semibold hover:bg-gray-900 active:scale-95 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Save Configurations
                    </button>
                </div>
            </form>

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
