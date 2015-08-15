<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

    <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="/css/app.css">
</head>
<body>
<div class="jumbotron">
    <div class="container">
        <form method="POST" action="/api/vehicle">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="registration">Rekisterinumero</label>
                <input id="registration" class="form-control" type="text" name="registration" >
            </div>
            <button type="submit" class="btn btn-default">Tallenna</button>
        </form>
    </div>
</div>
</body>
</html>
