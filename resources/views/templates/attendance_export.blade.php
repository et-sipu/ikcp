{{--@php--}}
{{--dd($dates);--}}
{{--@endphp--}}

<!DOCTYPE html>
    <head>
        <title>DAILY ATTENDANCE REPORT For {{ucwords($employee->full_name)}} between {{ $dates[0] }} and {{ $dates[1] }}</title>
        {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>--}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.0/jquery.matchHeight-min.js"></script>
        <script>
            $(function() {
                $('.box').matchHeight(false);
            });
        </script>
        <style type="text/css">
            .box{
                width:600px;
                margin:auto;
                border: 1px solid #ccc;
                height: auto !important;
            }
            th, td { border: 1px solid black }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-md-offset-2">
                    <h3 style="font-weight: bold">DAILY ATTENDANCE REPORT</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <p style="font-weight: bold">DEPT:</p>
                    <p style="font-weight: bold">NAME:</p>
                    <p style="font-weight: bold">MONTH:</p>
                    <p style="font-weight: bold">DATE:</p>
                </div>
                <div class="col-md-7">
                    @if($employee->department() !== null)
                        <p>{{ strtoupper($employee->department()->name) }}</p>
                    @else
                        <br>
                    @endif
                    <p>{{ ucwords($employee->full_name) }}</p>
                    @if($dates)
                        <p>{{ date('M', strtotime($dates[1])) }}</p>
                        <p>{{ $dates[0] }} To {{ $dates[1] }}</p>
                    @endif
                </div>
            </div>
            <table class="table table-bordered" style="margin:0px -15px;">
                <tr class="active">
                    <td class="col-md-1 text-center" style="vertical-align: middle; width: 3%;">NO</td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">DAYS</td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">DATE</td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">DEPT MOVEMENT</td>
                    <td class="col-md-4" style="padding: 0;">
                        <table class="table table-bordered" style="border: 0 !important; margin: 0;">
                            <tr class="active">
                                <div class="col-md-12 text-center" style="width: 100%; padding: 0;">THUMBPRINT</div>
                            </tr>
                            <tr class="active">
                                <td class="col-md-2 text-center">1 THUMB</td>
                                <td class="col-md-2 text-center">2 THUMB</td>
                                <td class="col-md-2 text-center">3 THUMB</td>
                                <td class="col-md-2 text-center">4 THUMB</td>
                                <td class="col-md-2 text-center">5 THUMB</td>
                                <td class="col-md-2 text-center">6 THUMB</td>
                            </tr>
                        </table>
                    </td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">LOG BOOK (HR)</td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">HR REVIEW</td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">EY QUERY</td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">REMARKS</td>
                </tr>
                @foreach($attendance_data as $attendance)
                    @php
                        $fingerprints = array_values($attendance->fingerprints()->toArray());
                    @endphp
                    <tr>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ $loop->iteration }}</td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ date('D', strtotime($attendance->attendance_date))}}</td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ date('d-M-y', strtotime($attendance->attendance_date))}}</td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ __('labels.backend.attendances.statuses.'.$attendance->dept_movement) }}</td>
                        <td class="col-md-4">
                            <div class="row">
                                <div class="col-md-2">
                                    @php
                                        echo isset($fingerprints[0]) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$fingerprints[0]['clocking'])->format('H:i') : null
                                    @endphp
                                </div>
                                <div class="col-md-2">
                                    @php
                                        echo isset($fingerprints[1]) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$fingerprints[1]['clocking'])->format('H:i') : null
                                    @endphp
                                </div>
                                <div class="col-md-2">
                                    @php
                                        echo isset($fingerprints[2]) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$fingerprints[2]['clocking'])->format('H:i') : null
                                    @endphp
                                </div>
                                <div class="col-md-2">
                                    @php
                                        echo isset($fingerprints[3]) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$fingerprints[3]['clocking'])->format('H:i') : null
                                    @endphp
                                </div>
                                <div class="col-md-2">
                                    @php
                                        echo isset($fingerprints[4]) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$fingerprints[4]['clocking'])->format('H:i') : null
                                    @endphp
                                </div>
                                <div class="col-md-2">
                                    @php
                                        echo isset($fingerprints[5]) ? \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s',$fingerprints[5]['clocking'])->format('H:i') : null
                                    @endphp
                                </div>
                            </div>
                        </td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ $attendance->logged_in_time }} - {{ $attendance->logged_out_time }}</td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ $attendance->hr_review }}</td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ $attendance->ey_review }}</td>
                        <td class="col-md-1 text-center" style="vertical-align: middle;">{{ $attendance->remarks }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="col-md-1 text-right" style="vertical-align: middle;" colspan="3">TOTAL WORKING DAYS:</td>
                    <td class="col-md-1 text-right" style="vertical-align: middle;" colspan="3"></td>
                    <td class="col-md-1 text-center" style="vertical-align: middle;">{{ $summery }}</td>
                    <td class="col-md-1 text-left" style="vertical-align: middle;" colspan="2"></td>
                </tr>
            </table>
        </div>
    </body>
</html>