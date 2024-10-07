@extends('layouts.layout')

@section('content')
    <div class="flex flex-col items-center justify-center lg:p-0 p-8">
        <div class="lg:w-1/2 w-full flex flex-col justify-center md:mt-20 mt-0 gap-4 bg-white rounded-2xl">
            <div class="rounded-2xl shadow-2xl flex flex-col gap-4">
                <div class="flex items-center justify-between p-4 md:p-5 border-b dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Change Password
                    </h3>
                </div>
                <form class="p-4 md:p-5 flex flex-col gap-4">
                    <div>
                        <label for="old_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Old
                            Password<span class="text-red-500">*</span></label>
                        <input type="password" name="old_password" id="old_password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter old password" required="">
                    </div>

                    <div>
                        <label for="new_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">New
                            Password<span class="text-red-500">*</span></label>
                        <input type="password" name="new_password" id="new_password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter new password" required="">
                    </div>

                    <div>
                        <label for="confirm_password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm New
                            Password<span class="text-red-500">*</span></label>
                        <input type="password" name="confirm_password" id="confirm_password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter confirm new password" required="">
                    </div>


                    <div class="flex items-center justify-center">
                        <button type="button" onclick="saveNewPassword()"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 w-full dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function saveNewPassword() {
            $.ajax({
                url: "{{ url('saveNewPassword') }}",
                type: 'PUT',
                data: {
                    old_password: $('#old_password').val(),
                    new_password: $('#new_password').val(),
                    confirm_password: $('#confirm_password').val(),
                    "_token": "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#old_password').val('');
                    $('#new_password').val('');
                    $('#confirm_password').val('');
                    if (response.success == true) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        })
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        })
                    }
                },
                error: function(error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while processing your request',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    })
                }
            })
        }
    </script>
@endsection
