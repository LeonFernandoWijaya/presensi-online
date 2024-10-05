@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="flex items-center mb-6 ">
            <h1 class="text-3xl text-blue-800 font-medium">Overtime History</h1>
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

                <div>
                    <label for="status"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select id="status"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        <option selected="0">All Status</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
                    </select>
                </div>
            </div>
            @isManager()
            <div>
                <a type="button" id="exportButton"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                    href="{{ url('/downloadOvertimeHistory?staffId=&startDate=&endDate=&status=0') }}"
                    target="_blank">Export</a>
            </div>
            @endisManager()
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
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="table-body">

                </tbody>
            </table>
            <ul id="pagination-overtime" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>
    @include('modal.overtime-details-modal')

    <script>
        let staffId = '';
        let startDate = '';
        let endDate = '';
        let status = 0;
        let url = "{{ url('/downloadOvertimeHistory') }}";


        function getOvertimeDetail(id) {
            $.ajax({
                url: "{{ url('/getOvertimeDetail') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response)
                    if (response.attendance == null) {
                        $('#overtimeDetailTitle').text('Overtime Details (Manual Request)');
                        $('.automatic-container').addClass('hidden');
                        $('.manual-container').removeClass('hidden');
                        $('#projectName').val(response.projectName != null ? response.projectName : '-');
                        $('#notes').val(response.notes != null ? response.notes : '-');
                        $('#customer').val(response.customer != null ? response.customer : '-');
                    } else {
                        $('#overtimeDetailTitle').text('Overtime Details (Automatic Request)');
                        $('.automatic-container').removeClass('hidden');
                        $('.manual-container').addClass('hidden');
                        $('#locationIn').val(response.attendance.clockInLocation);
                        $('#locationOut').val(response.attendance.clockOutLocation);
                        $('#customer').val(response.attendance.customer != null ? response.attendance.customer : '-');
                        $('#activityType').val(response.attendance.activitytype.name);
                        $('#activityCategory').val(response.attendance.activitycategory.name);
                        $('#photoIn').empty();
                        $('#photoIn').append(
                            `<img src="{{ asset('storage/photos/${response.attendance.clockInPhoto}') }}" class="w-32 h-32 object-cover rounded-lg">`
                            );
                        $('#photoOut').empty();
                        $('#photoOut').append(
                            `<img src="{{ asset('storage/photos/${response.attendance.clockOutPhoto}') }}" class="w-32 h-32 object-cover rounded-lg">`
                            );
                    }
                    $('#staffName').val(response.user.first_name + ' ' + (response.user.last_name != null ?
                        response.user.last_name : ''));
                    
                    $('#overtimeStart').val(response.overtimeStart);
                    $('#overtimeEnd').val(response.overtimeEnd);
                    $('#customer').val(response.customer != null ? response.customer : '-');
                    let hours = Math.floor(response.overtimeTotal / 60);
                    let minutes = response.overtimeTotal % 60;
                    $('#totalOvertime').val(`${hours} hours ${minutes} Minutes`);
                    $('#statusDetail').val(response.rejectDate != null ? 'Rejected' : 'Approved');
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function openOvertimeDetailsModal(id) {
            showFlowBytesModal('overtime-details-modal');
            getOvertimeDetail(id);
        }

        function getOvertimeHistory(page = 1) {
            let staff = $('#staff').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            let status = $('#status').val();

            $.ajax({
                url: "{{ url('/getOvertimeHistory?page=') }}" + page,
                type: 'GET',
                data: {
                    staff: staff,
                    startDate: startDate,
                    endDate: endDate,
                    status: status
                },
                success: function(response) {
                    $('#table-body').empty();
                    response.data.forEach(function(data) {
                        let status = data.rejectDate != null ? 'Rejected' : 'Approved';
                        let hours = Math.floor(data.overtimeTotal / 60);
                        let minutes = data.overtimeTotal % 60;
                        $('#table-body').append(`
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ${data.user.first_name} ${data.user.last_name != null ? data.user.last_name : ''}
                        </th>
                        <td class="px-6 py-4">
                            ${data.overtimeStart}
                        </td>
                        <td class="px-6 py-4">
                            ${data.overtimeEnd}
                        </td>
                        <td class="px-6 py-4">
                            ${hours} hours ${minutes} Minutes
                        </td>
                        <td class="px-6 py-4">
                            ${status}
                        </td>
                        <td class="px-6 py-4 flex items-center">
                            <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" onclick="openOvertimeDetailsModal(${data.id})">Details</button>
                        </td>
                    </tr>

                    `)
                    })
                    buttonPagination('#pagination-overtime', response.last_page,
                        response.current_page, "getOvertimeHistory");
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#staff').change(function() {
            getOvertimeHistory();
            staffId = $('#staff').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $('#startDate').change(function() {
            getOvertimeHistory();
            startDate = $('#startDate').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $('#endDate').change(function() {
            getOvertimeHistory();
            endDate = $('#endDate').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $('#status').change(function() {
            getOvertimeHistory();
            status = $('#status').val();
            $('#exportButton').attr('href',
                `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}&status=${status}`);
        });

        $(document).ready(function() {
            getOvertimeHistory();
        });
    </script>
@endsection
