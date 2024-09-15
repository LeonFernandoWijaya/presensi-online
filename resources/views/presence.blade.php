@extends('layouts.layout')

@section('content')
<p>Good Morning <span>Akun Admin</span></p>
<div class="rounded-xl shadow-lg">
    <div>
        <div id="mapid"></div>
    </div>
</div>
<script>
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

<!-- <script>
    // Membuat peta
    var mymap = L.map('mapid').setView([-6.1304363, 106.7770966], 17);

    // Menambahkan lapisan tile ke peta
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(mymap);

    // Menambahkan penanda ke peta
    L.marker([-6.1304363, 106.7770966]).addTo(mymap);
</script> -->
@endsection