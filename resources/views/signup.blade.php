@extends('layouts.layout')

@section('content')
<div class="flex items-center justify-center min-h-screen lg:p-0 p-8">


    <div class="py-8 px-6 shadow-xl rounded-3xl bg-white w-full lg:w-1/2">
        <h1 class="font-bold text-2xl mb-16 text-center">Sign Up</h1>
        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Full Name<span
                    class="text-red-500">*</span></label>
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="firstname" id="firstname"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Enter your first name" required="">
                <input type="text" name="lastname" id="lastname"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Enter your last name" required="">
            </div>
        </div>
        <div class="mb-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                    class="text-red-500">*</span></label>
            <input type="email" name="email" id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Enter your email" required="">
        </div>
        <div class="mb-4">
            <label for="department" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Department<span
                    class="text-red-500">*</span></label>
            <select id="department"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password<span
                    class="text-red-500">*</span></label>
            <input type="password" name="password" id="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Enter your password" required="">
        </div>
        <div class="mb-12">
            <label for="confirmpassword" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
                Password<span class="text-red-500">*</span></label>
            <input type="password" name="confirmpassword" id="confirmpassword"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Enter your confirm password" required="">
        </div>
        <div class="text-center">
            <button type="button" onclick="register()"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-8 py-2.5 mb-3 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Register</button>
            <p class="text-xs text-gray-500">Already have an account ? <a href="{{ url('login') }}"
                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Sign In</a>
            </p>
        </div>
    </div>
</div>
<script>
    function register() {
        const firstname = $('#firstname').val();
        const lastname = $('#lastname').val();
        const email = $('#email').val();
        const department = $('#department').val();
        const password = $('#password').val();
        const confirmpassword = $('#confirmpassword').val();
        $.ajax({
            url: "{{ url('/store-sign-up') }}",
            type: "POST",
            data: {
                firstname: firstname,
                lastname: lastname,
                email: email,
                department: department,
                password: password,
                confirmpassword: confirmpassword,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success == true) {
                    $('#firstname').val('');
                    $('#lastname').val('');
                    $('#email').val('');
                    $('#department').val('');
                    $('#password').val('');
                    $('#confirmpassword').val('');
                    swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    });
                } else {
                    swal.fire({
                        title: 'Error',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
</script>
@endsection