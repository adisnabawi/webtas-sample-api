<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google Maps</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .map-container {
            height: 100%;
            width: 100%;
        }

        iframe {
            border: 0;
            height: 100%;
            width: 100%;
        }
    </style>
</head>

<body>

    <iframe
        src="{{ 'https://www.google.com/maps/embed/v1/place?key=' . env('GOOGLE_API_KEY') . '&q=' . $location . '&zoom=18' }}"
        style="border:0;" loading="lazy">
    </iframe>

</body>

</html>
