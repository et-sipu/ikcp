@extends('mail_templates::minty')

@section('content')

    @include('mail_templates::minty.contentStart')
<body style="background-color: white !important;">
@if(count($expired))
    <h3> Expired Table</h3>
    <table class="table table-bordered table-primary">
        <tbody>
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Insurance</th>
            <th scope="col" >type</th>
            {{--<th scope="col">Vessels</th>--}}
            <th scope="col">Expiry Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($expired as $insurance)
            <tr>
                <td>{{ $insurance['id'] }}</td>
                <td>{{ $insurance['name'] }}</td>
                <td>{{ $insurance['type'] }}</td>
                {{--@dd($insurance)--}}
                {{--<td>{{ $insurance['vessels'] }}</td>--}}
                {{--<td>--}}
                    {{--<table>--}}
                        {{--@foreach($insurance as $vessel)--}}
                            {{--<tr>--}}
                                {{--<td>--}}
                                    {{--{{ $vessel['$vessel_covered']['name'] }}--}}
                                {{--</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                    {{--</table>--}}
                {{--</td>--}}
                <td>{{ $insurance['expiry_date'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
@endif
@if(count($warning))
    <h3> Warning Table</h3>
    <table class="table table-bordered table-danger">
        <tbody>
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Insurance</th>
            <th scope="col" >type</th>
            {{--<th scope="col">Vessels</th>--}}
            <th scope="col">Expiry Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($warning as $insurance)
            <tr>
                <td>{{ $insurance['id'] }}</td>
                <td>{{ $insurance['name'] }}</td>
                <td>{{ $insurance['type'] }}</td>
                {{--<td>{{ $insurance['vessels'] }}</td>--}}
                <td>{{ $insurance['expiry_date'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
@endif

    @include('mail_templates::minty.contentEnd')

@stop


