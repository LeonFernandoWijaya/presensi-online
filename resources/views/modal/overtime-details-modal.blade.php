<!-- Main modal -->
<div id="overtime-details-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="overtimeDetailTitle">
                    Overtime Details
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="hideFlowBytesModal('overtime-details-modal')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="staffName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Staff Name</label>
                        <input type="text" name="staffName" id="staffName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1 automatic-container">
                        <label for="locationIn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location In</label>
                        <input type="text" name="locationIn" id="locationIn" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1 automatic-container">
                        <label for="locationOut" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location Out</label>
                        <input type="text" name="locationOut" id="locationOut" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="overtimeStart" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime Start</label>
                        <input type="text" name="overtimeStart" id="overtimeStart" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="overtimeEnd" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime End</label>
                        <input type="text" name="overtimeEnd" id="overtimeEnd" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>


                    <div class="col-span-2 sm:col-span-1 manual-container">
                        <label for="projectName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project Name</label>
                        <input type="text" name="projectName" id="projectName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1 manual-container">
                        <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                        <input type="text" name="customer" id="customer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="totalOvertime" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Overtime</label>
                        <input type="text" name="totalOvertime" id="totalOvertime" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="statusDetail" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <input type="text" name="statusDetail" id="statusDetail" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled value="">
                    </div>

                    <div class="col-span-2 manual-container">
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes</label>
                        <textarea rows="2" name="notes" id="notes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" disabled></textarea>
                    </div>
                    <div class="col-span-2 sm:col-span-1 automatic-container">
                        <label for="photoIn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Photo In</label>
                        <div id="photoIn">

                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-1 automatic-container">
                        <label for="photoIn" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Photo Out</label>
                        <div id="photoOut">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>