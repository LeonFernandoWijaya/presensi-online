<div id="camera-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Take Photo
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    onclick="closeCameraModal()">
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
                <div class="relative">
                    <video disabledpictureinpicture playsinline webkit-playsinline
                        class="w-full h-[31rem] rounded-xl mb-5" id="video" autoplay="">

                    </video>
                    <div
                        class="absolute md:w-64 md:h-64 w-32 h-32 border-2 border-dotted border-white rounded-full top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    </div>
                </div>

                <div class="flex items-center justify-center">
                    <button type="button" onclick="takePhoto()"
                        class="hover:bg-white hover:border hover:border-gray-200 w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                        <span class="bg-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200">

                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
