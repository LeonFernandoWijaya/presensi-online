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
    </script>
</html>
