@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2 justify-between">
                <select id="holidayYear"
                    class="bg-gray-50 max-w-xs border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    @forelse ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @empty
                        <option value="" disabled selected>-</option>
                    @endforelse

                </select>

                <div class="flex items-center gap-2">
                    <button type="button" onclick=""
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Import
                        National DayOff</button>
                    <button type="button" onclick="showCreateNewHolidayModal()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Create
                        New Holiday</button>

                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
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

    @include('modal.create-new-holiday-modal')

    <script>
        function showCreateNewHolidayModal() {
            showFlowBytesModal('create-new-holiday-modal');
        }

        function saveNewHoliday() {
            const holidayName = $('#holidayName').val();
            const holidayDate = $('#holidayDate').val();
            $.ajax({
                url: "{{ url('saveNewHoliday') }}",
                type: 'POST',
                data: {
                    holidayName: holidayName,
                    holidayDate: holidayDate,
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
    </script>
@endsection
