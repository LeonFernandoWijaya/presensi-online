<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ url('javascripts/app.js') }}"></script>
    <link rel="icon" href="{{ url('mypassword.png') }}" type="image/x-icon">

    <script src="{{ asset('javascripts/sweetalert2@11.js') }}"></script>
    <title>Attendance App</title>

    <!-- TensorFlow.js and blazeface model -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>
    <style>
        .video-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            align-items: center;     /* Center vertically */
            position: relative;
        }

        .dashed-outline {
            width: 40%; /* Adjust based on desired size */
            height: 60%; /* Adjust based on desired size */
            border: 3px dashed white;
            border-radius: 50%; /* To create an oval/rounded shape */
            pointer-events: none; /* To make sure the div is not interactable */
            position: absolute;
        }
    </style>
</head>

<body class="bg-blue-50">
    @auth


        <nav class="bg-white border-gray-200">
            <div
                class="flex flex-wrap items-center md:justify-center justify-start md:gap-20 gap-0 mx-auto p-4 border-b border-gray-300">
                <div
                    class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse flex-row-reverse md:w-auto w-full justify-between">
                    <button type="button" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom">
                        <span class="sr-only">Open user menu</span>
                        <div
                            class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-100 rounded-full">
                            <span
                                class="font-medium text-gray-600">{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->last_name, 0, 1)) ?? '' }}

                            </span>
                        </div>
                    </button>
                    <!-- Dropdown menu -->
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                        id="user-dropdown">
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <a href="{{ url('/change-password') }}"
                                    class="block w-full justify-start flex px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change
                                    Password</a>
                            </li>
                            <li>
                                <form action="{{ url('/logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full justify-start flex px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign
                                        out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <button data-collapse-toggle="navbar-user" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        aria-controls="navbar-user" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">
                    <ul
                        class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                        <li>
                            <a href="{{ url('/presence') }}"
                                class="block py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'presence' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}"
                                aria-current="page">Presence</a>
                        </li>
                        <li>
                            <a href="{{ url('/request') }}"
                                class="block py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'request' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}">Request</a>
                        </li>
                        <li>
                            <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                                class="flex items-center justify-between w-full md:w-auto py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'history' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}">History
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg></button>
                            <!-- Dropdown menu -->
                            <div id="dropdownNavbar"
                                class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownLargeButton">
                                    <li>
                                        <a href="{{ url('/attendance-history') }}"
                                            class="block px-4 py-2 hover:bg-gray-100">Attendance
                                            History</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('/overtime-history') }}"
                                            class="block px-4 py-2 hover:bg-gray-100">Overtime
                                            History</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        @isManager()
                            <li>
                                <a href="{{ url('/reject') }}"
                                    class="block py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'reject' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}">Reject</a>
                            </li>
                            <li>
                                <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownSettings"
                                    class="flex items-center justify-between w-full md:w-auto py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'settings' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}">Settings
                                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 4 4 4-4" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div id="dropdownSettings"
                                    class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                                    <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownLargeButton">
                                        <li>
                                            <a href="{{ url('/user') }}" class="block px-4 py-2 hover:bg-gray-100">User</a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/department') }}"
                                                class="block px-4 py-2 hover:bg-gray-100">Department</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="{{ url('/holiday') }}"
                                    class="block py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'holiday' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}">Holiday</a>
                            </li>
                            <li>
                                <a href="{{ url('/shift') }}"
                                    class="block py-2 px-3 rounded md:hover:bg-transparent md:p-0 {{ $navbar == 'shift' ? 'md:text-blue-700' : 'text-gray-900 hover:bg-gray-100 md:hover:text-blue-700' }}">Shift</a>
                            </li>
                        @endisManager
                    </ul>
                </div>
            </div>
        </nav>

    @endauth

    @yield('content')
</body>

</html>
