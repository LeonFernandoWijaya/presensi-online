<!-- Main modal -->
<div id="edit-user-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Edit User
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    onclick="hideFlowBytesModal('edit-user-modal')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <input type="hidden" id="userId">
                    <div class="col-span-2">
                        <label for="fullName" class="block text-sm font-medium text-gray-900">Full
                            Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <input type="text" name="firstName" id="firstName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="First name" required="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <input type="text" name="lastName" id="lastName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                            placeholder="Last name" required="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="department" class="block mb-2 text-sm font-medium text-gray-900">Department<span
                                class="text-red-500">*</span></label>
                        <select id="department"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="role" class="block mb-2 text-sm font-medium text-gray-900">Roles<span
                                class="text-red-500">*</span></label>
                        <select id="role"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        </select>
                    </div>
                    {{-- <div class="col-span-2 sm:col-span-1">
                        <label for="shiftCategory" class="block mb-2 text-sm font-medium text-gray-900">Shift
                            Category<span class="text-red-500">*</span></label>
                        <select id="shiftCategory"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            <option value="">No Select</option>

                        </select>
                    </div> --}}

                    <div class="col-span-2">
                        <label for="holidayCategory" class="block mb-2 text-sm font-medium text-gray-900">Holiday
                            Category<span class="text-red-500">*</span></label>
                        <select id="holidayCategory"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                            <option value="">No Select</option>

                        </select>
                    </div>

                    <div class="col-span-2">
                        <label for="accountStatus" class="block mb-2 text-sm font-medium text-gray-900">Account
                            Status<span class="text-red-500">*</span></label>
                        <div class="grid md:grid-cols-2 grid-cols-1">
                            <div class="flex items-center mb-4">
                                <input id="default-radio-1" type="radio" value="1" name="default-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="default-radio-1"
                                    class="ms-2 text-sm font-medium text-gray-900">Active</label>
                            </div>
                            <div class="flex items-center">
                                <input id="default-radio-2" type="radio" value="0" name="default-radio"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                                <label for="default-radio-2"
                                    class="ms-2 text-sm font-medium text-gray-900">Inactive</label>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="saveChangesUser()"
                    class="text-white flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full w-full text-sm px-5 py-2.5 text-center">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
