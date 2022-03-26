<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Payment Requisition Form (#PRF{{str_pad($payment_requisition->id,8,'0',STR_PAD_LEFT)}})</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        p {
            font-family: Arial;
            font-size: 10pt
        }
        .starter-template {
            padding: 3rem 1.5rem;
            text-align: center;
        }
        .card-header {
            background-color: #808285;
            padding: .25rem!important;
            color: white;
        }
        .footer p{
            font-family:Arial;
            font-size: 10pt;
            font-weight: bold;
            margin-left: .25rem!important;
            margin-bottom: .25rem!important;        }
        .signature {
            font-weight: bolder;
        }
        .card {
            border: 1px solid #ddb21e;
        }
        li {
            border-top: 1px solid #ddb21e!important;
        }
        @page {
            margin: 0;  /* this affects the margin in the printer settings */
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>

<body>
    <main role="main" class="container">

        <div class="starter-template">
            <div class="row">
                <div class="col-md-9 text-left">
                    <p class="mb-1" style="font-family: Arial black; font-size: 30pt">PAYMENT REQUISITION FORM</p>
                    <p style="font-family:Myriad Pro; font-size: 13pt">(INTERNAL FORM FOR GROUP EXECUTIVE CHAIRMAN OFFICE)</p>
                </div>
                <div class="col-md-3 text-right">
                    <img src="{{asset('images/logo_forms.png')}}" class="img-fluid" alt="Responsive image">
                    <br><div style="font-family:Arial; font-size: 10pt">(325709-V)</div>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-5 pr-0">
                    <div class="row h-100">
                        <div class="col-md-12">
                            <div class="card border-bottom-0 border-right-0 h-50">
                                <div class="card-body text-left p-1">REQUESTED BY: {{ ucwords($payment_requisition->employee->full_name)  }}</div>
                            </div>
                            <div class="card border-right-0 h-50">
                                <div class="card-body text-left p-1">DESCRIPTION: {{ ucfirst($payment_requisition->title)  }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pl-0">
                    <div class="card h-100">
                        <div class="card-body text-left p-1">RELEASED TO:<br>{{ ucwords($payment_requisition->released_to)  }}<br></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0">
                        <div class="card-body p-0"><p>PRF {{ str_pad($payment_requisition->id,8,'0',STR_PAD_LEFT) }}</p></div>
                    </div>
                    <div class="card">
                        <div class="card-header" style="font-family:Arial; font-size: 10pt">REQUESTED DATE</div>
                        <div class="card-body p-1">{{$payment_requisition->created_at->format('Y-m-d')}}</div>
                    </div>
                </div>
            </div>
            <!-- form started -->
            <div class="row mb-2">
                <!-- requested by -->
                <div class="col-md-5 pr-1">
                    <div class="card h-50 mb-1">
                        <div class="card-header text-left" style="font-family:Arial; font-size: 10pt">PROPOSED FOR PAYMENT DETAILS</div>
                        <div class="card-body text-justify">{{ ucfirst($payment_requisition->details)  }}</div>
                    </div>
                    <div class="card h-50 mt-1">
                        <div class="card-header text-left" style="font-family:Arial; font-size: 10pt">REMARKS</div>
                        <div class="card-body text-justify">{{ ucfirst($payment_requisition->remarks)  }}</div>
                    </div>
                </div>
                <div class="col-md-7 pl-1">


                    <div class="card">
                        <div class="card-header text-left" style="font-family:Arial; font-size: 10pt">DETAILS</div>
                        <ul class="list-group list-group-flush">
                            <li class="border-top list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left pt-2 pb-2 pl-4">PROJECT</div>
                                    <div class="col-md-7 text-left">{{ ucfirst($payment_requisition->project)  }}</div>
                                </div>
                            </li>
                            <li class="list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left pt-2 pb-2 pl-4">NAME OF SUPPLIER</div>
                                    <div class="col-md-7 text-left">{{ ucfirst($payment_requisition->supplier)  }}</div>
                                </div>
                            </li>
                            <li class="list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left py-2 pl-4">CONTRACT SUM:<br>VO:<br>LAST PAYMENT AMOUNT / DATE: </div>
                                    <div class="col-md-7 text-left py-2">{{ $payment_requisition->contract_sum }}<br>{{ $payment_requisition->vo }}<br>{{ $payment_requisition->last_payment_amount . ' / ' . $payment_requisition->last_payment_date }}</div>
                                </div>
                            </li>
                            <li class="list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left py-2 pl-4">OUTSTANDING INVOICES</div>
                                    <div class="col-md-7 text-left">
                                        <div class="row">
                                            <div class="col px-0">
                                                <div class="card border-0 h-100">
                                                    <div class="card-header text-center" style="font-family:Arial; font-size: 10pt">NEW</div>
                                                    <div class="card-body">{{$payment_requisition->currency}}{{ $payment_requisition->new_outstanding_invoices }}</div>
                                                </div>
                                            </div>
                                            <div class="col px-0" style="margin-right: 11px">
                                                <div class="card border-right-0 border-top-0 border-bottom-0 h-100">
                                                    <div class="card-header text-center" style="font-family:Arial; font-size: 10pt">OLD</div>
                                                    <div class="card-body">{{ $payment_requisition->old_outstanding_invoices ?: 0 }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left pt-2 pb-2 pl-4">TOTAL OUTSTANDING</div>
                                    <div class="col-md-7 text-left">{{ number_format($payment_requisition->new_outstanding_invoices + $payment_requisition->old_outstanding_invoices, 2, '.', '') }}</div>
                                </div>
                            </li>
                            {{--<li class="list-group-item pr-1 pl-1 pt-0 pb-0">--}}
                                {{--<div class="row">--}}
                                    {{--<div class="col-md-5 border-right border-warning text-left pt-2 pb-2 pl-4">PROPOSED PAYMENT</div>--}}
                                    {{--<div class="col-md-7 text-left">{{ $payment_requisition->payment }}</div>--}}
                                {{--</div>--}}
                            {{--</li>--}}
                            <li class="list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left pt-2 pb-2 pl-4">APPROVED PAYMENT</div>
                                    <div class="col-md-7 text-left"></div>
                                </div>
                            </li>
                            <li class="list-group-item pr-1 pl-1 pt-0 pb-0">
                                <div class="row">
                                    <div class="col-md-5 border-right border-warning text-left pt-2 pb-2 pl-4">BALANCE AFTER<br>APPROVED PAYMENT</div>
                                    <div class="col-md-7 text-left"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header" style="font-family:Arial; font-size: 10pt; background-color: rgb(246, 151, 122);">FOR INTERNAL USE</div>
            </div>

            <!-- Approvals -->
            <div class="card-group">
                <div class="card">
                    <div class="card-header"><p class="mb-0">REQUESTED BY:</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">&nbsp;</div></div>
                    <div class="card-body signature">GENERATED AUTOMATICALLY</div>
                    <div class="footer text-left"><p>NAME: {{$payment_requisition->employee->full_name}}<br>DATE: {{$payment_requisition->created_at->format('Y-m-d')}}</p></div>
                </div>
                <div class="card">
                    @php
                        $name = '';
                        $date = '';
                        $t = $payment_requisition->transition('finance_approving');
                        if($t && $t->transition == 'hod_approved'){
                            $name = $t->user->employee->full_name;
                            $date = $t->in_date;
                        }
                    @endphp
                    <div class="card-header"><p class="mb-0">CHECKED BY:</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Head of Division / Department</div></div>
                    <div class="card-body signature">{{$name ? 'GENERATED AUTOMATICALLY' : ''}}</div>
                    <div class="footer text-left">
                        <p>NAME: {{$name}}
                            <br>DATE: {{$date}}
                        </p>
                    </div>
                </div>
                <div class="card">
                    @php
                        $name = '';
                        $date = '';
                        if($payment_requisition->transition('md_approving')){
                            $name = $payment_requisition->transition('md_approving')->user->employee ? $payment_requisition->transition('md_approving')->user->employee->full_name : $payment_requisition->transition('md_approving')->user->name;
                            $date = $payment_requisition->transition('md_approving')->in_date;
                        } elseif ($payment_requisition->transition('dec_approving') && $payment_requisition->transition('dec_approving')->transition != 'eco_approved'){
                            $name = $payment_requisition->transition('dec_approving')->user->employee->full_name;
                            $date = $payment_requisition->transition('dec_approving')->in_date;
                        }
                    @endphp
                    <div class="card-header"><p class="mb-0">VERIFIED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Finance</div></div> <!-- Change requested by Ms. Anisa-->
                    <div class="card-body signature">{{$name ? 'GENERATED AUTOMATICALLY' : ''}}</div>
                    <div class="footer text-left">
                        <p>NAME: {{$name}}
                            <br>DATE: {{$date}}
                        </p>
                    </div>
                </div>
            </div>
            <!-- APPROVAL 2 -->
            <div class="card-group mt-2">
                <!-- first colomn -->
                <div class="card">
                    @php
                        $name = '';
                        $date = '';
                        $t = $payment_requisition->transition('ceo_approving');
                        if($t){
                            $name = $t->user->employee->full_name;
                            $date = $t->in_date;
                        }
                    @endphp
                    <div class="card-header"><p class="mb-0">VERIFIED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Managing Director</div></div>
                    <div class="card-body signature">{{$name ? 'GENERATED AUTOMATICALLY' : ''}}</div>
                    <div class="footer text-left">
                        <p>NAME: {{$name}}
                            <br>DATE: {{$date}}
                        </p>
                    </div>
                </div>
                <!-- second colomn -->
                <div class="card">
                    @php
                        $name = '';
                        $date = '';
                        $t = $payment_requisition->transition('dec_approving');
                        if($t && $t->transition == 'ceo_approved'){
                            $name = $t->user->employee->full_name;
                            $date = $t->in_date;
                        }
                    @endphp

                    <div class="card-header"><p class="mb-0">CONFIRMED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Group CEO</div></div>
                    <div class="card-body signature">{{$name ? 'GENERATED AUTOMATICALLY' : ''}}</div>
                    <div class="footer text-left">
                        <p>NAME: {{$name}}
                            <br>DATE: {{$date}}
                        </p>
                    </div>
                </div>
                <!-- Third colomn -->
                <div class="card">
                    <div class="card-header"><p class="mb-0">APPROVED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Group Executive Chairman</div></div>
                    <div class="card-body"></div>
                    <div class="footer text-left">
                        <p>NAME:<br>DATE:</p>
                    </div>
                </div>
            </div>
            <!-- REMARKS -->
            <!-- first colomn -->
            <div class="row mt-2">
                <div class="col-md-8 pr-0">
                    <div class="card border-right-0 h-100">
                        <div class="card-header"><p class="mb-0 text-left">REMARKS</p></div>
                        <div class="card-body">
                            @foreach($payment_requisition->comments->sortByDesc('created_at') as $comment)
                                <div class="media">
                                    <div class="media-body">
                                        <p class="pl-5 media-heading float-right font-italic">{{$comment->commented->name .'@'. $comment->created_at->format('d M, Y')}}</p>
                                        <p class="text-justify font-weight-bold font-3xl">{{$comment->comment}}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-md-4 pl-0">
                    <div class="card h-100">
                        <div class="card-header"><p class="mb-0 text-left">CHEQUE</p></div>
                        <div class="card-body text-left p-2">CHEQUE NO. :<br>BANK  :<br>DATE :</div>
                    </div>
                </div>
            </div>

            <!-- second colomn -->
            <b class="mb-2 mt-2 text-left" style="font-family:Myriad Pro; font-size: 14pt">NOTE : THIS PRF CANNOT BE PROCESSED WITHOUT GROUP EXECUTIVE CHAIRMAN’S APPROVAL</b>
        </div>

    </main><!-- /.container -->
</body>
</html>
