@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2 justify-between">
                <div>
                    <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"></path>
                            </svg>
                        </div>
                        <input type="search" id="holidayCategoryName"
                            class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search Holiday" required="">
                    </div>
                </div>
                <button type="button" onclick="showCreateHolidayCategoryModal()"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Create
                    New Holiday
                </button>


            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
            <ul id="pagination-holiday-category" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>

    @include('modal.create-holiday-category-modal')

    <script>
        function showCreateHolidayCategoryModal() {
            $('#holidayNameForCategory').val('');
            showFlowBytesModal('create-new-holiday-category-modal');
        }

        function getAllHolidayCategories(page = 1) {
            let holidayNameCategory = $('#holidayCategoryName').val();
            $.ajax({
                url: "{{ url('/getAllHolidayCategory?page=') }}" + page,
                type: 'GET',
                data: {
                    search: holidayNameCategory
                },
                success: function(response) {
                    $('#tableBody').empty();
                    response.data.forEach(element => {
                        $('#tableBody').append(`
                            <tr class="bg-white border-b">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                   ${element.holiday_name}
                                </th>
                                <td class="px-6 py-4">
                                    <a href="{{ url('/holiday-days/${element.id}') }}"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 me-2 focus:outline-none">Edit
                                    </a>
                                    <button type="button" onclick="deleteHolidayCategory(${element.id})"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Delete
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                    buttonPagination('#pagination-holiday-category', response.last_page,
                        response
                        .current_page, "getAllHolidayCategories");
                }
            });
        }

        function createNewHolidayCategory() {
            let holidayNameForCategory = $('#holidayNameForCategory').val();
            $.ajax({
                url: "{{ url('/createNewHolidayCategory') }}",
                type: 'POST',
                data: {
                    holiday_name: holidayNameForCategory,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('create-new-holiday-category-modal');
                        getAllHolidayCategories();
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        });
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                }
            });
        }

        function deleteHolidayCategory(id) {
            swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this holiday category!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/deleteHolidayCategory') }}",
                        type: 'DELETE',
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success == true) {
                                getAllHolidayCategories();
                                swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Ok'
                                });
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'Ok'
                                });
                            }
                        }
                    });
                }
            });
        }

        $('#holidayCategoryName').on('keyup', function() {
            getAllHolidayCategories();
        });

        $(document).ready(function() {
            getAllHolidayCategories();
        });
    </script>
@endsection
