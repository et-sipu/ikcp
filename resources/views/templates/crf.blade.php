<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">

    <title>Cash Requisition Form (#CRF{{str_pad($cash_requisition->id,8,'0',STR_PAD_LEFT)}})</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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
        .card-header {
            background-color: #808285;
            padding: .25rem!important;
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
        .card {
            border: 1px solid #ddb21e;
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
                <p class="mb-1" style="font-family: Arial black; font-size: 20pt">CASH REQUISITION FORM</p>
                <p style="font-family:Myriad Pro; font-size: 10pt">FOR APPROVAL BY SUPERIOR, HR DEPARTMENT, FINANCE DEPARTMENT & GROUP CEO<br>
                    / EXECUTIVE CHAIRMAN OFFICE</p>
            </div>
            <div class="col-md-3 text-right">
                <img src="{{asset('images/logo_forms.png')}}" class="img-fluid" alt="Responsive image">
                <br><div style="font-family:Arial; font-size: 10pt">(325709-V)</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" {{($cash_requisition->request_type == 'cash_advance') ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="inlineCheckbox1">CASH ADVANCE</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" {{($cash_requisition->request_type == 'reimbursement') ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="inlineCheckbox1">REIMBURSEMENT</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" {{($cash_requisition->request_type == 'deposit') ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="inlineCheckbox1">DEPOSIT</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3"><p>CRF {{ str_pad($cash_requisition->id,8,'0',STR_PAD_LEFT) }}</p></div>
        </div>
        <!-- form started -->
        <div class="row mb-2">
            <!-- requested by -->
            <div class="col-md-9 pr-1">
                <div class="card h-100">
                    <div class="card-body main_content">
                        <div class="row">
                            <div class="col-md-6 text-left" style="font-family: Arial">
                                <p><span class="font-weight-light">REQUESTED BY: </span><span class="font-italic">{{ucwords($cash_requisition->employee->full_name)}}</span></p>
                            </div>
                            <div class="col-md-6 text-left" style="font-family: Arial;">
                                <p><span class="font-weight-light">DEPARTMENT: </span><span class="font-italic">{{strtoupper($cash_requisition->employee->department->name)}}</span></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 text-left" style="font-family: Arial">
                                <p><span class="font-weight-light">PURPOSE:</span></p>
                            </div>
                            <div class="col-md-10 text-left" style="font-family: Arial;">
                                <p>{{ucfirst($cash_requisition->purpose)}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 pl-1">
                <!-- DATE -->
                <div class="card">
                    <div class="card-header" style="font-family:Arial; font-size: 10pt">DATE</div>
                    <div class="card-body">{{$cash_requisition->created_at->format('Y-m-d')}}</div>
                </div>
                <!-- Ammount Requested -->
                <div class="card">
                    <div class="card-header" style="font-family:Arial; font-size: 10pt">AMOUNT REQUESTED<br><div style="font-family:Arial; font-size: 7pt"></div></div>
                    <div class="card-body">RM {{$cash_requisition->amount}}</div>
                </div>
                <!-- Ammount Approverd -->
                <div class="card">
                    <div class="card-header" style="font-family:Arial; font-size: 10pt">AMOUNT APPROVED<br><div style="font-family:Arial; font-size: 7pt"></div></div>
                    <div class="card-body">RM {{$cash_requisition->approved_amount}}</div>
                </div>
            </div>
        </div>
        <!-- Approvals -->
        <div class="card-group">
            <div class="card">
                <div class="card-header"><p class="mb-0">REQUESTED BY:</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">&nbsp;</div></div>
                <div class="card-body signature">GENERATED AUTOMATICALLY</div>
                <div class="footer text-left"><p>NAME: {{$cash_requisition->employee->full_name}}<br>DATE: {{$cash_requisition->created_at->format('Y-m-d')}}</p></div>
            </div>
            <div class="card">
                @php
                    $name = '';
                    $date = '';
                    $t = $cash_requisition->transition('hr_approving');
                    if($t && $t->transition == 'hod_approved'){
                        $name = $t->user->employee->full_name;
                        $date = $t->in_date;
                    }
                @endphp
                <div class="card-header"><p class="mb-0">CHECKED BY:</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Head of Department</div></div>
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
                    if($cash_requisition->transition('finance_approving')){
                        $name = $cash_requisition->transition('finance_approving')->user->employee->full_name;
                        $date = $cash_requisition->transition('finance_approving')->in_date;
                    }
                    // elseif ($cash_requisition->transition('dec_approving') && $cash_requisition->transition('dec_approving')->transition != 'eco_approved'){
                    //    $name = $cash_requisition->transition('dec_approving')->user->employee->full_name;
                    //    $date = $cash_requisition->transition('dec_approving')->in_date;
                    //}
                @endphp

                <div class="card-header"><p class="mb-0">APPROVED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">HR Department</div></div>
                <div class="card-body signature">{{$name ? 'GENERATED AUTOMATICALLY' : ''}}</div>
                <div class="footer text-left">
                    <p>NAME: {{$name}}
                        <br>DATE: {{$date}}
                    </p>
                </div>

            </div>
        </div>

        <!-- History & Staff Details -->
        <div class="row mt-2">
            <div class="col-md-7 pr-1">
                <div class="card h-100">
                    <div class="card-header text-left">History of Cash Advance (Finance Dept.)</div>
                    <div class="card-body text-left pb-0">
                        <div class="row mb-3">
                            <div class="col-md-5">CASH ADVANCE<br>TAKEN(A)</div>
                            <div class="col-md-7">
                                <div class="input-group input-group-sm m-0 mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">&nbsp;RM &nbsp;</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$cash_requisition->cash_advance_taken}}" disabled>
                                </div>
                                <div class="input-group input-group-sm m-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">DATE</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$cash_requisition->cash_advance_taken_date}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">TOTAL RECEIPT<br>RETURNED (B)</div>
                            <div class="col-md-7">
                                <div class="input-group input-group-sm m-0 mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">&nbsp;RM &nbsp;</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$cash_requisition->total_receipt_returned}}" disabled>
                                </div>
                                <div class="input-group input-group-sm m-0">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">DATE</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$cash_requisition->total_receipt_returned_date}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-5">BALANCE OWING TO<br>(STAFF) / COMPANY<br>(A-B)</div>
                            <div class="col-md-7 ">
                                <div class="input-group input-group-sm m-0 mb-1">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroup-sizing-sm">&nbsp;<br>&nbsp;RM&nbsp;&nbsp;<br>&nbsp;</span>
                                    </div>
                                    <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$cash_requisition->cash_advance_taken - $cash_requisition->total_receipt_returned}}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Staff CAR details (if any) -->
            <div class="col-md-5 pl-1">
                <div class="card h-100">
                    <div class="card-header">Staff CAR details (if any)</div>
                    <div class="card-body p-0">
                        Outstanding Staff CA:&nbsp;
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="optradio" {{($cash_requisition->outstanding_ca) ? 'checked' : '' }} disabled>YES&nbsp;
                            </label>
                        </div>
                        <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="optradio" {{(!$cash_requisition->outstanding_ca) ? 'checked' : '' }} disabled>NO
                            </label>
                        </div>
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-sm">&nbsp;RM&nbsp;</span>
                            </div>
                            <input type="text" class="form-control" aria-label="Small" aria-describedby="inputGroup-sizing-sm" value="{{$cash_requisition->outstanding_ca}}" disabled>
                        </div>
                        <p class=" m-0 text-left">REMARKS:</p>
                    </div>
                    <div class="card-header card-footer">CHECKED BY: <h style="font-family:Arial; font-style: italic; font-size: 8pt"> Finance Dept.</h></div>
                    <div class="card-body signature">GENERATED AUTOMATICALLY</div>

                    <div class="footer text-left">
                        <p>NAME: {{$cash_requisition->transition('eco_approving') ? $cash_requisition->transition('eco_approving')->user->employee->full_name : ''}}
                            <br>DATE: {{$cash_requisition->transition('eco_approving') ? $cash_requisition->transition('eco_approving')->in_date : ''}}
                        </p>
                    </div>

                </div>
            </div>
        </div>
        <!-- APPROVAL 2 -->
        <div class="card-group">
            <!-- first colomn -->
            <div class="card">
                @php
                    $name = '';
                    $date = '';
                    $t = $cash_requisition->transition('eco_approving');
                    if($t){
                        $name = $t->user->employee->full_name;
                        $date = $t->in_date;
                    }
                @endphp

                <div class="card-header"><p class="mb-0">RECORDED BY:</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Finance Dept. (Audit)</div></div>
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
                    $t = $cash_requisition->transition('dec_approving');
                    if($t && $t->transition == 'eco_approved'){
                        $name = $t->user->employee->full_name;
                        $date = $t->in_date;
                    }
                @endphp

                <div class="card-header"><p class="mb-0">APPROVED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Group CEO/ECO</div></div>
                <div class="card-body signature">{{$name ? 'GENERATED AUTOMATICALLY' : ''}}</div>

                <div class="footer text-left">
                    <p>NAME: {{$name}}
                        <br>DATE: {{$date}}
                    </p>
                </div>

            </div>
            <!-- Third colomn -->
            <div class="card">
                <div class="card-header"><p class="mb-0">APPROVED BY :</p><div style="font-family:Arial; font-style: italic; font-size: 8pt">Executive Chairman</div></div>
                <div class="card-body"></div>
                <div class="footer text-left">
                    <p>NAME: {{$cash_requisition->transition('releasing') ? $cash_requisition->transition('releasing')->user->employee->full_name : ''}}
                        <br>DATE: {{$cash_requisition->transition('releasing') ? $cash_requisition->transition('releasing')->in_date : ''}}
                    </p>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header text-left">REMARKS</div>
                    <div class="card-body text-left">{{ $cash_requisition->remarks ?: '' }}</div>
                </div>
            </div>
        </div>
        <div class="text-left mt-2" style="font-family:Arial; font-style: italic; font-size: 8pt">
            <b>TERMS & CONDITIONS FOR CASH ADVANCE</b><br>1. Cash Advance is given at the sole discretion of the Company.<br>
            2. Approval from the immediate Superior, HR Department, Finance Department, Executive Chairman Office/Group CEO must be obtain prior to Cash Advance be given to the employee.<br>
            3. All receipts for the usage of the Cash Advance and the balance of Cash Advance must be given and returned back to Finance Department immediately after the employee has return back from his/her assignment or after completing the specific purpose of the Cash Advance.<br>
            4. Failure to produce the receipts and return the balance of the Cash Advance will result THE SALARY of the employee in the following month TO BE DEDUCTED. equivalent to the Cash Advance taken without further notice to the employee.<br>
            5. Employee is responsible to ensure the receipts of the Cash Advance and the balance of Cash Advance to be returned to the Finance Department.
        </div>
    </div>

</main><!-- /.container -->
</body>
</html>
