<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <title>Laravel</title>

    </head>
    <body class="antialiased">
        hellow
    <div id="libur-container">
        <button type="button" onclick="showReverse()">Reverse</button>

    </div>
    </body>
    <script>
        $.ajax({
            url: 'https://dayoffapi.vercel.app/api',
            type: 'GET',
            success: function(data) {
                data.forEach(function(item){
                    console.log(item);
                    let isNotCuti = item.is_cuti == false ? '' : 'Cuti Bersama';
                    $('#libur-container').append('<div>'+item.keterangan+ ' ->' + isNotCuti+'</div>');
                });
            }
        });

        function showReverse(){
            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=-6.1304791,106.7770845&key=AIzaSyD0B9tz9Dlg8juh3uBzaJnsUvd1TgsfgOo',
                type: 'GET',
                success: function(data) {
                    console.log(data);
                }
            })
        }
    </script>
</html>
