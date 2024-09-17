@extends('layouts.layout')

@section('content')

<div class="p-4">
    <div class="flex md:justify-between justify-start md:flex-row flex-col gap-5 md:items-end items-start mb-6">
        <div class="flex items-center gap-2">
            <div>
                <label for="staff" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Staff</label>
                <select id="staff" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option selected value="">All Staff</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="startDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                <input type="date" name="startDate" id="startDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
            </div>

            <div>
                <label for="endDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date</label>
                <input type="date" name="endDate" id="endDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <input id="selectAll" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
            <label for="selectAll" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Select All</label>
        </div>

        <div>
            <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Reject Selected</button>

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
                    <th scope="col" class="px-6 py-3">
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

        <ul id="pagination-reject"
            class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
        </ul>
    </div>
</div>
<script>
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
                $('#tableBody').empty();
                response.data.forEach(overtime => {
                    let hours = Math.floor(overtime.overtimeTotal / 60);
                    let minutes = overtime.overtimeTotal % 60;
                    $('#tableBody').append(`
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="flex items-center gap-2">
                                    <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">

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
                                ${overtime.location ?? 'Request Manual'}
                            </td>
                            <td class="px-6 py-4">
                                ${overtime.photo ? `<img src="${overtime.photo}" class="w-20 h-20 rounded-xl">` : `<img src="{{url('no-selfie-taken.png')}}" class="w-20 h-20 rounded-xl">`}
                            </td>
                            <td class="px-6 py-4">
                                <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Reject</button>
                            </td>
                        </tr>
                    `)
                })
                buttonPagination('#pagination-reject', response.last_page,
                    response
                    .current_page, "getOvertimeForReject");
            }
        });
    }

    $('#staff').change(function() {
        getOvertimeForReject();
    });

    $('#startDate').change(function() {
        getOvertimeForReject();
    });

    $('#endDate').change(function() {
        getOvertimeForReject();
    });

    $(document).ready(function() {

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