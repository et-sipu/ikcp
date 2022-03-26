<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('bootstrap.min.css')}}" crossorigin="anonymous">
    <title>TICKET BUDGET</title>
</head>
<body style="background-color: white !important;font-size: 15px">
<div class="m-3" style="text-align: center;">
    <H3 style="text-align:center"> BUDGETING FOR FLIGHT / BUS/ CAB / TRAIN/ FERRY/ HOTEL</H3>
    <table class="table table-bordered">
        <thead>
        <tr class="bg-light">
            <th width="5%">NO</th>
            <th width="15%">Passengers</th>
            <th width="7%">Position</th>
            <th width="7%">Location</th>
            <th width="7%">Purpose</th>
            <th width="50%">Trips</th>
            <th width="5%">EST. Price</th>
            </th>
        </tr>
        </thead>
        <tbody>
    @foreach($flight_reservation_list as $ft)
        @php
        $total_price=0;
        $medias= $ft->getMedia('passengers');
        $quotations= $ft->quotations;
        $trips=[];
        @endphp
        <tr>
            <td scope="row">{{$ft->id}}</td>
            <td>
                @php
                    $medias->each(function($media)
                            {{
                                echo "Name: ";
                                echo $media->getCustomProperty('name');
                                echo "<br>";
                            }})

                @endphp

            </td>
             <td>
                    @foreach($medias as $media)

                            {{ $media->getCustomProperty('designation')}}
                            <br>

                    @endforeach
             </td>
            <td>
                @if($ft->form_type=='VESSEL')
                    {{$ft->vessel->name}}
                @else
                    {{$ft->form_target}}
                @endif</td>
             <td>{{$ft->purpose}}</td>
             <td class="p-0">
                <table style="width: 100%;border-style: hidden">
                        <tbody>
                            @foreach($ft->trip_item as $trip)
                                <tr>

                                    <td width="25%">
                                        {{$trip->trip_type}}
                                    </td>


                                  @if($trip->trip_type == 'BUS' || $trip->trip_type == 'TRAIN' || $trip->trip_type == 'CAB' || $trip->trip_type == 'FERRY' )
                                      <td width="50%" style="font-size: 20px">
                                           {{$trip->trip_attributes['transport_from']}}
                                          <span style='font-size:20px; font-weight: bolder;'>&#8608</span> {{$trip->trip_attributes['transport_to']}}<br>
                                          {{$trip->trip_attributes['departure_date']}}
                                      </td>

                                  @elseif($trip->trip_type == 'FLIGHT')
                                      @if($trip->trip_attributes['flight_type']=='ONEWAY')
                                        <td width="50%" style="font-size: 20px;">
                                            {{$trip->trip_attributes['departure_from']}}&nbsp<span style='font-size:20px; font-weight: bolder;'>&#8608</span>
                                             {{$trip->trip_attributes['departure_to']}}<br>
                                            {{$trip->trip_attributes['departure_date']}}
                                        </td>
                                      @else
                                        <td width="50%" style="font-size: 20px;">
                                            {{$trip->trip_attributes['departure_from']}}<span style='font-size:20px; font-weight: bolder;'>&#8646</span>
                                            {{$trip->trip_attributes['departure_to']}}<br>
                                            {{$trip->trip_attributes['departure_date']}}
                                        </td>
                                      @endif

                                  @elseif($trip->trip_type == 'HOTEL')
                                        <td width="50%" style="font-size: 20px">
                                            CheckIn {{$trip->trip_attributes['check_in']}}<br>
                                            For {{$trip->trip_attributes['nights']}} Nights
                                        </td>

                                  @endif
                                  <td>
                                     {{isset($trip->trip_attributes['price']) ? $trip->trip_attributes['price'] : 0}}<br>
                                      @php $total_price += isset($trip->trip_attributes['price']) ? $trip->trip_attributes['price'] : 0;@endphp
                                  </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </td>
            <td>
                {{$total_price}}
            </td>
        </tr>
    @endforeach
        </tbody>
    </table></div>
</body>
</html>
<style>
    table td {
        text-align: center;
        vertical-align: middle !important;
        padding: 5px;
        position: relative;
    }

</style>