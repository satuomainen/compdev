@extends('layouts.master')

@section('content')

    <div class="row">
        <h1>Tankkeri</h1>
    </div>

    <div class="row">
        <div class="panel panel-default login-panel">
            <div class="panel-heading">
                <h3 class="panel-title">Kirjaudu</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="/auth/login">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                            <label for="email" class="sr-only">Sähköpostiosoite</label>
                            <input id="email" name="email" type="email" class="form-control" placeholder="Sähköpostiosoite" aria-describedby="sizing-addon1" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
                            <label for="password" class="sr-only">Salasana</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Salasana" aria-describedby="sizing-addon2">
                        </div>
                    </div>

                    <div class="row form-group">
                        <input id="remember" name="remember" type="checkbox" checked>
                        <label for="remember" class="block">Muista minut</label>
                    </div>

                    <div class="row form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Kirjaudu</button>
                    </div>
                </form>
                <div class="row form-group text-center">
                    <a href="/auth/register">Luo tunnukset</a>
                    |
                    <a href="/password/email">Unohdin salasanani</a>
                </div>
            </div>
        </div>
    </div>

@endsection
