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
                        class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-8 py-2.5 mb-2">Check
                        Schedule</button>
                </div>
                <div class="text-6xl flex justify-center font-medium" id="nowClock">

                </div>

                <div class="flex items-center mt-16 mx-6 gap-3">

                </div>

                <div class="flex items-center mt-2">
                    <button type="button" onclick="validateLocationSetting('Clock In')" id="clockInButton"
                        class="text-white font-semibold text-md px-5 py-2.5 w-full rounded-bl-2xl {{ $isClockOut ? 'bg-gray-400' : 'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none' }}"
                        {{ $isClockOut ? 'disabled' : '' }}>CLOCK
                        IN</button>
                    <button type="button" onclick="validateLocationSetting('Clock Out')" id="clockOutButton"
                        class="text-white font-semibold text-md px-5 py-2.5 w-full rounded-br-2xl {{ $isClockOut ? 'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none' : 'bg-gray-400' }}"
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

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD0B9tz9Dlg8juh3uBzaJnsUvd1TgsfgOo&libraries=geometry">
    </script>

    <script>
        let latitude = null;
        let longitude = null;
        var mymap = null;
        let photo = null;
        $(document).ready(function() {
            var point1 = new google.maps.LatLng(-6.19058187063816, 106.797794951068); // my password location


            var point2 = new google.maps.LatLng(-6.17019594842749, 106.831384975091); // target location

            var distance = google.maps.geometry.spherical.computeDistanceBetween(point1, point2);
            var distanceInKm = distance / 1000;
            console.log(distanceInKm);
            // Fetch the current time from the server once
            $.ajax({
                url: "https://timeapi.io/api/time/current/zone?timeZone=Asia%2FJakarta",
                method: 'GET',
                success: function(data) {
                    // Extract year, month, day, hours, minutes, and seconds
                    let year = parseInt(data.dateTime.substring(0, 4));
                    let month = parseInt(data.dateTime.substring(5, 7)) -
                        1; // JavaScript months are 0-11
                    let date = parseInt(data.dateTime.substring(8, 10));
                    let hours = parseInt(data.dateTime.substring(11, 13));
                    let minutes = parseInt(data.dateTime.substring(14, 16));
                    let seconds = parseInt(data.dateTime.substring(17, 19));

                    // Create a Date object with the fetched time
                    let currentTime = new Date(year, month, date, hours, minutes, seconds);

                    // Update the clock every second
                    setInterval(function() {
                        // Increment the time by one second
                        currentTime.setSeconds(currentTime.getSeconds() + 1);

                        // Format the date
                        let formattedDate = ('0' + currentTime.getDate()).slice(-2) + ' - ' +
                            ('0' + (currentTime.getMonth() + 1)).slice(-2) + ' - ' +
                            currentTime.getFullYear();
                        $('#todayDate').text(formattedDate);

                        // Format the time
                        let time = ('0' + currentTime.getHours()).slice(-2) + ' : ' +
                            ('0' + currentTime.getMinutes()).slice(-2) + ' : ' +
                            ('0' + currentTime.getSeconds()).slice(-2);
                        $('#nowClock').text(time);

                        // Determine whether it's morning or afternoon
                        let greeting = currentTime.getHours() < 12 ? 'Good Morning,' :
                            'Good Afternoon,';
                        $('#greeting').text(greeting);
                    }, 1000);
                }
            });
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
            $.ajax({
                url: "{{ url('/checkStatusPresence') }}",
                type: "GET",
                success: function(response) {
                    $('#customerName').val('');
                    $('#activityTypes option:first').prop('selected', true);
                    $('#activityCategories option:first').prop('selected', true);
                    if (response.PresenceData != null) {
                        $('#customerName').val(response.PresenceData.customer != null ? response.PresenceData
                            .customer : '');

                        if (response.PresenceData.activity_type_id != null) {
                            $('#activityTypes option').each(function() {
                                if ($(this).val() == response.PresenceData.activity_type_id) {
                                    $(this).prop('selected', true);
                                }
                            });
                        } else {
                            $('#activityTypes option:first').prop('selected', true);
                        }

                        if (response.PresenceData.activity_category_id != null) {
                            $('#activityCategories option').each(function() {
                                if ($(this).val() == response.PresenceData.activity_category_id) {
                                    $(this).prop('selected', true);
                                }
                            });
                        } else {
                            $('#activityCategories option:first').prop('selected', true);
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            })
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

        async function takePhoto() {
            showFlowBytesModal('loading-modal');
            const video = document.getElementById('video');
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Load the model.
            const model = await blazeface.load();

            // Pass in an image or video to the model. The 'returnTensors' option tells the model to return tensors.
            const returnTensors = false; // Pass in `true` to get tensors back.
            const predictions = await model.estimateFaces(video, returnTensors);

            if (predictions.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'No faces detected in the photo.',
                });
                hideFlowBytesModal('loading-modal');
                return;
            }

            for (let i = 0; i < predictions.length; i++) {
                const start = predictions[i].topLeft;
                const end = predictions[i].bottomRight;
                const size = [end[0] - start[0], end[1] - start[1]];

                // Check if the face is too close to the camera.
                if (size[0] > video.videoWidth * 0.4 || size[1] > video.videoHeight * 0.4) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'You are too close to the camera. Please move back.',
                    });
                    hideFlowBytesModal('loading-modal');
                    return;
                }

                // Check if the face is in the center of the video.
                const centerX = start[0] + size[0] / 2;
                const centerY = start[1] + size[1] / 2;
                if (centerX < video.videoWidth / 2 - size[0] / 2 || centerX > video.videoWidth / 2 + size[0] / 2 ||
                    centerY < video.videoHeight / 2 - size[1] / 2 || centerY > video.videoHeight / 2 + size[1] / 2) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please position your face in the center of the video.',
                    });
                    hideFlowBytesModal('loading-modal');
                    return;
                }


            }
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            photo = canvas.toDataURL('image/png');
            const previewPhoto = document.getElementById('previewPhoto');
            previewPhoto.innerHTML = `<img src="${photo}" class="h-full w-full object-cover rounded-lg">`;
            hideFlowBytesModal('loading-modal');
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
            $('#locationAndPhotoModalSubmit').prop('disabled', true);
            setInterval(function() {
                $('#locationAndPhotoModalSubmit').prop('disabled', false);
            }, 3000);


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
                                customerName: $('#customerName').val(),
                                activityTypes: $('#activityTypes').val(),
                                activityCategories: $('#activityCategories').val(),
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
                                        $('#clockInButton').removeClass(
                                            'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300'
                                        );

                                        $('#clockOutButton').attr('disabled', false);
                                        $('#clockOutButton').removeClass('bg-gray-400');
                                        $('#clockOutButton').addClass(
                                            'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300'
                                        );
                                    } else {
                                        $('#clockInButton').attr('disabled', false);
                                        $('#clockInButton').removeClass('bg-gray-400');
                                        $('#clockInButton').addClass(
                                            'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300'
                                        );

                                        $('#clockOutButton').attr('disabled', true);
                                        $('#clockOutButton').addClass('bg-gray-400');
                                        $('#clockOutButton').removeClass(
                                            'bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300'
                                        );
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
