@extends('layouts.layout')

@section('content')

<div class="flex flex-col items-center justify-center lg:p-0 p-8">
    <div class="lg:w-1/2 w-full flex flex-col justify-center md:mt-20 mt-0 gap-4">
        <p class="flex justify-start items-center">Good Morning, <span class="ms-2 font-bold">Akun Admin</span></p>
        <div class="rounded-2xl shadow-2xl flex flex-col gap-4">
            <div class="border-b border-gray-600 border-dashed p-4">
                <div class="flex justify-center items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                    <span id="todayDate"></span>
                </div>

            </div>
            <div class="flex justify-center mt-10">
                <button type="button" class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-full text-sm px-8 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">Check Schedule</button>
            </div>
            <div class="text-6xl flex justify-center font-medium" id="nowClock">

            </div>
            <div class="flex items-center mt-16 ms-6">
                <input id="default-checkbox" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="default-checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Overtime</label>
            </div>
            <div class="flex items-center mt-2">
                <button type="button" onclick="validateLocationSetting('Clock In')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-semibold text-md px-5 py-2.5 w-full rounded-bl-2xl dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">CLOCK IN</button>
                <button type="button" onclick="validateLocationSetting('Clock Out')" class="text-white bg-gray-400 font-semibold text-md px-5 py-2.5 w-full rounded-br-2xl" disabled>CLOCK OUT</button>
            </div>
        </div>
    </div>
</div>

@include('modal.location-and-photo-modal')
@include('modal.camera-modal')
<script>
    let latitude = null;
    let longitude = null;
    var mymap = null;

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
    });

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
        mapMaker();
    }
    // $.ajax({
    //     url: 'https://us1.locationiq.com/v1/reverse?key=pk.460d675996d878661445851022dd0fc9&lat=-6.1304363&lon=106.7770966&format=json',
    //     type: 'GET',
    //     success: function(data) {
    //         console.log(data);
    //     }
    // });

    // $.ajax({
    //     url: ' https://maps.googleapis.com/maps/api/geocode/json?latlng=-6.1304363,106.7770966&key=',
    //     type: 'GET',
    //     success: function(data) {
    //         console.log(data);
    //     }
    // });
</script>

@endsection