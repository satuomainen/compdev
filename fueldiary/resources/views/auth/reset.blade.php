@extends('layouts.master')

@section('content')

    <div class="row">
        <h1>Tankkeri</h1>
    </div>

    <div class="row">
        <div class="panel panel-info login-panel">
            <div class="panel-heading">
                <h3 class="panel-title">Asetetaan uusi salasana</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="/password/reset">
                    {!! csrf_field() !!}
                    <input type="hidden" name="token" value="{{ $token }}">
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
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon" id="sizing-addon3"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
                            <label for="password_confirmation" class="sr-only">Salasanan vahvistus</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Salasanan vahvistus" aria-describedby="sizing-addon3">
                        </div>
                    </div>
                    <div class="row form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Vaihda salasana</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
