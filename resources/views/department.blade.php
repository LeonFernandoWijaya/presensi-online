@extends('layouts.layout')

@section('content')
    <div class="p-4">
        <div class="mb-6">
            <div class="flex items-center gap-2 justify-between">
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
                        <input type="search" id="search-department"
                            class="block w-full p-3 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search Department" required />
                    </div>
                </div>
                <div>
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        onclick="showCreateDepartmentModal()">Create Department</button>
                </div>
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
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
            <ul id="pagination-department" class="mt-4 flex justify-center space-x-2 rtl:space-x-reverse text-sm">
            </ul>
        </div>
    </div>
    @include('modal.create-department')
    @include('modal.edit-department')

    <script>
         $('#search-department').on('keyup', function(){
            getDepartments();
        });

        function getDepartments(page = 1){
            let search = $('#search-department').val();
            $.ajax({
                url: "{{ url('getDepartments?page=') }}" + page,
                type: 'GET',
                data: {
                    search: search,
                },
                success: function(response){
                    $('#tableBody').empty();
                    response.data.forEach(department => {
                        $('#tableBody').append(`
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    ${department.department_name}
                                </th>
                                <td class="px-6 py-4 flex items-center gap-2">
                                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" onclick="showEditDepartmentModal(${department.id})">Edit</button>
                                    <button type="button" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" onclick="deleteDepartment(${department.id})">Delete</button>
                                </td>
                            </tr>
                        `);
                    });
                    buttonPagination('#pagination-department', response.last_page,
                        response
                        .current_page, "getDepartments");
                },
                error: function(error){
                    console.log(error);
                }
            })
        }

        function createDepartment(){
            let name = $('#name').val();
            $.ajax({
                url: "{{ url('createDepartment') }}",
                type: 'POST',
                data: {
                    department_name: name,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response){
                    hideFlowBytesModal('create-department-modal');
                    getDepartments();
                    swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    })
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function showEditDepartmentModal(id){
            $.ajax({
                url: "{{ url('getDepartmentDetail') }}",
                type: 'GET',
                data: {
                    id: id
                },
                success: function(response){
                    showFlowBytesModal('edit-department-modal');
                    $('#editName').val(response.department_name);
                    $('#departmentId').val(response.id);
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function updateDepartment(){
            let name = $('#editName').val();
            let id = $('#departmentId').val();
            $.ajax({
                url: "{{ url('updateDepartment') }}",
                type: 'PUT',
                data: {
                    department_name: name,
                    id: id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response){
                    hideFlowBytesModal('edit-department-modal');
                    getDepartments();
                    swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    })
                },
                error: function(error){
                    console.log(error);
                }
            });
        }

        function deleteDepartment(id){
            swal.fire({
                title: 'Are you sure?',
                text: 'You will not be able to recover this department!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        url: "{{ url('deleteDepartment') }}",
                        type: 'DELETE',
                        data: {
                            id: id,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response){
                            getDepartments();
                            swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            })
                        },
                        error: function(error){
                            console.log(error);
                        }
                    });
                }
            });
        }

        function showCreateDepartmentModal(){
            showFlowBytesModal('create-department-modal');
            $('#name').val('');
        }

        $(document).ready(function(){
            getDepartments();
        });
    </script>

@endsection