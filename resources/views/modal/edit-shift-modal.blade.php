<!-- Main modal -->
<div id="edit-shift-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Edit Shift
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    onclick="hideFlowBytesModal('edit-shift-modal')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5 flex flex-col gap-8">
                <div>
                    <label for="editShiftName" class="block mb-2 text-sm font-medium text-gray-900">Shift Name<span
                            class="text-red-500">*</span></label>
                    <input type="text" name="editShiftName" id="editShiftName"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                        placeholder="Type shift name" required="">
                </div>

                <div class="flex items-center justify-between gap-2">
                    <p class="font-medium">List Day</p>
                    <button type="button" onclick="showAddShiftDayModal()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Add
                        Day</button>
                </div>

                <div class="h-64 overflow-y-auto flex flex-col gap-2" id="shiftDayContainer">
                </div>

                <button type="button" onclick="updateShift()"
                    class="text-white flex justify-center w-full items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                    onclick="createNewShift()">

                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
