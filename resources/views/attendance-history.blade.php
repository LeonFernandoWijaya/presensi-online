@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="flex items-center mb-6 ">
            <h1 class="text-3xl text-blue-800 font-medium">Attendance History</h1>
        </div>
        <div class="flex md:justify-between justify-start md:flex-row flex-col gap-5 md:items-end items-start mb-6">
            <div class="flex items-center gap-2">
                <div>
                    <label for="staff" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Staff</label>
                    <select id="staff"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected value="">All Staff</option>
                        @foreach ($userResults as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="startDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start
                        Date</label>
                    <input type="date" name="startDate" id="startDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required="">
                </div>

                <div>
                    <label for="endDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End
                        Date</label>
                    <input type="date" name="endDate" id="endDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        required="">
                </div>
            </div>
            <div>
                <button type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Export</button>

            </div>
        </div>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Staff
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Clock In
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Clock Out
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            staff A
                        </th>
                        <td class="px-6 py-4">
                            10 August 2024
                        </td>
                        <td class="px-6 py-4">
                            10:24
                        </td>
                        <td class="px-6 py-4">
                            17:10
                        </td>
                        <td class="px-6 py-4 flex items-center">
                            <button type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                onclick="showAttendanceDetailsModal()">Details</button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            staff A
                        </th>
                        <td class="px-6 py-4">
                            10 August 2024
                        </td>
                        <td class="px-6 py-4">
                            10:24
                        </td>
                        <td class="px-6 py-4">
                            17:10
                        </td>
                        <td class="px-6 py-4 flex items-center">
                            <button type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                onclick="showAttendanceDetailsModal()">Details</button>
                        </td>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            staff A
                        </th>
                        <td class="px-6 py-4">
                            10 August 2024
                        </td>
                        <td class="px-6 py-4">
                            10:24
                        </td>
                        <td class="px-6 py-4">
                            17:10
                        </td>
                        <td class="px-6 py-4 flex items-center">
                            <button type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                                onclick="showAttendanceDetailsModal()">Details</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @include('modal.attendance-details-modal')

    <script>
        function showAttendanceDetailsModal() {
            showFlowBytesModal('attendance-details-modal');
        }
    </script>
@endsection
