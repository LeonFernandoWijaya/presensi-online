@extends('layouts.layout')

@section('content')
<div class="flex items-center justify-center min-h-screen lg:p-0 p-8">


    <div class="py-8 px-6 shadow-xl rounded-3xl bg-white w-full lg:w-1/2 ">
        <h1 class="font-bold text-2xl mb-16 text-center">Sign In</h1>
        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email<span
                    class="text-red-500">*</span></label>
            <input type="email" name="email" id="email"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Enter your email" required="">
        </div>
        <div class="mb-20">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password<span
                    class="text-red-500">*</span></label>
            <input type="password" name="password" id="password"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                placeholder="Enter your password" required="">
        </div>
        <div class="text-center">
            <button type="button" onclick="login()"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-8 py-2.5 mb-3 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Login</button>
            <p class="text-xs text-gray-500">Don't have an account ? <a href="{{ url('signUp') }}"
                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Sign Up</a>
            </p>
        </div>
    </div>
</div>
<script>
    function login() {
        const email = $('#email').val();
        const password = $('#password').val();
        $.ajax({
            url: "{{ url('store-login') }}",
            type: "POST",
            data: {
                email: email,
                password: password,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success == true) {
                    window.location.href = "{{ url('presence') }}";
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