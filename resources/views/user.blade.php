@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2">
                <div>
                    <label for="default-search"
                        class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="staffName"
                            class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Staff" required />
                    </div>
                </div>
                <select id="statusAccount"
                    class="max-w-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option selected value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Shift
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
            <ul id="pagination-users" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>
    @include('modal.edit-user-modal')

    <script>
        $('#staffName').on('keyup', function() {
            getAllUsers();
        })

        $('#statusAccount').on('change', function() {
            getAllUsers();
        })

        function getAllUsers(page = 1) {
            $.ajax({
                url: "{{ url('/getAllUsers?page=') }}" + page,
                type: 'GET',
                data: {
                    name: $('#staffName').val(),
                    statusAccount: $('#statusAccount').val()
                },
                success: function(response) {
                    $('#tableBody').empty();
                    response.data.forEach(user => {
                        $('#tableBody').append(`
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ${user.first_name} ${user.last_name}
                            </th>
                            <td class="px-6 py-4">
                                ${user.is_active ? 'Active' : 'Inactive'}
                            </td>
                            <td class="px-6 py-4">
                                ${user.shift ? user.shift.shift_name : 'No Shift'}
                            </td>
                            <td class="px-6 py-4">
                                ${user.role ? user.role.role_name : 'No Position'}
                            </td>
                            <td class="px-6 py-4 flex items-center gap-2">
                                <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" onclick="showEditUserModal(${user.id})">Edit</button>
                                <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="deleteUser(${user.id})">Delete</button>

                            </td>
                        </tr>
                         `)
                    });
                    buttonPagination('#pagination-users', response.last_page,
                        response
                        .current_page, "getAllUsers");
                }
            })
        }

        function getInitialDataModal() {
            $.ajax({
                url: "{{ url('/getInitialDataModal') }}",
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    response.departments.forEach(department => {
                        $('#department').append(`
                        <option value="${department.id}">${department.department_name}</option>
                    `)
                    });
                    response.shifts.forEach(shift => {
                        $('#shiftCategory').append(`
                        <option value="${shift.id}">${shift.shift_name}</option>
                    `)
                    });

                    response.roles.forEach(role => {
                        $('#role').append(`
                        <option value="${role.id}">${role.role_name}</option>
                    `)
                    });

                    response.holidays.forEach(holiday => {
                        $('#holidayCategory').append(`
                        <option value="${holiday.id}">${holiday.holiday_name}</option>
                    `)
                    });
                }
            })
        }

        function showEditUserModal(id) {
            showFlowBytesModal('edit-user-modal')
            $.ajax({
                url: "{{ url('/getUserById') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response) {
                    console.log(response);
                    $('#userId').val(response.id);
                    $('#firstName').val(response.first_name);
                    $('#lastName').val(response.last_name);
                    $('#department option').each(function() {
                        if ($(this).val() == response.department_id) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#role option').each(function() {
                        if ($(this).val() == response.role_id) {
                            $(this).prop('selected', true);
                        }
                    });
                    $('#shiftCategory option').each(function() {
                        if ($(this).val() == response.shift_id) {
                            $(this).prop('selected', true);
                        } else {
                            $('#shiftCategory option:first').prop('selected', true);
                        }
                    });
                    let foundHoliday = false;
                    $('#holidayCategory option').each(function() {
                        if ($(this).val() == response.holiday_id) {
                            $(this).prop('selected', true);
                            foundHoliday = true;
                        }
                    });
                    if (!foundHoliday) {
                        $('#holidayCategory option:first').prop('selected', true);
                    }
                    $('input[name="default-radio"]').each(function() {
                        if ($(this).val() == response.is_active) {
                            $(this).prop('checked', true);
                        }
                    });

                }
            })
        }

        function saveChangesUser() {
            $.ajax({
                url: "{{ url('/saveChangesUser') }}",
                type: 'PUT',
                data: {
                    id: $('#userId').val(),
                    first_name: $('#firstName').val(),
                    last_name: $('#lastName').val(),
                    department_id: $('#department').val(),
                    role_id: $('#role').val(),
                    shift_id: $('#shiftCategory').val(),
                    holiday_id: $('#holidayCategory').val(),
                    is_active: $('input[name="default-radio"]:checked').val(),
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.success == true) {
                        hideFlowBytesModal('edit-user-modal');
                        getAllUsers();
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

        function deleteUser(id) {
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
                        url: "{{ url('/deleteUser') }}",
                        type: 'DELETE',
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response.success == true) {
                                getAllUsers();
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

        $(document).ready(function() {
            getAllUsers();
            getInitialDataModal();
        });
    </script>
@endsection
