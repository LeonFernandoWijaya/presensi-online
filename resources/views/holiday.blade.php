@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2 justify-between">
                <div>
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                            </svg>
                        </div>
                        <input type="search" id="holidayCategoryName"
                            class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Holiday" required="">
                    </div>
                </div>
                <button type="button" onclick="showCreateHolidayCategoryModal()"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create
                    New Holiday
                </button>


            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Holiday Category
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>
            <ul id="pagination-holiday" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>

    @include('modal.create-holiday-category-modal')

    <script>
        function showCreateHolidayCategoryModal() {
            $('#holidayCategoryName').val('');
            showFlowBytesModal('create-new-holiday-category-modal');
        }
    </script>
@endsection
