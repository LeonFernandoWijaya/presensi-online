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
                    <label for="customer" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Customer<span class="text-red-500">*</span></label>
                    <input type="text" name="customer" id="customer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter customer name" required="">
                </div>

                <div>
                    <label for="projectName" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Project Name<span class="text-red-500">*</span></label>
                    <input type="text" name="projectName" id="projectName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Enter project name" required="">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <label for="overtimeStart" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime Start<span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="overtimeStart" id="overtimeStart" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                    </div>
                    <div class="flex flex-col">
                        <label for="overtimeEnd" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Overtime End<span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="overtimeEnd" id="overtimeEnd" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="">
                    </div>

                </div>

                <div>
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notes<span class="text-red-500">*</span></label>
                    <textarea name="notes" id="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required="" placeholder="Enter your notes"></textarea>
                </div>

                <div class="flex items-center justify-center">
                    <button type="button" onclick="saveRequest()" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-5 py-2.5 w-full dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Submit</button>

                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function saveRequest() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, send it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('saveRequest') }}",
                    type: 'POST',
                    data: {
                        customer: $('#customer').val(),
                        projectName: $('#projectName').val(),
                        overtimeStart: $('#overtimeStart').val(),
                        overtimeEnd: $('#overtimeEnd').val(),
                        notes: $('#notes').val(),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success == true) {
                            swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                            $('#customer').val('');
                            $('#projectName').val('');
                            $('#overtimeStart').val('');
                            $('#overtimeEnd').val('');
                            $('#notes').val('');
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message,
                            });
                        }
                    }
                });
            }
        })
    }

    $(document).ready(function() {
        var now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        var maxDateTime = now.toISOString().slice(0, 16);
        $('#overtimeStart').attr('max', maxDateTime);

        $('#overtimeStart').change(function() {
            var start = new Date($(this).val());
            start.setDate(start.getDate() + 1);
            start.setMinutes(start.getMinutes() - start.getTimezoneOffset());
            var maxEndDateTime = start.toISOString().slice(0, 16);
            $('#overtimeEnd').attr('max', maxEndDateTime);
            $('#overtimeEnd').val(maxEndDateTime);
        });

        $('#overtimeEnd').change(function() {
            var start = new Date($('#overtimeStart').val());
            var end = new Date($(this).val());
            if (end < start) {
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'End date must be greater than start date!',
                });
                $(this).val($('#overtimeStart').val());
            }
        });
    });
</script>

@endsection