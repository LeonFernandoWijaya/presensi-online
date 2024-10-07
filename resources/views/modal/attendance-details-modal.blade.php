<!-- Main modal -->
<div id="attendance-details-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Attendance Details
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    onclick="hideFlowBytesModal('attendance-details-modal')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="staffName" class="block mb-2 text-sm font-medium text-gray-900">Staff Name</label>
                        <input type="text" name="staffName" id="staffName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="customerName" class="block mb-2 text-sm font-medium text-gray-900">Customer</label>
                        <input type="text" name="customerName" id="customerName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="activityType" class="block mb-2 text-sm font-medium text-gray-900">Activity
                            Type</label>
                        <input type="text" name="activityType" id="activityType"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="activityCategory" class="block mb-2 text-sm font-medium text-gray-900">Activity
                            Category</label>
                        <input type="text" name="activityCategory" id="activityCategory"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="locationIn" class="block mb-2 text-sm font-medium text-gray-900">Location In</label>
                        <textarea disabled id="locationIn" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="locationOut" class="block mb-2 text-sm font-medium text-gray-900">Location
                            Out</label>
                        <textarea disabled id="locationOut" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="dateTimeIn" class="block mb-2 text-sm font-medium text-gray-900">Date Time
                            In</label>
                        <input type="text" name="dateTimeIn" id="dateTimeIn"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="dateTimeOut" class="block mb-2 text-sm font-medium text-gray-900">Date Time
                            Out</label>
                        <input type="text" name="dateTimeOut" id="dateTimeOut"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            disabled value="">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="photoIn" class="block mb-2 text-sm font-medium text-gray-900">Photo
                            In</label>
                        <div id="photoIn">

                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="photoOut" class="block mb-2 text-sm font-medium text-gray-900">Photo
                            Out</label>
                        <div id="photoOut">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
