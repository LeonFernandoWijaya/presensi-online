@extends('layouts.layout')

@section('content')
    <div class="flex items-center justify-center min-h-screen lg:p-0 p-8">


        <div class="py-8 px-6 shadow-xl rounded-3xl bg-white w-full lg:w-1/2">
            <h1 class="font-bold text-2xl mb-16 text-center">Sign Up</h1>
            <div class="mb-4">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Full Name<span
                        class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="firstname" id="firstname"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter your first name" required="">
                    <input type="text" name="lastname" id="lastname"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Enter your last name" required="">
                </div>
            </div>
            <div class="mb-4">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email<span
                        class="text-red-500">*</span></label>
                <input type="email" name="email" id="email"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                    placeholder="Enter your email" required="">
            </div>
            <div class="mb-4">
                <label for="department" class="block mb-2 text-sm font-medium text-gray-900">Department<span
                        class="text-red-500">*</span></label>
                <select id="department"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password<span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 mb-2"
                        placeholder="Enter your password" required="">
                    <button class="absolute inset-y-0 end-2 flex items-center" id="show-password" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="mb-12">
                <label for="confirmpassword" class="block mb-2 text-sm font-medium text-gray-900">Confirm
                    Password<span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="confirmpassword" id="confirmpassword"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 mb-2"
                        placeholder="Enter your confirm password" required="">
                    <button class="absolute inset-y-0 end-2 flex items-center" id="show-confirm-password" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="text-center">
                <button type="button" onclick="register()"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-8 py-2.5 mb-3 focus:outline-none">Register</button>
                <p class="text-xs text-gray-500">Already have an account ? <a href="{{ url('login') }}"
                        class="font-medium text-blue-600 hover:underline">Sign In</a>
                </p>
            </div>
        </div>
    </div>
    @include('modal.verify-email')
    @include('modal.loading-modal')
    <script>
        function register() {
            showFlowBytesModal('loading-modal');
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
                    hideFlowBytesModal('loading-modal');
                    if (response.success == true) {
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        })
                        showFlowBytesModal('verify-email-modal');
                        $('#firstname').val('');
                        $('#lastname').val('');
                        $('#email').val('');
                        $('#department').val('');
                        $('#password').val('');
                        $('#confirmpassword').val('');
                        $('#currentUserId').val(response.currentUserId);
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
                    hideFlowBytesModal('loading-modal');
                    console.log(err);
                }
            });
        }

        function verifyEmail() {
            const currentUserId = $('#currentUserId').val();
            const OTP = $('#OTP').val();
            $.ajax({
                url: "{{ url('/verify-email') }}",
                type: "PUT",
                data: {
                    currentUserId: currentUserId,
                    otp: OTP,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success == true) {
                        swal.fire({
                            title: 'Success',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'Ok'
                        })
                        hideFlowBytesModal('verify-email-modal');
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: response.message,
                            icon: 'error',
                            confirmButtonText: 'Ok'
                        });
                    }
                },
            })
        }

        $('#show-password').click(function() {
            var passwordField = $('#password');
            if (passwordField.attr('type') === 'password') {
                passwordField.attr('type', 'text');
                $(this).find('svg').toggleClass('hidden block');
            } else {
                passwordField.attr('type', 'password');
                $(this).find('svg').toggleClass('hidden block');
            }
        });

        $('#show-confirm-password').click(function() {
            var confirmPasswordField = $('#confirmpassword');
            if (confirmPasswordField.attr('type') === 'password') {
                confirmPasswordField.attr('type', 'text');
                $(this).find('svg').toggleClass('hidden block');
            } else {
                confirmPasswordField.attr('type', 'password');
                $(this).find('svg').toggleClass('hidden block');
            }
        });

        $(document).ready(function() {
            $('#OTP').on('input', function() {
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
                }
            });
        });
    </script>
@endsection
