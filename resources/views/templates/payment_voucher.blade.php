<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PAYMENT VOUCHER</title>
  <style>
    @page {
      margin-right: 0.5in;
      margin-left: 0.5in;
      margin-top: 0.4in;
      margin-bottom: 1in
    }
    body {
      /*font-family: Calibri, "Times New Roman", Arial Narrow, Arial, sans-serif*/
      font-size: 12px;
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
    .title {
      font-weight: bold;
      font-size: 24px;
      letter-spacing: 4px;
    }
    .address {
      font-size: 12px;
    }
    sub {
      vertical-align: sub;
      font-size: 10px;
    }
    footer table td {
      height: 30px
    }
    footer { position: fixed; bottom: 0px; left: 0px; right: 0px;font-size: 10px; height: 50px; }
    footer table.bordered td {
      border: 1px solid #000000;text-align: center; padding: 5px; font-size: 12px;vertical-align: top;height: 50px
    }
    footer table.total td {
      vertical-align: top;
    }
    table.content th {
      border: 1px solid black
    }
  </style>
</head>
  <body>
    <main>
      <table width="100%" cellspacing="0">
        <tr>
          <td width="20%"><img src="{{asset('/images/InaiKenangaLogo.png')}}" style="width: 150px"/></td>
          <td width="60%"><span class="title">INAI KENAGA SDN BHD</span><sub>548268-A</sub><br>
            <span class="address">LOT 74082 Pantai Acheh, Jalan Pulau Indah, 47000 Port Klang, Selangor Darul Ehsan.</span>
            <span class="address">Telphone : (603) 3101 5555 FAX : (603) 3101 5550</span>
          </td>
          <td width="20%"></td>
        </tr>
      </table>
      <br><br>
      <table width="100%" cellspacing="0">
        <tr style="vertical-align: top;">
          <td width="30%">
            Pay To: {{ $payment_requisition->released_to }}
          </td>
          <td width="25%">
          </td>
          <td width="20%" style="text-align: right">
            <span style="font-weight: bolder">Temporary PV No.:</span><br>
            <span>PRF No.:</span><br>
            <span>Date:</span><br>
            <span>Cheque No.:</span><br>
            <span>Payment By:</span><br>
          </td>
          <td width="25%" style="text-align: left">
            {{ 'TPV' . str_pad($payment_requisition->id,10,'0',STR_PAD_LEFT) }}<br>
            {{$payment_requisition->id}}<br>
            {{$data['cheque_date']}}<br>
            {{$data['cheque_no']}}<br>
            {{$data['cheque_bank']}}<br>
          </td>
        </tr>
      </table>
      <br><br>
      <table width="100%" cellspacing="0" class="content">
        <thead>
          <tr style="text-align: center;">
            <th width="20%">A/C
            </th>
            <th width="65%">Description
            </th>
            <th width="15%" style="text-align: right">Amount (RM)
            </th>
          </tr>
        </thead>
        <tbody>
        <tr>
          <td width="20%">{{ $payment_requisition->title }}
          </td>
          <td width="65%">{{ $payment_requisition->details }}
          </td>
          <td width="15%" style="text-align: right">{{ $payment_requisition->approved_payment }}
          </td>
        </tr>
        </tbody>
      </table>
    </main>
    <footer>
      <hr>
      @php
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
      @endphp
      <table width="100%" cellspacing="0" class="total">
        <tr>
          <td width="80%">RINGGIT MALAYSIA: {{ strtoupper($f->format($payment_requisition->approved_payment)) }} ONLY</td>
          <td width="20%">Total: {{ number_format($payment_requisition->approved_payment, 2) }}</td>
        </tr>
      </table>
      <table width="100%" cellspacing="0" class="bordered">
        <tr>
          <td>Prepared By</td>
          <td>Approved By</td>
          <td>Received By</td>
        </tr>
      </table>
    </footer>
  </body>
</html>