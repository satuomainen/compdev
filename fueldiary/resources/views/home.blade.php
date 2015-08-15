@extends('layouts.master')

@section('navigation')
    @include('common.navbar')
@endsection

@section('scripts')
    <script>
        var showAddVehicleForm = function(event) {
            event.preventDefault();
            $('#add-vehicle-form-container').show();
            $(this).hide();
        };

        var submitAddVehicleForm = function(event) {
            event.preventDefault();
            var postUrl = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url: postUrl,
                data: data,
                method: 'POST',
                success: function(data, textStatus, jqXHR) {
                    $.get(document.location, reloadPageAndReplaceVehicles);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // TODO:
                }
            });
        };

        var reloadPageAndReplaceVehicles = function(response) {
            var newVehicles = $(response).find('.row');
            $('.row').remove();
            $('.container').prepend(newVehicles);
        };

        var cancelAddVehicleForm = function(event) {
            event.preventDefault();
            $('#add-vehicle-form-container').hide();
            $('#add-vehicle-button').show();
        };

        $('#add-vehicle-button').click(showAddVehicleForm);
        $('#add-vehicle-form').submit(submitAddVehicleForm);
        $('#cancel-add-vehicle-button').click(cancelAddVehicleForm);
    </script>
@endsection

@section('content')

    @inject('averageConsumptionService', 'App\Services\AverageConsumptionService')

    @foreach ($vehicles as $vehicle)
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        {{ $vehicle->registration }}
                    </h3>
                </div>
                <div class="panel-body">
                    <a href="/add-fillup/{{ $vehicle->id }}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus"></span></a>
                    @if (count($vehicle->fillups) > 0)
                        <p class="statistic">
                            <span class="glyphicon glyphicon-road"></span>
                            <span class="quantity">
                                {{ number_format($averageConsumptionService->getAverageConsumption($vehicle->id), 2) }} litraa/100 km
                            </span>
                        </p>
                        <p class="statistic">
                            <span class="glyphicon glyphicon-tint"></span>
                            <span class="quantity">
                                {{ date('d.m.Y', strtotime($vehicle->fillups[0]->fillup_date)) }}
                                | {{ number_format($vehicle->fillups[0]->litres, 2) }} litraa
                                | {{ number_format($vehicle->fillups[0]->amount_paid, 2) }} €
                            </span>
                            <!--
                            <span class="quantity">30.12.2015 | 99,99 litraa | 999,99 €</span>
                            -->
                        </p>
                    @else
                        <p>Ei vielä merkittyjä tankkauksia</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <div id="add-vehicle-form-container" class="row" style="display: none;">
        <div class="panel panel-default">
            <div class="panel-heading"><h3 class="panel-title">Lisätään auto</h3></div>
            <div class="panel-body">
                <form id="add-vehicle-form" method="POST" action="/api/vehicle">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="registration">Rekisterinumero</label>
                        <input id="registration" class="form-control" type="text" name="registration" >
                    </div>
                    <button type="submit" class="btn btn-success btn-lg">Tallenna</button>
                    <button id="cancel-add-vehicle-button" type="button" class="btn btn-warning btn-lg">Peru</button>
                </form>
            </div>
        </div>
    </div>

    <button id="add-vehicle-button" class="btn btn-success btn-lg">Lisää auto</button>

@endsection