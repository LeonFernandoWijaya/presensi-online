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
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="shiftSearch"
                            class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search Shift Name" required />
                    </div>
                </div>

                <div>
                    <button type="button" onclick="showFlowBytesModal('create-new-shift-modal')"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Create
                        New Shift</button>

                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
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
            <ul id="pagination-shift" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>
    @include('modal.create-new-shift-modal')
    @include('modal.edit-shift-modal')
    @include('modal.shift-add-day-modal')
    @include('modal.shift-edit-day-modal')

    <script>
        $('#shiftSearch').on('keyup', function() {
            getShifts();
        });

        function getShifts(page = 1) {
            const shiftSearch = $('#shiftSearch').val();
            $.ajax({
                url: "{{ url('/getShifts?page=') }}" + page,
                type: 'GET',
                data: {
                    shiftSearch: shiftSearch
                },
                success: function(response) {
                    console.log(response);
                    $('#tableBody').empty();
                    response.data.forEach(shift => {
                        $('#tableBody').append(`
                    <tr class="bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        ${shift.shift_name}
                    </th>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="showEditShiftModal(${shift.id})">Edit</button>
                        <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="deleteShift(${shift.id})">Delete</button>
                    </td>
                </tr>

                    `)
                    })
                    buttonPagination('#pagination-shift', response.last_page,
                        response
                        .current_page, "getShifts");
                }
            });
        }

        function createNewShift() {
            const shiftName = $('#shiftName').val();
            console.log($('#shiftName').val());
            $.ajax({
                url: "{{ url('createNewShift') }}",
                type: 'POST',
                data: {
                    shiftName: shiftName,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('create-new-shift-modal');
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        getShifts();
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

        function deleteShift(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/deleteShift') }}",
                        type: 'DELETE',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success == true) {
                                getShifts();
                                swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                })
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                })
                            }
                        }
                    })
                }
            })
        }

        function getShiftDayData(id) {
            $.ajax({
                url: "{{ url('/getShiftById') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    $('#editShiftName').val(response.shift_name);
                    $('#shiftDayContainer').empty();
                    response.shiftDays.forEach(day => {
                        $('#shiftDayContainer').append(`
                    <div class="rounded-xl border border-gray-200 shadow-sm flex items-center justify-between px-4 py-2">
                        <div class="flex flex-col gap-2 font-medium">
                            <p>${day.dayName}</p>
                            <p>${day.startHour.substr(0, 5)} - ${day.endHour.substr(0, 5)}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="showEditShiftDayModal(${day.id})" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>
                            <button type="button" onclick="deleteShiftDay(${day.id})" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>

                            </button>

                        </div>
                    </div>
                    `)
                    })

                }
            });
        }

        function showEditShiftModal(id) {
            $('#shiftId').val(id);
            showFlowBytesModal('edit-shift-modal');
            getShiftDayData(id);
        }

        function showAddShiftDayModal() {
            $('#startHour').val('');
            $('#endHour').val('');
            showFlowBytesModal('shift-add-day-modal');
        }

        function addShiftDay() {
            const shiftId = $('#shiftId').val();
            const dayName = $('#dayName').val();
            const startHour = $('#startHour').val();
            const endHour = $('#endHour').val();
            if (startHour >= endHour) {
                swal.fire({
                    title: 'Error',
                    text: 'Start hour must be less than end hour',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }

            $.ajax({
                url: "{{ url('/addShiftDay') }}",
                type: 'POST',
                data: {
                    shiftId: shiftId,
                    dayName: dayName,
                    startHour: startHour,
                    endHour: endHour,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('shift-add-day-modal');
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        getShiftDayData(shiftId);
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

        function deleteShiftDay(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/deleteShiftDay') }}",
                        type: 'DELETE',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success == true) {
                                getShiftDayData(response.id);
                                swal.fire({
                                    title: 'Success',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                })
                            } else {
                                swal.fire({
                                    title: 'Error',
                                    text: response.message,
                                    icon: 'error',
                                    confirmButtonText: 'OK'
                                })
                            }
                        }
                    })
                }
            })
        }

        function updateShift() {
            const shiftId = $('#shiftId').val();
            const shiftName = $('#editShiftName').val();
            $.ajax({
                url: "{{ url('/updateShift') }}",
                type: 'PUT',
                data: {
                    shiftId: shiftId,
                    shiftName: shiftName,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('edit-shift-modal');
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        getShifts();
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

        function showEditShiftDayModal(id) {
            showFlowBytesModal('shift-edit-day-modal');
            $.ajax({
                url: "{{ url('/getShiftDayById') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    $('#shiftDayId').val(response.id);
                    console.log(response);
                    $('#editDayName option').each(function() {
                        if ($(this).val() == response.dayName) {
                            $(this).attr('selected', 'selected');
                        }
                    });
                    let formatStartHour = response.startHour.substring(0, 5); // Extract 'HH:MM'
                    let formatEndHour = response.endHour.substring(0, 5); // Extract 'HH:MM'
                    $('#editStartHour').val(formatStartHour);
                    $('#editEndHour').val(formatEndHour);
                }
            });
        }

        function updateShiftDay() {
            $.ajax({
                url: "{{ url('/updateShiftDay') }}",
                type: 'PUT',
                data: {
                    id: $('#shiftDayId').val(),
                    dayName: $('#editDayName').val(),
                    startHour: $('#editStartHour').val(),
                    endHour: $('#editEndHour').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('shift-edit-day-modal');
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                        getShiftDayData(response.id);
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
            getShifts();
        });
    </script>
@endsection
