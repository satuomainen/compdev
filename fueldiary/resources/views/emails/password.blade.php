<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tankkeri</title>
</head>
<body>
    <h3>Salasanan palautus</h3>
    Aseta uusi salasana tästä linkistä: {{ url('password/reset/'.$token) }}
</body>
</html>
