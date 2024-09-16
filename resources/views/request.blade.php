@extends('layouts.layout')

@section('content')
<div class="flex flex-col items-center justify-center lg:p-0 p-8">
    <div class="lg:w-1/2 w-full flex flex-col justify-center md:mt-20 mt-0 gap-4 bg-white rounded-2xl">
        <div class="rounded-2xl shadow-2xl flex flex-col gap-4">
            <div class="flex items-center justify-between p-4 md:p-5 border-b dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Request Overtime
                </h3>
            </div>
            <form class="p-4 md:p-5 flex flex-col gap-4">
                <div>
                    <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                    <input type="text" name="customer" id="customer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter customer name" required="">
                </div>

                <div>
                    <label for="projectName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project Name</label>
                    <input type="text" name="projectName" id="projectName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter project name" required="">
                </div>

                <div>
                    <label for="overtimeDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime Date</label>
                    <input type="date" name="overtimeDate" id="overtimeDate" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <label for="startHour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Hour</label>
                        <div class="relative">
                            <input type="time" name="startHour" id="startHour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 pr-10" required="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 absolute right-9 top-1/2 transform -translate-y-1/2" style="pointer-events: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>

                    </div>
                    <div class="flex flex-col">
                        <label for="endHour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Hour</label>
                        <div class="relative">
                            <input type="time" name="endHour" id="endHour" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 pr-10" required="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 absolute right-9 top-1/2 transform -translate-y-1/2" style="pointer-events: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                    </div>

                </div>

                <div>
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime Date</label>
                    <textarea name="notes" id="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="" placeholder="Enter your notes"></textarea>
                </div>

                <div class="flex items-center justify-center">
                    <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 w-full dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                </div>
            </form>

        </div>
    </div>
</div>

@endsection