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
            <th scope="col">Registration</th>
            <th scope="col" >Registration No </th>
            <th scope="col">Company</th>
            <th scope="col">Validity to</th>
        </tr>
        </thead>
        <tbody>
        @foreach($expired as $document)
            <tr>

                <td>{{ $document['registration'] }}</td>
                <td>{{ $document['registration_no'] }}</td>
                <td>{{ $document['company'] }}</td>
                <td>{{ $document['validity_to'] }}</td>
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
            <th scope="col">Registration</th>
            <th scope="col" >Registration No </th>
            <th scope="col">Company</th>
            <th scope="col">Validity to</th>
        </tr>
        </thead>
        <tbody>
        @foreach($warning as $document)
            <tr>

                <td>{{ $document['registration'] }}</td>
                <td>{{ $document['registration_no'] }}</td>
                <td>{{ $document['company'] }}</td>
                <td>{{ $document['validity_to'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
@endif

    @include('mail_templates::minty.contentEnd')

@stop


