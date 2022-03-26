<html>
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body style="background-color: white !important;">
        <table style="background-color: red;">
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
                        <td>{{ $warning->document_type }}</td>
                        <td>{{ $warning->document_expiry_date }}</td>
                        <td>{{ $warning_left}}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
        <table class="table-striped table-danger">
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
                        <td>{{ $critical->document_type }}</td>
                        <td>{{ $critical->document_expiry_date }}</td>
                        <td>{{ $critical_left}}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
   </body>

</html>
