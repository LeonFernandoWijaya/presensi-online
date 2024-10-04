<div id="location-and-photo-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="locationAndPhotoModalTitle">
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    onclick="hideFlowBytesModal('location-and-photo-modal')">
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
                <div class="w-full h-32 mb-5 rounded-2xl" id="mapid">

                </div>

                <div class="flex items-center gap-2 mb-5">
                    <button type="button" onclick="refreshLocation()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full w-full text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get
                        My Location</button>
                    <button type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full w-full text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                        onclick="openCameraModal()">Get My Photo</button>

                </div>

                <div class="grid grid-cols-2 gap-3 mb-5">
                    <div class="col-span-2">
                        <label for="customerName"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer</label>
                        <input type="text" name="customerName" id="customerName"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Enter Customer">
                    </div>

                    <div>
                        <label for="activityTypes"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Activity Type<span
                                class="text-red-500">*</span></label>
                        <select id="activityTypes"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected value="" disabled>Select Activity Type</option>
                            @foreach ($activityTypes as $activityType)
                                <option value="{{ $activityType->id }}">{{ $activityType->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div>
                        <label for="activityCategories"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Activity Category<span
                                class="text-red-500">*</span></label>
                        <select id="activityCategories"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected value="" disabled>Select Activity Category</option>
                            @foreach ($activityCategories as $activityCategory)
                                <option value="{{ $activityCategory->id }}">{{ $activityCategory->name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label for="location"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Location
                            <span class="text-red-500">*</span></label>
                        <textarea id="location" disabled rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"></textarea>
                    </div>
                    <div>
                        <label for="photo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Photo
                            <span class="text-red-500">*</span></label>
                        <div class="h-32 w-32 border rounded-lg border-gray-400 flex items-center justify-center"
                            id="previewPhoto">
                            <span class="text-xs">No Photo</span>
                        </div>
                    </div>
                </div>


                <button id="locationAndPhotoModalSubmit" type="button" onclick="presenceNow()"
                    class="mt-2 text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full justify-center">
                </button>
            </form>
        </div>
    </div>
</div>
