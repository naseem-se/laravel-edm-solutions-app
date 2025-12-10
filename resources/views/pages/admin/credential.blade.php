@extends('layouts.auth')
@section('title')
    Credentials
@endsection

@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto">
        <div class="pt-6 px-6 pb-12">
            <div class=" text-zinc-900 text-4xl font-bold leading-7">Credentials</div>
        </div>
        <div class="">

            <!-- Table Container -->
            <div class="overflow-x-auto px-6">
                <table class="w-full min-w-full table table-auto">
                    <thead class="bg-white">
                        <tr>
                            <th
                                class="px-6 rounded-l-md py-6 text-left text-sm font-semibold text-[#1F3C88] capitalize tracking-wider">
                                Worker Name
                            </th>
                            <th class="px-6 py-6 text-left text-sm font-semibold text-[#1F3C88] capitalize tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-6 text-left text-sm font-semibold text-[#1F3C88] capitalize tracking-wider">
                                License Type
                            </th>
                            <th class="px-6 py-6 text-left text-sm font-semibold text-[#1F3C88] capitalize tracking-wider">
                                Expiration Date
                            </th>
                            <th class="px-6 py-6 text-left text-sm font-semibold text-[#1F3C88] capitalize tracking-wider">
                                Status
                            </th>
                            <th
                                class="px-6 rounded-r-md py-6 text-left text-sm font-semibold text-[#1F3C88] capitalize tracking-wider">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="h-3" />
                        <tr class="">
                            <td class="px-6 py-4 rounded-l-md whitespace-nowrap text-sm font-medium text-gray-900">
                                Alexander Smith
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                RBN
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Registering
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                09-25-1997
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Verified
                            </td>
                            <td class="px-6 rounded-r-md py-4 whitespace-nowrap text-sm space-x-2">
                                <button
                                    class="px-8 py-1 cursor-pointer bg-[#006838] rounded-[50px] text-white hover:bg-green-700 transition-colors">
                                    Approved
                                </button>
                            </td>
                        </tr>
                        <tr class="h-3" />

                        <tr class="">
                            <td class="px-6 py-4 rounded-l-md whitespace-nowrap text-sm font-medium text-gray-900">
                                Alexander Smith
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                RBN
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Registering
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                09-25-1997
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Verified
                            </td>
                            <td class="px-6 rounded-r-md py-4 whitespace-nowrap text-sm space-x-2">
                                <button
                                    class="px-8 py-1 cursor-pointer bg-[#006838] rounded-[50px] text-white hover:bg-green-700 transition-colors">
                                    Approved
                                </button>
                            </td>
                        </tr>
                        <tr class="h-3" />

                        <tr class="">
                            <td class="px-6 py-4 rounded-l-md whitespace-nowrap text-sm font-medium text-gray-900">
                                Alexander Smith
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                RBN
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Registering
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                09-25-1997
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Verified
                            </td>
                            <td class="px-6 rounded-r-md py-4 whitespace-nowrap text-sm space-x-2">
                                <button
                                    class="px-8 py-1 cursor-pointer bg-[#006838] rounded-[50px] text-white hover:bg-green-700 transition-colors">
                                    Approved
                                </button>
                            </td>
                        </tr>
                        <tr class="h-3" />

                        <tr class="">
                            <td class="px-6 py-4 rounded-l-md whitespace-nowrap text-sm font-medium text-gray-900">
                                Alexander Smith
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                RBN
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Registering
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                09-25-1997
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Verified
                            </td>
                            <td class="px-6 rounded-r-md py-4 whitespace-nowrap text-sm space-x-2">
                                <button
                                    class="px-8 py-1 cursor-pointer bg-[#006838] rounded-[50px] text-white hover:bg-green-700 transition-colors">
                                    Approved
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
