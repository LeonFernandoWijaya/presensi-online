@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="flex md:justify-between justify-start md:flex-row flex-col gap-5 md:items-end items-start mb-6">
            <div class="flex items-center gap-2">
                <div>
                    <label for="staff" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Staff</label>
                    <select id="staff"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected value="">All Staff</option>
                        @foreach ($users as $user)
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
                <a type="button" id="exportButton"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    href="{{ url('/downloadReject?staffId=&startDate=&endDate=&status=0') }}" target="_blank">Export</a>

            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div>
                <input id="selectAll" type="checkbox" value=""
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="selectAll" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Select All</label>
            </div>

            <div>
                <button type="button" onclick="rejectSelectedOvertime()" id="rejectSelected"
                    class="hidden focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Reject
                    Selected</button>

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
                            Overtime Start
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Overtime End
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total Overtime
                        </th>
                        <th scope="col" class="px-6 py-3 w-1/4">
                            Location
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Photo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">


                </tbody>

            </table>

            <ul id="pagination-reject" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>
    <script>
        let selectedOvertime = [];
        let allOvertimeIds = [];
        let totalOvertime = 0;
        let url = "{{ url('/downloadReject') }}";
        let staffId = '';
        let startDate = '';
        let endDate = '';

        function rejectSelectedOvertime() {
            if (selectedOvertime.length == 0) {
                swal.fire({
                    title: 'Error!',
                    text: 'Please select at least one overtime request to reject',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            } else {
                swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to reject selected overtime requests',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Reject it!',
                    cancelButtonText: 'No, Cancel!',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('rejectSelectedOvertime') }}",
                            type: 'PUT',
                            data: {
                                selectedOvertime: selectedOvertime,
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success == true) {
                                    swal.fire({
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });
                                    getOvertimeForReject();
                                    selectedOvertime = [];
                                    allOvertimeIds = [];
                                    $('#selectAll').prop('checked', false);
                                    $('#rejectSelected').addClass('hidden');
                                } else {
                                    swal.fire({
                                        title: 'Error!',
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
        }

        function getOvertimeForReject(page = 1) {
            let staff = $('#staff').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            $.ajax({
                url: "{{ url('getOvertimeForReject?page=') }} " + page,
                type: 'GET',
                data: {
                    staff: staff,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    console.log(response);
                    totalOvertime = response.overtime.total;
                    $('#tableBody').empty();
                    response.overtime.data.forEach(overtime => {
                        allOvertimeIds = [...response.overtimeIds];
                        let hours = Math.floor(overtime.overtimeTotal / 60);
                        let minutes = overtime.overtimeTotal % 60;
                        $('#tableBody').append(`
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center gap-2">
                                    <input id="checkbox-${overtime.id}" type="checkbox" value="${overtime.id}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

                                    <span>${overtime.user.first_name} ${overtime.user.last_name}</span>
                                </div>
                            </th>
                            <td class="px-6 py-4">
                                ${overtime.overtimeStart}
                            </td>
                            <td class="px-6 py-4">
                                ${overtime.overtimeEnd}
                            </td>
                            <td class="px-6 py-4">
                                ${hours} Hours ${minutes} Minutes
                            </td>
                            <td class="px-6 py-4">
                                ${overtime.attendance != null ? `<div class="flex flex-col gap-2"><div><span class="text-green-500">IN : </span>${overtime.attendance.clockInLocation}</div><div><span class="text-red-500">OUT : </span>${overtime.attendance.clockOutLocation}</div></div>` : 'Request Manual'}
                            </td>
                            <td class="px-6 py-4">
                                <div class="grid grid-cols-2 gap-2">
                                    ${overtime.attendance != null ? `<img src="{{ asset('storage/photos/${overtime.attendance.clockInPhoto}') }}" class="w-25 h-20 rounded-xl"><img src="{{ asset('storage/photos/${overtime.attendance.clockOutPhoto}') }}" class="w-25 h-20 rounded-xl">` : `<div>Request Manual</div>`}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" onclick="rejectOvertime(${overtime.id})" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Reject</button>
                            </td>
                        </tr>
                    `);

                        // Check if the checkbox should be checked
                        if (selectedOvertime.includes(overtime.id)) {
                            $(`#checkbox-${overtime.id}`).prop('checked', true);
                        }

                        // Add change event listener to the checkbox
                        $(`#checkbox-${overtime.id}`).change(function() {
                            if (this.checked) {
                                selectedOvertime.push(overtime.id);
                            } else {
                                selectedOvertime = selectedOvertime.filter(id => id !== overtime
                                    .id);
                            }

                            if (selectedOvertime.length == totalOvertime) {
                                $('#selectAll').prop('checked', true);
                            } else {
                                $('#selectAll').prop('checked', false);
                            }

                            if (selectedOvertime.length > 0) {
                                $('#rejectSelected').removeClass('hidden');
                            } else {
                                $('#rejectSelected').addClass('hidden');
                            }
                        });
                    })
                    buttonPagination('#pagination-reject', response.overtime.last_page,
                        response.overtime
                        .current_page, "getOvertimeForReject");
                }
            });
        }


        function rejectOvertime(id) {
            swal.fire({
                title: 'Are you sure?',
                text: 'You want to reject this overtime request',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Reject it!',
                cancelButtonText: 'No, Cancel!',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('rejectOvertime') }}",
                        type: 'PUT',
                        data: {
                            id: id,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success == true) {
                                selectedOvertime = [];
                                $('#selectAll').prop('checked', false);
                                $('#rejectSelected').addClass('hidden');
                                swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                });
                                getOvertimeForReject();
                            } else {
                                swal.fire({
                                    title: 'Error!',
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

        $('#staff').change(function() {
            getOvertimeForReject();
            selectedOvertime = [];
            allOvertimeIds = [];
            $('#selectAll').prop('checked', false);
            $('#rejectSelected').addClass('hidden');
            staffId = $('#staff').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $('#startDate').change(function() {
            getOvertimeForReject();
            selectedOvertime = [];
            allOvertimeIds = [];
            $('#selectAll').prop('checked', false);
            $('#rejectSelected').addClass('hidden');
            startDate = $('#startDate').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $('#endDate').change(function() {
            getOvertimeForReject();
            selectedOvertime = [];
            allOvertimeIds = [];
            $('#selectAll').prop('checked', false);
            $('#rejectSelected').addClass('hidden');
            endDate = $('#endDate').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $(document).ready(function() {
            // Add change event listener to the "Select All" checkbox
            $('#selectAll').change(function() {
                if (this.checked) {
                    selectedOvertime = [];
                    selectedOvertime = [...allOvertimeIds];
                    $('#tableBody input[type="checkbox"]').prop('checked', true);

                } else {
                    selectedOvertime = [];
                    $('#tableBody input[type="checkbox"]').prop('checked', false);
                }

                if (selectedOvertime.length == totalOvertime) {
                    $('#rejectSelected').removeClass('hidden');
                } else {
                    $('#rejectSelected').addClass('hidden');
                }
            });

            getOvertimeForReject();

            // Get the startDate and endDate input fields
            var $startDateInput = $('#startDate');
            var $endDateInput = $('#endDate');

            // Add an event listener for the change event on the endDate input field
            $endDateInput.on('change', function() {
                // Create Date objects from the values of the startDate and endDate input fields
                var startDate = new Date($startDateInput.val());
                var endDate = new Date($endDateInput.val());

                // Compare the startDate and endDate
                if (endDate < startDate) {
                    swal.fire({
                        title: 'Error!',
                        text: 'End Date must be greater than Start Date',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    $('#endDate').val('');
                    getOvertimeForReject();
                }
            });
        });
    </script>
@endsection
