@extends('layouts.master')

@section('navigation')
    @include('common.navbar')
@endsection

@section('scripts')
    <script>
        var reloadPageAndReplaceFillupTable = function(response) {
            event.preventDefault();
            var updatedTable = $(response).find('#fillups-table');
            $('#fillups-table').replaceWith(updatedTable);
        };

        var submitAddFillupForm = function(event) {
            event.preventDefault();
            var postUrl = $(this).attr('action');
            var data = $(this).serialize();
            $.ajax({
                url: postUrl,
                data: data,
                method: 'POST',
                success: function(data, textStatus, jqXHR) {
                    document.location = '/home';
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error', errorThrown);
                }
            });
        };

        var deleteFillup = function(event) {
            event.preventDefault();
            var deleteUrl = $(this).attr('href');
            var data = $(this).serialize();
            $.ajax({
                url: deleteUrl,
                data: data,
                method: 'DELETE',
                success: function(data, textStatus, jqXHR) {
                    $.get(document.location, reloadPageAndReplaceFillupTable);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('error', errorThrown);
                }
            });
        };

        $('#add-fillup-form').submit(submitAddFillupForm);
        $('body').on('click', '.delete-fillup-link', deleteFillup);
    </script>
@endsection

@section('content')

    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    {{ $vehicle->registration }}
                </h3>
            </div>
            <div class="panel-body">

                <form id="add-fillup-form" method="POST" action="/api/vehicle/{{ $vehicle->id }}/fillup">
                    {!! csrf_field() !!}
                    <input type="hidden" value="{{ $vehicle->registration }}">
                    <div class="form-group">
                        <label for="">Tankkauspäivä</label>
                        <input id="fillup_date" class="form-control" type="date" name="fillup_date" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Litrat</label>
                        <input id="litres" class="form-control" type="number" min="0.0" step="0.01" name="litres" required>
                    </div>
                    <div class="form-group">
                        <label for="">Eurot</label>
                        <input class="form-control" type="number" min="0.0" step="0.01" name="amount_paid" required>
                    </div>
                    <div class="form-group">
                        <label for="">Mittarilukema</label>
                        <input class="form-control" type="text" pattern="[0-9]*" name="mileage" required>
                    </div>

                    <button type="submit" class="btn btn-success">Tallenna</button>
                    <a href="/home" class="btn btn-danger">Peru</a>
                </form>

            </div>
        </div>
    </div>

    <div id="fillups-table">
        @if (count($vehicle->fillups) > 0)
            @inject('averageConsumptionService', 'App\Services\AverageConsumptionService')
            <div class="table-responsive">
                <table class="table table-bordered fillups">
                    <thead>
                    <tr>
                        <th>Pvm</th>
                        <th>Litrat</th>
                        <th>Eurot</th>
                        <th>l/100</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicle->fillups as $fillup)
                            <tr>
                                <td>{{ date('d.m.Y', strtotime($fillup->fillup_date)) }}</td>
                                <td>{{ number_format($fillup->litres, 2) }}</td>
                                <td>{{ number_format($fillup->amount_paid, 2) }}</td>
                                <td>{{ number_format($averageConsumptionService->getFillupConsumption($fillup->id), 1) }}</td>
                                <th>
                                    <a class="delete-fillup-link" href="/api/vehicle/{{ $vehicle->id }}/fillup/{{ $fillup->id }}">
                                        <span class="glyphicon glyphicon-trash text-danger"></span>
                                    </a>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection


