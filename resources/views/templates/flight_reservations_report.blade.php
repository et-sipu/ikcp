@php
    $counter=0;
    $total=0;
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('bootstrap3.min.css')}}" crossorigin="anonymous">

    <title>Document</title>
    <style type="text/css">
        table { page-break-inside:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
    </style>
</head>
<body style="background-color: white !important;font-size: 13px">
<div class="text-center" style="margin-top: 10px;font-size: large;font-weight: bolder">
    SUMMARY FOR FLIGHT / BUS/ CAB / TRAIN/ FERRY/ HOTEL
</div>
<div  class="m-3" style="text-align: center; margin: 10px">
    <table class="table table-md table-striped table-bordered">
{{--        <thead>--}}
        <tr>
            <th width="5%">NO</th>
            <th width="18%">Passengers / Position</th>
            <th width="5%">Location</th>
            <th width="7%">Purpose</th>
            <th width="35%">Trips</th>
            <th width="8%">EST. Price</th>
            <th width="8%">ACT. Price</th>
            <th width="8%">Deduction</th>
            <th width="9%">REMARK</th>
        </tr>
{{--        </thead>--}}
        <tbody>
        @foreach($flight_reservation_list as $ft)
            @php
                $total_price=0;
                $medias= $ft->getMedia('passengers');
                // $quotations= $ft->quotations;
                $trips=[];
            @endphp
            <tr>
                <td>{{$ft->id}}</td>
                <td style="text-align: left">
                    @php
                        $medias->each(function($media)
                                {
                                    echo "- ".$media->getCustomProperty('name') . "<span style='font-weight: bold;font-style: italic'> (".$media->getCustomProperty('designation').")</span><br>";
                                });

                    @endphp
                </td>
                <td>
                    @if($ft->form_type=='VESSEL')
                    {{$ft->vessel->name}}
                    @else
                    {{$ft->form_target}}
                    @endif

                </td>
                <td>{{$ft->purpose}}</td>
                <td class="p-0">
                    <table style="width: 100%;border-style: hidden">
                        <tbody>
                        @foreach($ft->trip_item as $trip)
                            <tr>

                                <td width="25%">
                                    {{$trip->trip_type}}
                                </td>

                                <td width="50%" style="font-weight: bold;font-style: italic">
                                    @if($trip->trip_type == 'BUS' || $trip->trip_type == 'TRAIN' || $trip->trip_type == 'CAB' || $trip->trip_type == 'FERRY' )
                                            {{$trip->trip_attributes['transport_from']}}
                                            <span>&#8608</span> {{$trip->trip_attributes['transport_to']}}<br>
                                            {{$trip->trip_attributes['departure_date']}}

                                    @elseif($trip->trip_type == 'FLIGHT')
                                        @if($trip->trip_attributes['flight_type']=='ONEWAY')
                                                {{$trip->trip_attributes['departure_from']}}&nbsp<span>&#8608</span>
                                                {{$trip->trip_attributes['departure_to']}}<br>
                                                {{$trip->trip_attributes['departure_date']}}
                                        @else
                                                {{$trip->trip_attributes['departure_from']}}&nbsp<span>&#8646</span>
                                                {{$trip->trip_attributes['departure_to']}}<br>
                                                {{$trip->trip_attributes['departure_date']}}
                                        @endif

                                    @elseif($trip->trip_type == 'HOTEL')
                                            CheckIn {{$trip->trip_attributes['check_in']}}<br>
                                            For {{$trip->trip_attributes['nights']}} Nights
                                    @endif
                                </td>

                                <td>
                                    {{isset($trip->trip_attributes['price']) ? $trip->trip_attributes['price'] : 0}}<br>
                                    @php
                                        $total_price += isset($trip->trip_attributes['price']) ? $trip->trip_attributes['price'] : 0;
                                    @endphp
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </td>
                <td>
                    {{number_format($total_price * $medias->count(),2)}}
                </td>
                <td>{{number_format(($ft->price?:0) * $medias->count(),2)}}</td>
                <td>{{number_format(($ft->deduction? (rtrim($ft->deduction , '%') == $ft->deduction ? rtrim($ft->deduction , '%') : (($ft->price) ? ($ft->price * rtrim($ft->deduction , '%') / 100) : null)) :0) * $medias->count(),2)}}</td>
                <td></td>
            </tr>
            @php
                $total += $ft->price * $medias->count();
            @endphp
        @endforeach
        </tbody>
    </table>
</div>
<table WIDTH="100%">
    <tr><td style="text-align: left">
            <div class="row">
                <div class="col-xs-4">
                    Prepare by<br><br><br>
                    Name: {{is_object(auth()->user()) && isset(auth()->user()->name) ? auth()->user()->name : ''}}<br>
                    Date: {{date('Y-m-d')}}
                </div>
                <div class="col-xs-4">
                    Verify By<br><br><br>
                    Name:<br>
                    Date:<br>
                </div>
                <div class="col-xs-4" style="padding-left: 130px">
                    Total: <span style="margin-left: 10px;border-bottom: 3px double"> RM{{number_format($total,2)}}</span>
                </div>
            </div>

        </td></tr>
</table>
</body>
</html>
<style>
    table td, table th {
        text-align: center;
        vertical-align: middle !important;
        padding: 5px;
        position: relative;
    }

</style>
