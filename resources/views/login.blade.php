@extends('layouts.layout')

@section('content')
    <div class="flex items-center justify-center min-h-screen lg:p-0 p-8">


        <div class="py-8 px-6 shadow-xl rounded-3xl bg-white w-full lg:w-1/2 ">
            <h1 class="font-bold text-2xl mb-16 text-center">Sign In</h1>
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Email<span
                        class="text-red-500">*</span></label>
                <input type="email" name="email" id="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter your email" required="">
            </div>
            <div class="mb-20">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password<span
                        class="text-red-500">*</span></label>
                <input type="password" name="password" id="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 mb-2"
                    placeholder="Enter your password" required="">
                <div class="flex items-center mb-4">
                    <input id="show-password" type="checkbox" value=""
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="show-password" class="ms-2 text-sm font-medium text-gray-900">Show
                        Password</label>
                </div>
            </div>
            <div class="text-center">
                <button type="button" onclick="login()"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-8 py-2.5 mb-3 focus:outline-none">Login</button>
                <p class="text-xs text-gray-500">Don't have an account ? <a href="{{ url('signUp') }}"
                        class="font-medium text-blue-600 hover:underline">Sign Up</a>
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

        $('#show-password').click(function() {
            if ($(this).is(':checked')) {
                $('#password').attr('type', 'text');
            } else {
                $('#password').attr('type', 'password');
            }
        });

        $(document).keypress(function(event) {
            if (event.which == 13) { // 13 adalah kode untuk tombol Enter
                login();
            }
        });
    </script>
@endsection
