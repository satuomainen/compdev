@extends('layouts.master')

@section('content')

    <div class="row">
        <h1>Tankkeri</h1>
    </div>

    <div class="row">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="panel panel-default login-panel">
            <div class="panel-heading">
                <h3 class="panel-title">Salasanan palauttaminen</h3>
            </div>
            <div class="panel-body">
                <form method="POST" action="/password/email">
                    {!! csrf_field() !!}
                    <p>Anna sähköpostiosoitteesi, niin lähetämme sinulle salasanan palautuslinkin</p>
                    <div class="row">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon" id="sizing-addon1"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                            <label for="email" class="sr-only">Sähköpostiosoite</label>
                            <input id="email" name="email" type="email" class="form-control" placeholder="Sähköpostiosoite" aria-describedby="sizing-addon1" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="row form-group">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">Lähetä palautuslinkki</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
