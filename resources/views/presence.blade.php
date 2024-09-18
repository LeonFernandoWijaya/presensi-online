@extends('layouts.layout')

@section('content')
    <div class="flex flex-col items-center justify-center lg:p-0 p-8">
        <div class="lg:w-1/2 w-full flex flex-col justify-center md:mt-20 mt-0 gap-4">
            <div class="flex items-center">
                <span class="flex justify-start items-center" id="greeting"></span>
                <span class="ms-2 font-bold">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            </div>
            <div class="rounded-2xl shadow-2xl flex flex-col gap-4">
                <div class="border-b border-gray-600 border-dashed p-4">
                    <div class="flex justify-center items-center gap-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                        </svg>
                        <span id="todayDate"></span>
                    </div>

                </div>
                <div class="flex justify-center mt-10">
                    <button type="button" onclick="checkSchedule()"
                        class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-8 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Check
                        Schedule</button>
                </div>
                <div class="text-6xl flex justify-center font-medium" id="nowClock">

                </div>
                <div class="flex items-center mt-16 ms-6">
                    <input id="default-checkbox" type="checkbox" value=""
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-checkbox"
                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Overtime</label>
                </div>
                <div class="flex items-center mt-2">
                    <button type="button" onclick="validateLocationSetting('Clock In')" id="clockInButton"
                        class="text-white font-semibold text-md px-5 py-2.5 w-full rounded-bl-2xl {{ $isClockOut ? 'bg-gray-400' : 'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800' }}"
                        {{ $isClockOut ? 'disabled' : '' }}>CLOCK
                        IN</button>
                    <button type="button" onclick="validateLocationSetting('Clock Out')" id="clockOutButton"
                        class="text-white font-semibold text-md px-5 py-2.5 w-full rounded-br-2xl {{ $isClockOut ? 'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800' : 'bg-gray-400' }}"
                        {{ $isClockOut ? '' : 'disabled' }}>CLOCK
                        OUT</button>
                </div>
            </div>
        </div>
    </div>

    @include('modal.location-and-photo-modal')
    @include('modal.camera-modal')
    @include('modal.check-schedule-modal')
    @include('modal.finding-location-modal')
    @include('modal.loading-modal')

    <script>
        let latitude = null;
        let longitude = null;
        var mymap = null;
        let photo = null;

        $(document).ready(function() {
            // Get the current date
            let today = new Date();
            let date = String(today.getDate()).padStart(2, '0');
            let month = today.toLocaleString('default', {
                month: 'long'
            });
            let year = today.getFullYear();

            let formattedDate = date + ' - ' + month + ' - ' + year;
            $('#todayDate').text(formattedDate);

            // Update the clock
            function updateClock() {
                let now = new Date();
                let hours = String(now.getHours()).padStart(2, '0');
                let minutes = String(now.getMinutes()).padStart(2, '0');
                let seconds = String(now.getSeconds()).padStart(2, '0');

                let time = hours + ' : ' + minutes + ' : ' + seconds;
                $('#nowClock').text(time);
            }
            updateClock(); // Update the clock immediately
            setInterval(updateClock, 1000); // Update the clock every 1000 milliseconds (1 second)

             // Get the current hour
            let hour = new Date().getHours();

            // Determine whether it's morning or afternoon
            let greeting;
            if (hour < 12) {
                greeting = 'Good Morning,';
            } else {
                greeting = 'Good Afternoon,';
            }

            // Change the text of the #greeting div
            $('#greeting').text(greeting);
        });

        function refreshLocation() {
            getLocation();
            mapMaker();
            showFlowBytesModal('finding-location-modal');
        }

        function mapMaker() {
            // If a map already exists, remove it
            if (mymap != null) {
                mymap.remove();
            }

            // Membuat peta
            mymap = L.map('mapid').setView([latitude, longitude], 20);

            // Menambahkan lapisan tile ke peta
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mymap);

            // Menambahkan penanda ke peta
            L.marker([latitude, longitude]).addTo(mymap);

            $.ajax({
                url: `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`,
                type: 'GET',
                success: function(data) {
                    $('#location').val(data.display_name);
                    hideFlowBytesModal('finding-location-modal');
                },
                error: function(error) {
                    console.log(error);
                    hideFlowBytesModal('finding-location-modal');
                }
            });
        }

        function getLocation() {
            return new Promise((resolve, reject) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;
                        resolve();
                    }, () => {
                        Swal.fire({
                            title: "Error",
                            text: "Please allow location access",
                            icon: "error"
                        });
                        reject();
                    });
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Geolocation is not supported by this browser.",
                        icon: "error"
                    });
                    reject();
                }
            });
        }

        async function validateLocationSetting(type) {
            await getLocation();
            if (latitude == null || longitude == null) {
                Swal.fire({
                    title: "Error",
                    text: "Please allow location access",
                    icon: "error"
                });

                return;
            } else {
                showLocationAndPhotoModal(type);
            }
        }

        function showLocationAndPhotoModal(type) {
            if (latitude == null || longitude == null) {
                Swal.fire({
                    title: "Error",
                    text: "Please allow location access",
                    icon: "error"
                });

                return;
            }
            showFlowBytesModal('location-and-photo-modal');
            $('#locationAndPhotoModalTitle').text(type);
            $('#locationAndPhotoModalSubmit').text(type + ' Now');
            $('#previewPhoto').empty();
            $('#previewPhoto').append(`
                <span class="text-xs">No Photo</span>
            `);
            photo = null;
            mapMaker();
        }

        function openCameraModal() {
            showFlowBytesModal('camera-modal');
            const video = document.getElementById('video');
            const constraints = {
                video: {
                    facingMode: 'user' // Using front camera
                }
            };
            navigator.mediaDevices.getUserMedia(constraints)
                .then((stream) => {
                    video.srcObject = stream;
                })
                .catch((err) => {
                    console.error("Error accessing camera: ", err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please go to your browser settings and allow camera access for this site.',
                    });
                    hideFlowBytesModal('camera-modal');
                    return false;

                });
        }

        function closeCameraModal() {
            const video = document.getElementById('video');
            const stream = video.srcObject;
            const tracks = stream.getTracks();

            tracks.forEach(function(track) {
                track.stop();
            });

            video.srcObject = null;
            hideFlowBytesModal('camera-modal');
        }

        function takePhoto() {
            const video = document.getElementById('video');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            photo = canvas.toDataURL('image/png');
            const previewPhoto = document.getElementById('previewPhoto');
            previewPhoto.innerHTML = ``;
            previewPhoto.innerHTML = `<img src="${photo}" class="h-full w-full object-cover rounded-lg">`;
            closeCameraModal();
        }

        function checkSchedule() {
            showFlowBytesModal('check-schedule-modal');
            $.ajax({
                url: "{{ url('checkSchedule') }}",
                type: 'GET',
                success: function(response) {
                    $('#checkScheduleContainer').empty();
                    response.forEach(data => {
                        let formattedStartHour = data.startHour.slice(0, 5);
                        let formattedEndHour = data.endHour.slice(0, 5);
                        $('#checkScheduleContainer').append(`
                            <div class="flex items-center justify-between rounded-full border border-gray-300 py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                                    </svg>
                                    <span>${data.dayName}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>${formattedStartHour} - ${formattedEndHour}</span>
                                </div>
                            </div>
                        `);
                    })
                }
            })
        }

        async function presenceNow() {
            showFlowBytesModal('loading-modal');
            let sendLatitude = null;
            let sendLongitude = null;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async position => {
                    sendLatitude = position.coords.latitude;
                    sendLongitude = position.coords.longitude;

                    try {
                        const response = await $.ajax({
                            url: "{{ url('presenceNow') }}",
                            type: 'POST',
                            data: {
                                sendLatitude: sendLatitude,
                                sendLongitude: sendLongitude,
                                photo: photo,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                hideFlowBytesModal('loading-modal');
                                if (response.status == 'success') {
                                    Swal.fire({
                                        title: "Success",
                                        text: response.message,
                                        icon: "success"
                                    });
                                    hideFlowBytesModal('location-and-photo-modal');
                                    if (response.statusPresence == 'clockIn') {
                                        $('#clockInButton').attr('disabled', true);
                                        $('#clockInButton').addClass('bg-gray-400');
                                        $('#clockInButton').removeClass('bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800');

                                        $('#clockOutButton').attr('disabled', false);
                                        $('#clockOutButton').removeClass('bg-gray-400');
                                        $('#clockOutButton').addClass('bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800');
                                    } else {
                                        $('#clockInButton').attr('disabled', false);
                                        $('#clockInButton').removeClass('bg-gray-400');
                                        $('#clockInButton').addClass('bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800');

                                        $('#clockOutButton').attr('disabled', true);
                                        $('#clockOutButton').addClass('bg-gray-400');
                                        $('#clockOutButton').removeClass('bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800');
                                    }
                                } else {
                                    Swal.fire({
                                        title: "Error",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(error) {
                                hideFlowBytesModal('loading-modal');
                                swal.fire({
                                    title: "Error",
                                    text: "Failed to send presence data",
                                    icon: "error"
                                });
                            }
                        });

                        console.log(response);
                    } catch (error) {
                        console.error('Error:', error);
                    }
                }, () => {
                    Swal.fire({
                        title: "Error",
                        text: "Please allow location access",
                        icon: "error"
                    });
                });
            } else {
                Swal.fire({
                    title: "Error",
                    text: "Geolocation is not supported by this browser.",
                    icon: "error"
                });
            }
        }
    </script>
@endsection
