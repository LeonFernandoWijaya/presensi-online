<!-- Main modal -->
<div id="import-schedule-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">
                    Import Schedule
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    onclick="hideFlowBytesModal('import-schedule-modal')">
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
                <div class="grid gap-4 mb-2 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900">File<span
                                class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2">
                            <input
                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                id="import-file" type="file">

                            <button type="button" onclick="previewImport()"
                                class="text-white inline-flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Preview
                            </button>
                        </div>
                        <a href="#"
                            class="font-medium text-blue-600 text-xs dark:text-blue-500 hover:underline">Download import
                            template</a>
                    </div>

                </div>

                <div class="mb-4">
                    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab"
                            data-tabs-toggle="#default-tab-content" role="tablist">
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab"
                                    data-tabs-target="#profile" type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Valid Row</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button
                                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                    id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                                    aria-controls="dashboard" aria-selected="false">Invalid Row</button>
                            </li>
                        </ul>
                    </div>
                    <div id="default-tab-content">
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="profile" role="tabpanel"
                            aria-labelledby="profile-tab">
                            <div class="relative overflow-x-auto h-72 overflow-y-auto">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                User Id
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Shift Id
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Start Date
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                End Date
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-valid-import">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel"
                            aria-labelledby="dashboard-tab">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            User Id
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Shift Id
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Start Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            End Date
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Error Message
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-invalid-import">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <button type="button" onclick=""
                    class="text-white w-full flex justify-center items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 text-center">
                    Import Now
                </button>
            </div>
        </div>
    </div>
</div>
