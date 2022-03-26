<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>IMO CREW LIST</title>
  <style>
    @page {
      margin-right: 0.7in;
      margin-left: 0.7in;
      margin-top: 0.4in;
      margin-bottom: 1in
    }
    body {
      /*font-family: Calibri, "Times New Roman", Arial Narrow, Arial, sans-serif*/
    }
    table.body_table td {
      border-top: 1px solid #000000;
      border-bottom: 1px solid #000000;
      border-left: 1px solid #000000;
      border-right: 1px solid #000000;
      padding: 2px;
      font-size: 12px;
      line-height: 90%
    }
    footer table td {
      border: 1px solid #000000;text-align: center; padding: 5px; font-size: 12px;
    }
    .title {
      font-weight: bold;
    }
    footer { position: fixed; bottom: 0px; left: 0px; right: 0px;font-size: 10px; }
    header { position: fixed; top: -10px; left: 0px; right: 0px;}
  </style>
</head>
  <body>
    <footer>
      <table width="100%" cellspacing="0">
        <tr>
          <td>IK 01</td>
          <td>VER : 0 / REV : 0</td>
          <td>DATE : JAN 2017</td>
        </tr>
      </table>
    </footer>
    <main>
      <div style="text-align: center">
        <h2>INAI KIARA SDN BHD</h2>
        <h3>IMO CREW LIST</h3>
      </div>
      <table class="body_table" cellspacing="0" width="100%">
        <tr>
          <td width="50%" style="border-top: 0px;border-left: 0px;border-bottom: 0px;"><br>
          </td>
          <td width="5%" style="border-bottom: 0px;font-family: DejaVu Sans, sans-serif;font-size: 20px">{!! ($movement_type == 'Arrival') ? "&#10003;" : '' !!}
          </td>
          <td style="border-top: 0px;border-bottom: 0px;padding-top: 10px">Arrival</td>
          <td width="5%" style="border-bottom: 0px;font-family: DejaVu Sans, sans-serif;font-size: 20px">{!! ($movement_type == 'Departure') ? "&#10003;" : '' !!}
          </td>
          <td style="border-top: 0px;border-right: 0px;padding-top: 10px;">Departure</td>
        </tr>
      </table>
      <table class="body_table" cellspacing="0">
        <tr>
          <td colspan="2">
            <p style="margin: 0px;line-height: 150%">
              <span class="title">1. Name Of Ship</span><br>
              <span>{{$vessel->name}}</span>
            </p>
          </td>
          <td colspan="2">
            <p style="margin: 0px;line-height: 150%">
              <span class="title">2. Port Of {{$movement_type}}</span><br>
              <span>{{$goal_port}}</span>
            </p>
          </td>
          <td colspan="2">
            <p style="margin: 0px;line-height: 150%">
              <span class="title">3. Date Of {{$movement_type}}</span><br>
              <span>{{$movement_date}}</span>
            </p>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <p style="margin: 0px;line-height: 150%">
              <span class="title">4. Nationality of Ship</span><br>
              <span>{{$vessel->flag}}</span>
            </p>
          </td>
          <td colspan="3">
            <p style="margin: 0px;line-height: 150%">
              <span class="title">5. Last Port</span><br>
              {{--        <span>{{$last_port->name}}</span>--}}
              <span>{{$last_port}}</span>
            </p>
          </td>
          <td rowspan="2" style="text-align: center">
            <span class="title">6.  Nature and No. of Identity Document (Seaman’s Passport)</span><br>
          </td>
        </tr>
        <tr>
          <td width="6%" class="title">7. NO</td>
          <td width="24%" class="title">8. Family Name, Given Names</td>
          <td width="15%" class="title">9. Rank</td>
          <td width="15%" class="title">10. Nationality</td>
          <td width="25%" class="title">11. Date and Place of Birth</td>
        </tr>
        @foreach($onboard_seafarers as $seafarer)
          <tr>
            <td style="text-align: center">{{$loop->index+1}}</td>
            <td>{{$seafarer->full_name}}</td>
            <td style="text-align: center">{{$seafarer->rank}}</td>
            <td style="text-align: center">{{$seafarer->real_nationality}}</td>
            <td style="text-align: center">{{$seafarer->date_of_birth}}, {{$seafarer->place_of_birth}}</td>
            <td style="text-align: center">{{($seafarer->documents->where('document_type','PASSPORT')->first()) ? $seafarer->documents->where('document_type','PASSPORT')->first()->document_no : ''}}</td>
          </tr>
        @endforeach
      </table>
      <table>
        <tr>
          <td>
            <div style="line-height: 200%">
              <span>12. Date and Signature (Master, Authorized Agent or Officer)</span><br>
              <span>Date : {{ date('Y-m-d') }}</span><br>
              @if($signature)
                <img src="{{$signature->encode('data-url')}}" />
              @endif
            </div>
            <div style="text-align: center;float: left">
              <span style="text-decoration: underline">Capt. {{($vessel->current_master()) ? $vessel->current_master()->full_name : ''}}</span><br>
              <span style="font-weight: bold;">Master</span>
            </div>
          </td>
        </tr>
      </table>
    </main>
  </body>
</html>