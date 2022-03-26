@extends('mail_templates::minty')

@section('content')

    @include('mail_templates::minty.contentStart')

    <body style="background-color: white !important;">
        @if(count($warnings))

            <h3> Warning Table</h3>
            <table class="table table-bordered table-warning">
                <tbody>
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col" >Document Type</th>
                    <th scope="col">Expiry Date</th>
                    <th scope="col">Days Left</th>
                </tr>
                </thead>
                <tbody>
                @foreach($warnings as $vessel_name => $vessel_warning)
                    @foreach($vessel_warning as $warning)
                        <tr>
                            <td>{{$vessel_name}} </td>
                            <td>{{ $warning['certificate']['document_type'] }}</td>
                            <td>{{ $warning['certificate']['document_expiry_date'] }}</td>
                            <td>{{ $warning['differ'] }} Days</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <br>
        @endif

        @if(count($critical))

            <h3> Critical Table</h3>
            <table class="table table-bordered table-danger">
                <tbody>
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Document Type</th>
                    <th scope="col">Expiry Date</th>
                    <th scope="col">Days Left</th>
                </tr>
                </thead>
                <tbody>
                @foreach($critical as $vessel_name => $vessel_critical)
                    @foreach($vessel_critical as $critical)
                        <tr>
                            <td>{{$vessel_name}} </td>
                            <td>{{ $critical['certificate']['document_type'] }}</td>
                            <td>{{ $critical['certificate']['document_expiry_date'] }}</td>
                            <td>{{ $critical['differ'] }} Days</td>

                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <br>
        @endif

        @if(count($expired))

            <h3>Pending Renwal. Certificates already Expired</h3>

            <table class="table table-bordered table-primary">
                <tbody>
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Document Type</th>
                    <th scope="col">Expiry Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($expired as $vessel_name => $vessel_expired)
                    @foreach($vessel_expired as $expired)
                        <tr>
                            <td>{{$vessel_name}} </td>
                            <td>{{ $expired->document_type }}</td>
                            <td>{{ $expired->document_expiry_date }}</td>

                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        @endif
    </body>


    @include('mail_templates::minty.contentEnd')

@stop