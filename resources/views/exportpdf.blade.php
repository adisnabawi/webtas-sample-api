<style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>
<div>
    <img src="{{ asset('logo.png') }}" style="height:50px; float:right;">
    <h4 style="margin:0px">WEBTAS - Rekod Kehadiran</h4>
</div>
<div style="clear:both; margin-top:-30px;">
    <p style="font-size: 12px">
        <b> Nama: </b>{{ $name }} <br>
        <b>Tarikh Cetak:</b> {{ date('Y-m-d H:i:s') }}
    </p>
</div>
<div style="padding-top: 20px ">
    <table>
        <thead>
            <tr>
                <td style="font-weight: bold;">Masa Masuk</td>
                <td style="font-weight: bold;">Masa Keluar</td>
                <td style="font-weight: bold;">Lokasi Masuk</td>
                <td style="font-weight: bold;">Lokasi Keluar</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($history_limit as $history)
                <tr>
                    <td>{{ $history['time_in'] }}</td>
                    <td>{{ $history['time_out'] }}</td>
                    <td>
                        {{ 'Latitude: ' . $history['latitude_in'] . ', ' . 'Longitude: ' . $history['longitude_in'] }}
                    </td>
                    <td>
                        {{ 'Latitude: ' . $history['latitude_out'] . ', ' . 'Longitude: ' . $history['longitude_out'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
