@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2 justify-between">
                <input type="hidden" id="holidayTarget" value="{{ $holiday->id }}">
                <select id="holidayYear"
                    class="bg-gray-50 max-w-xs border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <option value="" selected>All Year</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach ()
                </select>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="showImportHolidayModal()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Import
                        National DayOff</button>
                    <button type="button" onclick="showCreateNewHolidayModal()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Create
                        New Holiday</button>

                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Holiday Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
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

    @include('modal.import-holiday-modal')
    @include('modal.information-import-national-dayoff-modal')
    @include('modal.edit-holiday-modal')
    @include('modal.create-new-holiday-modal')



    <script>
        let nationalDayOffArray = [];

        $('#holidayYear').change(function() {
            getHolidays();
        });

        function showCreateNewHolidayModal() {
            showFlowBytesModal('create-new-holiday-modal');
        }

        function saveNewHoliday() {
            const holidayName = $('#holidayName').val();
            const holidayDate = $('#holidayDate').val();
            const holidayTarget = $('#holidayTarget').val();
            $.ajax({
                url: "{{ url('saveNewHoliday') }}",
                type: 'POST',
                data: {
                    holidayName: holidayName,
                    holidayDate: holidayDate,
                    id: holidayTarget,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('create-new-holiday-modal');
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        getHolidays();
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function getHolidays(page = 1) {
            const year = $('#holidayYear').val();
            $.ajax({
                url: "{{ url('getHolidays?page=') }}" + page,
                type: 'GET',
                data: {
                    year: year,
                    id: $('#holidayTarget').val()
                },
                success: function(response) {
                    console.log(response);
                    $('#tableBody').empty();
                    response.data.forEach(holiday => {
                        $('#tableBody').append(`
                        <tr class="bg-white border-b">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            ${holiday.holiday_name}
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            ${holiday.holiday_date}
                        </th>
                        <td class="px-6 py-4 flex items-center gap-2">
                            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none" onclick="showEditHolidayModal(${holiday.id})">Edit</button>
                            <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="deleteHoliday(${holiday.id})">Delete</button>
                        </td>
                        </tr>
                        `)
                    })
                    buttonPagination('#pagination-holiday', response.last_page,
                        response
                        .current_page, "getHolidays");
                }
            });
        }

        function deleteHoliday(id) {
            swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this holiday!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('deleteHoliday') }}",
                        type: 'DELETE',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success == true) {
                                swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                getHolidays();
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                });
                            }
                        }
                    });
                }
            });
        }

        function showImportHolidayModal() {
            showFlowBytesModal('import-holiday-modal');
            nationalDayOffArray = [];
            renderCurrentNationalDayOffAPI();
        }

        function removeFromNationalDayOffArray(index) {
            nationalDayOffArray.splice(index, 1);
            renderCurrentNationalDayOffAPI();
        }

        function renderCurrentNationalDayOffAPI() {
            $('#nationalDayOffAPIContainer').empty();
            nationalDayOffArray.forEach((holiday, index) => {
                $('#nationalDayOffAPIContainer').append(`
                         <div
                        class="grid grid-cols-3 gap-3 items-center text-xs px-4 py-2 shadow-md border border-gray-200 rounded-full">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                            </svg>
                            <p>${holiday.tanggal}</p>
                        </div>
                        <div>
                            ${holiday.keterangan}
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="removeFromNationalDayOffArray(${index})"
                                class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-2 py-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>

                            </button>
                        </div>
                    </div>
                        `)
            })
        }

        function getNationalDayOffAPI() {
            const selectedYear = $('#importYear').val();
            $.ajax({
                url: "{{ url('getNationalDayOffAPI') }}",
                type: 'GET',
                data: {
                    year: selectedYear
                },
                success: function(response) {
                    nationalDayOffArray = [...response];
                    renderCurrentNationalDayOffAPI();
                },
                error: function(error) {
                    swal.fire({
                        title: 'Error',
                        text: $('#importYear').val() + ' is not available now in the API',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function saveAllImportNationalDayOff() {
            $.ajax({
                url: "{{ url('saveAllImportNationalDayOff') }}",
                type: 'POST',
                data: {
                    nationalDayOffArray: nationalDayOffArray,
                    _token: '{{ csrf_token() }}',
                    id: $('#holidayTarget').val()
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('import-holiday-modal');
                        showFlowBytesModal('information-import-national-dayoff-modal');
                        $('#totalSuccessImport').text(response.totalSuccess);
                        $('#totalFailedImport').text(response.totalNotStored);
                        $('#listFailedImport').empty();
                        response.existingDates.forEach((date, index) => {
                            $('#listFailedImport').append(`
                                <div class="flex text-xs">${index + 1}. ${date}</div>
                            `)
                        })
                        getHolidays();
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        function showEditHolidayModal(id) {
            showFlowBytesModal('edit-holiday-modal');
            $.ajax({
                url: "{{ url('getHolidayById') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    $('#editHolidayName').val(response.holiday_name);
                    $('#editHolidayDate').val(response.holiday_date);
                    $('#editHolidayId').val(response.id);
                }
            });
        }

        function saveChangesHoliday() {
            const holidayName = $('#editHolidayName').val();
            const id = $('#editHolidayId').val();
            $.ajax({
                url: "{{ url('saveChangesHoliday') }}",
                type: 'PUT',
                data: {
                    holidayName: holidayName,
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('edit-holiday-modal');
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        getHolidays();
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        $(document).ready(function() {
            getHolidays();
            const currentYear = new Date().getFullYear();
            const $select = $('#importYear');
            for (let i = 0; i <= 2; i++) {
                const year = currentYear + i;
                $select.append($('<option>', {
                    value: year,
                    text: year
                }));
            }
        });
    </script>
@endsection
