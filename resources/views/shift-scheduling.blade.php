@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2 justify-between">
                <select id="filterUser"
                    class="max-w-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    <option selected value="">All User</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
                <div class="flex items-center justify-between gap-2">
                    <button type="button" onclick="openImportScheduleModal()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">Import
                        Schedule</button>
                    <button onclick="showFlowBytesModal('create-new-schedule-modal')" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 whitespace-nowrap">Create
                        New Schedule</button>
                </div>
            </div>

        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            User Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Staff Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Start Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            End Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Shift Id
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Shift Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
            <ul id="pagination-schedule" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>

    @include('modal.create-shift-schedule-modal')
    @include('modal.edit-shift-schedule-modal')
    @include('modal.import-schedule-modal')

    <script>
        $('#startDate').on('change', function() {
            var startDate = $(this).val();
            var endDate = $('#endDate').val();
            $('#endDate').attr('min', startDate);
            if (endDate && endDate < startDate) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'End date must be greater than start date'
                })
                $('#startDate').val('');
            }
        });

        $('#endDate').on('change', function() {
            var endDate = $(this).val();
            var startDate = $('#startDate').val();
            if (endDate < startDate) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'End date must be greater than start date'
                })
                $(this).val('');
            }
        });

        // Enable date picker on focus
        $('#startDate, #endDate').on('focus', function() {
            $(this).removeAttr('readonly');
        }).on('blur', function() {
            $(this).attr('readonly', 'readonly');
        });

        // For #editStartDate and #editEndDate
        $('#editStartDate').on('change', function() {
            var editStartDate = $(this).val();
            var editEndDate = $('#editEndDate').val();
            $('#editEndDate').attr('min', editStartDate);
            if (editEndDate && editEndDate < editStartDate) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'End date must be greater than start date'
                });
                $('#editStartDate').val('');
            }
        });

        $('#editEndDate').on('change', function() {
            var editEndDate = $(this).val();
            var editStartDate = $('#editStartDate').val();
            if (editEndDate < editStartDate) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Date',
                    text: 'End date must be greater than start date'
                });
                $(this).val('');
            }
        });

        // Enable date picker on focus
        $('#editStartDate, #editEndDate').on('focus', function() {
            $(this).removeAttr('readonly');
        }).on('blur', function() {
            $(this).attr('readonly', 'readonly');
        });


        $('#filterUser').on('change', function() {
            getShiftSchedule();
        });

        function saveNewSchedule() {
            $.ajax({
                url: "{{ url('saveNewSchedule') }}",
                type: "POST",
                data: {
                    userId: $('#userId').val(),
                    shiftId: $('#shiftId').val(),
                    startDate: $('#startDate').val(),
                    endDate: $('#endDate').val(),
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('create-new-schedule-modal');
                        getShiftSchedule();
                        swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        })
                        $('#userId').val('');
                        $('#shiftId').val('');
                        $('#startDate').val('');
                        $('#endDate').val('');
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        })
                    }
                },
                error: function(error) {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while saving the schedule'
                    })
                }
            })
        }

        function getShiftSchedule(page = 1) {
            $.ajax({
                url: "{{ url('getShiftSchedule?page=') }}" + page,
                type: "GET",
                data: {
                    userId: $('#filterUser').val()
                },
                success: function(response) {
                    $('#tableBody').empty();
                    response.data.forEach(schedule => {
                        $('#tableBody').append(`
                        <tr class="bg-white border-b">
                             <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                ${schedule.user.id}
                            </th>
                            <th class="px-6 py-4">
                                ${schedule.user.first_name} ${schedule.user.last_name}
                            </th>
                            <td class="px-6 py-4">
                                ${schedule.start_date}
                            </td>
                            <td class="px-6 py-4">
                                ${schedule.end_date}
                            </td>
                            <td class="px-6 py-4">
                                ${schedule.shift.id}
                            </td>
                            <td class="px-6 py-4">
                                ${schedule.shift.shift_name}
                            </td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="getShiftScheduleDetail(${schedule.id})">Edit</button>
                                <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="deleteSchedule(${schedule.id})">Delete</button>

                            </td>
                        </tr>
                         `)
                    });
                    buttonPagination('#pagination-schedule', response.last_page,
                        response
                        .current_page, "getShiftSchedule");
                },
                error: function(error) {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching shift schedules'
                    })
                }
            })
        }

        function getShiftScheduleDetail(id) {
            $.ajax({
                url: "{{ url('getShiftScheduleDetail') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    $('#editScheduleId').val(response.id);
                    $('#editUserId').val(response.user_id);
                    $('#editShiftId').val(response.shift_id);
                    $('#editStartDate').val(response.start_date);
                    $('#editEndDate').val(response.end_date);
                    $('#editScheduleId').val(response.id);
                    showFlowBytesModal('edit-schedule-modal');
                },
                error: function(error) {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while fetching shift schedule details'
                    })
                }
            })
        }

        function updateSchedule() {
            $.ajax({
                url: "{{ url('updateSchedule') }}",
                type: "PUT",
                data: {
                    id: $('#editScheduleId').val(),
                    userId: $('#editUserId').val(),
                    shiftId: $('#editShiftId').val(),
                    startDate: $('#editStartDate').val(),
                    endDate: $('#editEndDate').val(),
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('edit-schedule-modal');
                        getShiftSchedule();
                        swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        })
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        })
                    }
                },
                error: function(error) {
                    swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while updating the schedule'
                    })
                }
            })
        }

        function deleteSchedule(id) {
            swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this schedule!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('deleteSchedule') }}",
                        type: "DELETE",
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success == true) {
                                getShiftSchedule();
                                swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message
                                })
                            } else {
                                swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.message
                                })
                            }
                        },
                        error: function(error) {
                            swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while deleting the schedule'
                            })
                        }
                    })
                }
            })
        }

        function openImportScheduleModal() {
            showFlowBytesModal('import-schedule-modal');
            $('#import-file').val('');
            $('#tbody-invalid-import').empty();
        }

        function importNow() {
            var fileInput = document.getElementById('import-file');
            var file = fileInput.files[0];
            var formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: "{{ url('importNow') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success == true ){
                    console.log(response);
                    swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: `Total Data : ${response.totalData}, Success : ${response.totalValid}, Error : ${response.totalInvalid}`
                    })
                    $('#tbody-invalid-import').empty();
                    response.invalidImport.forEach((element) => {
                        $('#tbody-invalid-import').append(`
                        <tr>
                            <td class="px-6 py-3">${element.row}</td>
                            <td class="px-6 py-3">${element.data[0]}</td>
                            <td class="px-6 py-3">${element.data[1]}</td>
                            <td class="px-6 py-3">${element.data[2]}</td>
                            <td class="px-6 py-3">${element.data[3]}</td>
                            <td class="px-6 py-3">${element.errors}</td>
                        </tr>
                        `)
                    });
                    getShiftSchedule();
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        })
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $(document).ready(function() {
            getShiftSchedule();
        });
    </script>
@endsection
