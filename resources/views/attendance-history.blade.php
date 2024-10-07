@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="flex items-center mb-6 ">
            <h1 class="text-3xl text-blue-800 font-medium">Attendance History</h1>
        </div>
        <div class="flex md:justify-between justify-start md:flex-row flex-col gap-5 md:items-end items-start mb-6">
            <div class="flex items-center gap-2">
                <div>
                    <label for="staff" class="block mb-2 text-sm font-medium text-gray-900">Staff</label>
                    <select id="staff"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option selected value="">All Staff</option>
                        @foreach ($userResults as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="startDate" class="block mb-2 text-sm font-medium text-gray-900">Start
                        Date</label>
                    <input type="date" name="startDate" id="startDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        required="">
                </div>

                <div>
                    <label for="endDate" class="block mb-2 text-sm font-medium text-gray-900">End
                        Date</label>
                    <input type="date" name="endDate" id="endDate"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        required="">
                </div>
            </div>
            @isManager()
            <div>
                <a type="button" href="{{ url('/downloadAttendanceHistory?staffId=&startDate=&endDate=') }}"
                    target="_blank" id="exportButton"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none">Export</a>

            </div>
            @endisManager()
        </div>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Staff
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date In
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date Out
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="table-body">


                </tbody>
            </table>
            <ul id="pagination-attendance" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>
    @include('modal.attendance-details-modal')

    <script>
        let staffId = '';
        let startDate = '';
        let endDate = '';
        let url = "{{ url('/downloadAttendanceHistory') }}";

        function getAttendanceDetail(id) {
            $.ajax({
                url: "{{ url('/getAttendanceDetail') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    let photoOut = ``;
                    if (response.clockOutPhoto != null) {
                        photoOut =
                            `<img src="{{ asset('storage/photos/${response.clockOutPhoto}') }}" class="w-32 h-32 object-cover rounded-lg">`
                    } else {
                        photoOut =
                            `<img src="{{ url('in-progress.png') }}" class="w-32 h-32 object-cover rounded-lg">`
                    }
                    $('#staffName').val(response.user.first_name + ' ' + (response.user.last_name != null ?
                        response.user.last_name : ''));
                    $('#customerName').val(response.customer != null ? response.customer.name : '-');
                    $('#activityType').val(response.activitytype.name);
                    $('#activityCategory').val(response.activitycategory.name);
                    $('#locationIn').val(response.clockInLocation + ' (' + response.clockInMode +
                        ')');
                    $('#locationOut').val(response.clockOutLocation != null ? response.clockOutLocation + ' (' +
                        response.clockOutMode + ')' :
                        "In Progress");
                    $('#dateTimeIn').val(response.clockInTime);
                    $('#dateTimeOut').val(response.clockOutTime != null ? response.clockOutTime :
                        "In Progress");
                    $('#photoIn').empty();
                    $('#photoIn').append(`
                        <img src="{{ asset('storage/photos/${response.clockInPhoto}') }}" class="w-32 h-32 object-cover rounded-lg">
                    `);
                    $('#photoOut').empty();
                    $('#photoOut').append(photoOut);

                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function showAttendanceDetailsModal(id) {
            showFlowBytesModal('attendance-details-modal');
            getAttendanceDetail(id);
        }

        function getAttendanceHistory(page = 1) {
            let staff = $('#staff').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            $.ajax({
                url: "{{ url('/getAttendanceHistory?page=') }}" + page,
                type: 'GET',
                data: {
                    staff: staff,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    console.log(response);
                    $('#table-body').empty();
                    response.data.forEach(data => {
                        $('#table-body').append(`
                            <tr class="bg-white border-b">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    ${data.user.first_name} ${data.user.last_name ?? ""}
                                </th>
                                <td class="px-6 py-4">
                                    ${data.clockInTime}
                                </td>
                                <td class="px-6 py-4">
                                    ${data.clockOutTime != null ? data.clockOutTime : "In Progress"}
                                </td>
                                <td class="px-6 py-4">
                                    <button type="button"
                                        class="text-white flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 focus:outline-none"
                                        onclick="showAttendanceDetailsModal(${data.id})">Details</button>
                                </td>
                            </tr>
                        `)
                    })
                    buttonPagination('#pagination-attendance', response.last_page,
                        response.current_page, "getAttendanceHistory");
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        $('#staff').change(function() {
            getAttendanceHistory();
            staffId = $('#staff').val();
            $('#exportButton').attr('href', `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}`);
        });

        $('#startDate').change(function() {
            getAttendanceHistory();
            startDate = $('#startDate').val();
            $('#exportButton').attr('href', `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}`);
        });

        $('#endDate').change(function() {
            getAttendanceHistory();
            endDate = $('#endDate').val();
            $('#exportButton').attr('href', `${url}?staffId=${staffId}&startDate=${startDate}&endDate=${endDate}`);
        });

        $(document).ready(function() {
            getAttendanceHistory();
        });
    </script>
@endsection
