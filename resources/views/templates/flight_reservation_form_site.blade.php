@php
    $medias= $flight_reservation->getMedia('passengers');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Flight Requisition Form</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{asset('bootstrap3.min.css')}}" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('bootstrap-extension.css')}}" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <style>
        p {
            font-family: Arial;
            /*font-size: 10pt*/
        }
        .starter-template {
            padding: 3rem 1.5rem;
            text-align: center;
        }
        .panel-heading {
            background-color: #808285;
            padding: .25rem!important;
            color: white;
        }
        .small-panel-heading {
            background-color: #808285;
            color: white;
        }
        .main_content {
            font-weight: bold;
            font-size: 14pt !important;
        }
        .signature {
            font-weight: bolder;
        }
        .footer p{
            font-family:Arial;
            font-size: 10pt;
            font-weight: bold;
            margin-left: .25rem!important;
            margin-bottom: .25rem!important;
        }
        .panel-warning,.panel-warning .border {
            border-color: #ddb21e !important;
        }
        .extra-border {
            border: 2px solid #ddb21e !important;
        }
        .panel-heading {
            background-color: #808285 !important;
            color: white !important;
        }
        .heading {
            font-style: italic;font-weight: 600
        }
        @page {
            margin: 0;  /* this affects the margin in the printer settings */
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>

<body style="background-color: white !important; font-size: small">
<main role="main" class="container">
    <div class="starter-template">
        <div class="row">
            <div class="col-xs-9 text-left">
                <p class="mb-1" style="font-family: Arial black; font-size: 22pt;font-weight: bolder">FLIGHT RESERVATION FORM<BR>PROJECT SITE</p>
                <p style="font-family:Myriad Pro; font-size: 12pt;font-style: italic;font-weight: bold">To : TRAVEL DESK</p>
            </div>
            <div class="col-xs-3 text-right">
                <img src="{{asset('images/logo_forms.png')}}" class="img-responsive" alt="Responsive image">
                <div style="font-family:Arial; font-size: 10pt">(325709-V)</div>
            </div>
        </div>

        {{--START OF THE FORM--}}
        <p style="font-family:Myriad Pro; font-size: 10pt;font-style: italic" class="text-left">PARTICULARS OF APPLICANT</p>
        <div class="row">
            <div class="col-xs-7 text-left ">

                {{--first panel--}}
                <div class="row mx-0">
                    <div class="col-xs-12 panel panel-warning p-0">
                        <div class="panel-body p-0">
                            <div class="row mx-0 border rounded-top">
                                <div class="col-xs-4 small-panel-heading rounded-top">
                                    <p class="mb-0 heading">NAME</p>
                                </div>
                                <div class="col-xs-8 text-left">
                                    <p class="mb-0 heading">{{ucwords($flight_reservation->requester->employee->full_name)}}</p>
                                </div>
                            </div>
                            <div class="row mx-0 border">
                                <div class="col-xs-4 small-panel-heading">
                                    <p class="mb-0 heading">DESIGNATION</p>
                                </div>
                                <div class="col-xs-8 text-left">
                                    <p class="mb-0"></p>
                                </div>
                            </div>
                            <div class="row mx-0 border rounded-bottom">
                                <div class="col-xs-4 small-panel-heading rounded-bottom">
                                    <p class="mb-0 heading">DEPARTMENT / PROJECT</p>
                                </div>
                                <div class="col-xs-8 text-left d-flex" style="align-items: center; flex-wrap: wrap">
                                    <p class="mb-0">{{ucwords($flight_reservation->requester->employee->department->name)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{--second panel--}}
                <div class="row mx-0">
                    <div class="col-xs-12 panel panel-warning p-0">
                        <div class="panel-body p-0">
                            <div class="row mx-0 border rounded-top">
                                <div class="col-xs-4 small-panel-heading rounded-top">
                                    <p class="mb-0 heading">DATE REQUEST</p>
                                </div>
                                <div class="col-xs-8 text-left border-bottom">
                                    <p class="mb-0">{{$flight_reservation->created_at->format('Y-m-d')}}</p>
                                </div>
                            </div>
                            <div class="row mx-0 border">
                                <div class="col-xs-4 small-panel-heading">
                                    <p class="mb-0 heading">EXT NO.</p>
                                </div>
                                <div class="col-xs-8 text-left">
                                    <p class="mb-0"></p>
                                </div>
                            </div>
                            <div class="row mx-0 border rounded-bottom">
                                <div class="col-xs-4 small-panel-heading rounded-bottom">
                                    <p class="mb-0 heading">FOR ACCT. OF</p>
                                </div>
                                <div class="col-xs-8 text-center">
                                    <div class="row">
                                        <div class="col-xs-5 px-0">Head Office</div>
                                        <div class="col-xs-1 px-0"></div>
                                        <div class="col-xs-5 px-0">Project</div>
                                        <div class="col-xs-1 px-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mx-0">
                    <div class="col-xs-12">
                        <ul class="p-1 mb-0"><li><p style="font-family:Myriad Pro; font-size: 12pt; font-weight: bolder;font-style: italic" class="mb-0"> FLIGHT DETAIL REQUEST <span style="font-family:Myriad Pro; font-size: 10pt">(Please fill up this from accordingly)</span></p></li></ul>
                    </div>
                </div>
                <div class="row mx-0">
                    <div class="col-xs-6 panel panel-warning">
                        <div class="panel-body p-0">
                            <div class="row text-center">
                                @if($flight_reservation->flight_type === 'RETURN')
                                    <div class="col-xs-8 border heading">Return</div>
                                    <div class="col-xs-4 border">&#x2714;</div>
                                @else
                                    <div class="col-xs-8 border heading">One Way</div>
                                    <div class="col-xs-4 border">&#x2714;</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- right side -->
            <div class="col-xs-5 text-left">
                <div class="row panel panel-warning">
                    <div class="col-xs-12 border">
                        <p style="font-family:Myriad Pro; font-size: 11pt; font-weight: bolder" class="mb-0 pl-2 pr-2 pb-3 pt-1">SITE: &nbsp;{{$flight_reservation->form_target}}</p>
                    </div>
                </div>
                <div class="row panel">
                    <div class="col-xs-12 p-0">
                        <div class="text-left panel-heading">
                            <div class="row text-left mt-1 ml-1 mr-1" style="font-family:Arial; font-style: italic; font-size: 8pt">
                                <div class="col-xs-12 p-0">** important Notice :</div>
                            </div>
                            <div class="row text-left mt-1 ml-1 mr-1" style="font-family:Arial; font-style: italic; font-size: 8pt">
                                <div class="col-xs-2 p-0 font-weight-bold">HQ</div>
                                <div class="col-xs-10 pr-0">The Form requires the authorised signatory of at least any one(1) of the Director or Executive Directors or Executive chairman(Group 1)</div>
                            </div>
                            <div class="row text-left mt-1 ml-1 mr-1" style="font-family:Arial; font-style: italic; font-size: 8pt">
                                <div class="col-xs-2 p-0 font-weight-bold">Site Office</div>
                                <div class="col-xs-10 pr-0">either two(2) General managers <b>OR</b> one(1) General manager & one(1) Project Manager(Group2)</div>
                            </div>
                            <div class="row text-left mt-1 ml-1 mr-1 mb-1" style="font-family:Arial; font-style: italic; font-size: 8pt">
                                <div class="col-xs-12 p-0"><b>*** Without either one(1) of the GROUP signature, Reservation and / or payment will not be made or will not be approved.</b></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{--second panel group--}}
        <div class="row">
            <div class="col-xs-6">
                <div class="panel panel-warning text-left">
                    <div class="row mx-0 border rounded-top">
                        <div class="col-xs-5 small-panel-heading rounded-top">
                            <p class="mb-0 heading">DEPARTURE DATE</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{$flight_reservation->departure_date}}</p>
                        </div>
                    </div>
                    <div class="row mx-0 border">
                        <div class="col-xs-5 small-panel-heading">
                            <p class="mb-0 heading">TIME</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{$flight_reservation->departure_period}}</p>
                        </div>
                    </div>
                    <div class="row mx-0 border">
                        <div class="col-xs-5 small-panel-heading">
                            <p class="mb-0 heading">DESTINATION</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{ucwords($flight_reservation->departure_to)}}</p>
                        </div>
                    </div>
                    <div class="row mx-0 border rounded-bottom">
                        <div class="col-xs-5 small-panel-heading rounded-bottom">
                            <p class="mb-0 heading">LUGGAGE</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{$flight_reservation->departure_luggage}}</p>
                        </div>
                    </div>
                </div>
            </div>


            @if($flight_reservation->flight_type === 'RETURN')
                <div class="col-xs-6">
                <div class="panel panel-warning text-left">
                    <div class="row mx-0 border rounded-top">
                        <div class="col-xs-5 small-panel-heading rounded-top">
                            <p class="mb-0 heading">RETURN DATE</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{$flight_reservation->return_date}}</p>
                        </div>
                    </div>
                    <div class="row mx-0 border">
                        <div class="col-xs-5 small-panel-heading">
                            <p class="mb-0 heading">TIME</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{$flight_reservation->return_period}}</p>
                        </div>
                    </div>
                    <div class="row mx-0 border">
                        <div class="col-xs-5 small-panel-heading">
                            <p class="mb-0 heading">DESTINATION</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{ucwords($flight_reservation->return_to)}}</p>
                        </div>
                    </div>
                    <div class="row mx-0 border rounded-bottom">
                        <div class="col-xs-5 small-panel-heading rounded-bottom">
                            <p class="mb-0 heading">LUGGAGE</p>
                        </div>
                        <div class="col-xs-7">
                            <p class="mb-0">{{$flight_reservation->return_luggage}}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{--THIRD GORUP OF panelS--}}
        @if($flight_reservation->transport_type)
            <div class="row text-left m-1">
                <div class="col-xs-12">
                    <ul class="p-1 mb-1"><li><p style="font-family:Myriad Pro; font-size: 13pt; font-weight: bolder;font-style: italic" class="mb-0">IF NECESSARY</p></li></ul>
                </div>
            </div>

            <!-- trip Details  -->
            <div class="row">
                <div class="col-xs-3" style="padding-right: 1rem">
                    <div class="panel panel-warning">
                    <div class="panel-heading border"><p class="mb-0 heading">TRANSPORT:</p></div>
                    <div class="panel-body border">
                        {{$flight_reservation->transport_type}}
                    </div>
                    {{--<div class="panel-body signature">GENERATED AUTOMATICALLY</div>--}}
                    {{--<div class="footer text-left"><p>NAME: {{$cash_requisition->requester->name}}<br>DATE: {{$cash_requisition->created_at->format('Y-m-d')}}</p></div>--}}
                </div>
                </div>
                <div class="col-xs-6" style="padding-right: 1rem;padding-left: 1rem">
                    <div class="panel panel-warning">
                    <div class="panel-heading border"><p class="mb-0 heading">DESTINATION:</p></div>
                    <div class="panel-body text-left border">
                        <div class="row">
                            <div class="col-xs-6">FROM: {{$flight_reservation->transport_from}}</div>
                            <div class="col-xs-6">TO: {{$flight_reservation->transport_to}}</div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="col-xs-3" style="padding-left: 1rem">
                    <div class="panel panel-warning">
                    <div class="panel-heading border"><p class="mb-0 heading">TOTAL (RM):</p></div>
                    <div class="panel-body border">{{$flight_reservation->transportation_cost}}&nbsp;</div>
                </div>
                </div>
            </div>
        @endif

        <div class="row text-left ml-1 ">
            <div class="col-xs-12">
                <ul class="p-1 mb-1 mt-3"><li><p style="font-family:Myriad Pro; font-size: 13pt; font-weight: bolder;font-style: italic" class="mb-0">PARTICULARS OF PERSON TRAVELING</p></li></ul>
            </div>
        </div>

        <!-- passenger details -->
        @foreach($medias as $media)
            <div class="row">
                <div class="col-xs-8 mb-0 pr-0">
                    <div class="panel panel-warning extra-border mb-0">
                        <div class="row m-0">
                            <div class="col-xs-4 small-panel-heading text-left heading">
                                <p class="mb-0">NAME:</p>
                            </div>
                            <div class="col-xs-8 text-left">
                                <p class="mb-0">{{$media->getCustomProperty('name')}}</p>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xs-4 small-panel-heading text-left heading">
                                <p class="mb-0">DESIGNATION</p>
                            </div>
                            <div class="col-xs-8 text-left">
                                <p class="mb-0">{{$media->getCustomProperty('designation')}}</p>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xs-4 small-panel-heading text-left heading">
                                <p class="mb-0">MyKad/PASSPORT</p>
                            </div>
                            <div class="col-xs-4 text-left">
                                <p class="mb-0">{{$media->getCustomProperty('passport')}}</p>
                            </div>
                            <div class="col-xs-4 text-left" style="font-size: 12px">
                                <p class="mb-0 "><span class="heading">EXPIRY DATE:</span> {{$media->getCustomProperty('expiry')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4 pl-0">
                    <div class="panel panel-warning extra-border mb-0 extra-border">
                        <div class="row m-0">
                            <div class="col-xs-7 text-left heading">
                                <p class="mb-0">NATIONALITY:</p>
                            </div>
                            <div class="col-xs-5 text-left">
                                <p class="mb-0">{{$media->getCustomProperty('nationality')}}</p>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xs-7 text-left heading">
                                <p class="mb-0">DATE OF BIRTH</p>
                            </div>
                            <div class="col-xs-5 text-left">
                                <p class="mb-0">{{$media->getCustomProperty('dob')}}</p>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-xs-7 text-left heading">
                                <p class="mb-0">HANDPHONE NO:</p>
                            </div>
                            <div class="col-xs-5 text-left">
                                <p class="mb-0">{{$media->getCustomProperty('hp')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- purpose -->
        <div class="row mt-2">
            <div class="col-xs-12">
                <div class="panel panel-warning text-left extra-border">
                    <p style="font-family:Myriad Pro; font-size: 11pt; font-weight: bolder" class="mb-0 pl-2 pr-2 pt-1"><span class="heading">PURPOSE:</span> <span style="font-family:Myriad Pro; font-size: 9pt">(STRICTLY TO BE FILLED UP)</span></p>
                    <p class="mb-0 pl-2 pr-2">{{$flight_reservation->purpose}}</p>
                </div>
            </div>
        </div>


        <!-- APPROVAL -->
        <div class="row">
            <!-- first colomn -->
            <div class="col-xs-4">
                <div class="panel panel-warning">
                    <div class="panel-heading border"><p class="mb-0 heading">REQUESTED BY</p></div>
                    <div class="panel-body signature border pt-2">
                        <div style="font-family:Arial; font-style: italic; font-size: 8pt" class="mb-1 pt-0">&nbsp;</div>
                        GENERATED AUTOMATICALLY<br><br>

                        <div class="footer text-left">
                            <p>NAME: {{ucwords($flight_reservation->requester->name)}}
                                <br>DATE: {{$flight_reservation->created_at->format('Y-m-d')}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- second colomn -->
            <div class="col-xs-4">
                <div class="panel panel-warning" style="border-left: 1px solid #ddb21e">
                    <div class="panel-heading border"><p class="mb-0 heading">CERTIFIED BY</p></div>

                    <div class="panel-body signature border pt-2">
                        <div style="font-family:Arial; font-style: italic; font-size: 8pt" class="mb-1 pt-0">IMMEDIATE SUPERIOR</div>
                        GENERATED AUTOMATICALLY<br><br>
                        @php
                            $name = '';
                            $date = '';
                            $t = $flight_reservation->transition('ceo_approving');
                            if($t){
                                $name = $t->user->name;
                                $date = $t->in_date;
                            }
                        @endphp

                        <div class="footer text-left">
                            <p>NAME: {{$name}}
                                <br>DATE: {{$date}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Third colomn -->
            <div class="col-xs-4">
                <div class="panel panel-warning" style="border-left: 1px solid #ddb21e">
                    <div class="panel-heading border"><p class="mb-0 heading">APPROVED BY</p></div>
                    <div class="panel-body signature border pt-2">
                        <div style="font-family:Arial; font-style: italic; font-size: 8pt" class="mb-1 pt-0">DIRECTOR/CEO/GCEO</div>
                        GENERATED AUTOMATICALLY<br><br>
                        @php
                            $name = '';
                            $date = '';
                            $t = $flight_reservation->transition('budgeting');
                            if($t){
                                $name = $t->user->name;
                                $date = $t->in_date;
                            }
                        @endphp

                        <div class="footer text-left">
                            <p>NAME: {{$name}}
                                <br>DATE: {{$date}}
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <!-- footer -->

        <div class="row text-left" style="font-family:Arial; font-style: italic; font-size: 7pt">
            <div class="col-xs-10">** Person who request for cancellation of flight (without valid reason) shall be liable to the Cancellation or administrative Fees charged be the Air Career, if any.</div>
            <div class="col-xs-2 text-right">DOA : 04-04-2016</div>
        </div>
    </div>
</main><!-- /.container -->
</body>
</html>
