<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Copy - Seafarer</title>
    <link rel="stylesheet" href="{{asset('print/style.css')}}">
    <link rel="shortcut icon" href="{{asset('favicon.png')}}">
</head>
<body>
    <header>
        <div style="padding-top: 20px">
            <img src="{{asset('images/logo_prop.png')}}" alt="" width="100px">
        </div>
    </header>
    <footer>
        <table width="100%" >
            <tr>
                <td>
                    Propetco.CRWD.QF01 Rev 0 2014
                </td>
                <td align="right"></td>
            </tr>
        </table>
    </footer>
    <main>
        <table width="100%" cellpadding="7" cellspacing="0">
            <tbody>
                <tr>
                    <td width="26%" style="padding-left: 100px">
                        {{--<img src="{{asset('print/print_inaikiara_logo.png')}}" alt="" width="100px">--}}
                        <p>
                            <span class="header_copies">Copy - Seafarer</span><br>
                            <span class="header_copies">Copy - Ship’s File</span><br>
                            <span class="header_copies">Copy – Crewing Dept</span>
                        </p>
                    </td>
                    <td width="52%" style="border: none;line-height: 150%;padding-left: 60px;padding-right: 120px" align="center">
                        <p class="header_title">CREW CONTRACT OF AGREEMENT SIGN ON</p>
                    </td>
                    <td width="12%" style="border: 1px solid #000000; padding: 0in" align="center">
                        <img src="{{$seafarerContract->seafarer->photo_path}}" style="padding: 0.05in;" align="bottom" width="70px"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <p>
                            <span class="contract_label_bold">AGREEMENT NO : Propetco/CRWD </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border: 1px solid #000000; padding: 0.05in">
                        <p class="contract_terms">This Employment Contract is entered into between the Seafarer and the Shipowner / the Employer on behalf of the Shipowner</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="table_label">A. THE SEAFARER</p>
        <table width="100%" cellpadding="7" cellspacing="0">
            <tbody>
            <tr>
                <td colspan="4" class="td_normal">
                    <p>
                        <span class="contract_label">1. Surname :</span><br/>
                        <span class="contract_value">{{ strtoupper($seafarerContract->seafarer->surname) }}</span>
                    </p>
                </td>
                <td colspan="5" class="td_normal">
                    <p>
                        <span class="contract_label">Given Names :</span><br/>
                        <span class="contract_value">{{ strtoupper($seafarerContract->seafarer->name) }}</span>
                    </p>
                </td>
                <td colspan="5" class="td_normal">
                    <p>
                        <span class="contract_label">Contact No :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->seafarer->contactInfo->personal_hp }}</span>
                    </p>
                </td>
                <td colspan="3" class="td_normal">
                    <p>
                        <span class="contract_label">Sex :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->seafarer->sex }}</span>
                    </p>
                </td>
                <td colspan="3" class="td_normal td_last_cell_in_row">
                    <p>
                        <span class="contract_label">Religion :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->seafarer->religion }}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="14" class="td_normal">
                    <p>
                        <span class="contract_label">Full home address : </span><span class="contract_value">{{ $seafarerContract->seafarer->address }}</span>
                    </p>
                </td>
                <td colspan="6" class="td_normal td_last_cell_in_row">
                    <p >
                        <span class="contract_label_bold">BANK DETAILS ({{ $seafarerContract->seafarer->financialInfo->bank }}) </span> <span class="contract_label">Acct No :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->seafarer->financialInfo->account_no }}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="td_normal">
                    <p>
                        <span class="contract_label">Nationality :</span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->real_nationality}}</span>
                    </p>
                </td>
                <td colspan="8" class="td_normal">
                    <p>
                        <span class="contract_label">Passport / Expiry Date :</span><br/>
                        <span class="contract_value">
                            {{($seafarerContract->seafarer->documents->where('document_type','PASSPORT')->first()) ? $seafarerContract->seafarer->documents->where('document_type','PASSPORT')->first()->document_no : '' }} / {{($seafarerContract->seafarer->documents->where('document_type','PASSPORT')->first()) ? $seafarerContract->seafarer->documents->where('document_type','PASSPORT')->first()->document_expiry_date : '' }}</span>
                    </p>
                </td>
                <td colspan="3" class="td_normal">
                    <p>
                        <span class="contract_label">NRIC No :</span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->nric_no}}</span>
                    </p>
                </td>
                <td colspan="3" class="td_normal">
                    <p>
                        <span class="contract_label">Date of Birth :</span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->date_of_birth}}</span>
                    </p>
                </td>
                <td colspan="3" class="td_normal td_last_cell_in_row">
                    <p>
                        <span class="contract_label">Place of birth: </span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->place_of_birth}}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="td_normal">
                    <p>
                        <span class="contract_label">2. Position :</span><br/>
                        <span class="contract_value">{{$seafarerContract->contract_rank->name}}</span>
                    </p>
                </td>
                <td colspan="15" class="td_normal">
                    <p>
                        <span class="contract_label_bold">Certificate of Competency / Type : </span><span class="contract_value">{{$seafarerContract->seafarer->capabilitiesInfo->coc_certificate_type}}</span><br/>
                        <span class="contract_label">Certificate No : </span><span class="contract_value">
                            {{($seafarerContract->seafarer->documents->where('document_type','COC_CERT')->first()) ? $seafarerContract->seafarer->documents->where('document_type','COC_CERT')->first()->document_no : '' }}
                            </span>
                        <span class="contract_label">Expiry Date : </span><span class="contract_value">
                            {{($seafarerContract->seafarer->documents->where('document_type','COC_CERT')->first()) ? $seafarerContract->seafarer->documents->where('document_type','COC_CERT')->first()->document_expiry_date : '' }}
                        </span>
                    </p>
                </td>
                <td colspan="3" class="td_normal td_last_cell_in_row">
                    <p>
                        <span class="contract_label">3. Duration of Service : </span><br/>
                        <span class="contract_value">{{$seafarerContract->duration_of_service}} {{ $seafarerContract->duration_of_service_unit}}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="4" rowspan="2" class="td_normal">
                    <p>
                        <span class="contract_label">SMC Reg No : </span><br/><span class="contract_value">{{($seafarerContract->seafarer->documents->where('document_type','SMC')->first()) ? $seafarerContract->seafarer->documents->where('document_type','SMC')->first()->document_no : '' }}</span><br/>
                        <span class="contract_label_bold">Expiry Date : </span><span class="contract_value">{{($seafarerContract->seafarer->documents->where('document_type','SMC')->first()) ? $seafarerContract->seafarer->documents->where('document_type','SMC')->first()->document_expiry_date : '' }}</span>
                    </p>
                </td>
                <td colspan="7" rowspan="2" class="td_normal">
                    <p>
                        <span class="contract_label">Discharge Book No :</span><br/><span class="contract_value">{{($seafarerContract->seafarer->documents->where('document_type','DISCHARGE_BOOK')->first()) ? $seafarerContract->seafarer->documents->where('document_type','DISCHARGE_BOOK')->first()->document_no : '' }}</span><br/>
                        <span class="contract_label_bold">Expiry Date :</span><span class="contract_value"> {{($seafarerContract->seafarer->documents->where('document_type','DISCHARGE_BOOK')->first()) ? $seafarerContract->seafarer->documents->where('document_type','DISCHARGE_BOOK')->first()->document_expiry_date : '' }}</span>
                    </p>
                </td>
                <td colspan="6" class="td_normal">
                    <p>
                        <span class="contract_label">Security Courses :</span><br/><br/>
                    </p>
                </td>
                <td colspan="3" rowspan="2" class="td_normal td_last_cell_in_row">
                    <p>
                        <span class="contract_label">Medical Cert No :</span><br/><span class="contract_value">{{($seafarerContract->seafarer->documents->where('document_type','MEDICAL_CERT')->first()) ? $seafarerContract->seafarer->documents->where('document_type','MEDICAL_CERT')->first()->document_no : ''}}</span><br/>
                        <span class="contract_label_bold">Expiry Date :</span><span class="contract_value"> {{($seafarerContract->seafarer->documents->where('document_type','MEDICAL_CERT')->first()) ? $seafarerContract->seafarer->documents->where('document_type','MEDICAL_CERT')->first()->document_expiry_date : '' }}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="td_normal">
                    <p>
                        <span class="contract_label_bold">SSO</span>
                    </p>
                </td>
                <td class="td_normal" style="font-family: DejaVu Sans, sans-serif;padding-top: 1px">{!! ($seafarerContract->seafarer->capabilitiesInfo->security_course && array_key_exists('SSO',array_flip($seafarerContract->seafarer->capabilitiesInfo->security_course)) ? "&#10003;": '') !!}</td>
                <td class="td_normal">
                    <p>
                        <span class="contract_label_bold">DSD</span>
                    </p>
                </td>
                <td class="td_normal" style="font-family: DejaVu Sans, sans-serif;padding-top: 1px">{!! ($seafarerContract->seafarer->capabilitiesInfo->security_course && array_key_exists('DSD',array_flip($seafarerContract->seafarer->capabilitiesInfo->security_course)) ? "&#10003;": '') !!}</td>
                <td class="td_normal">
                    <p>
                        <span class="contract_label_bold">SSA</span>
                    </p>
                </td>
                <td class="td_normal" style="font-family: DejaVu Sans, sans-serif;padding-top: 1px">{!! ($seafarerContract->seafarer->capabilitiesInfo->security_course && array_key_exists('SSA',array_flip($seafarerContract->seafarer->capabilitiesInfo->security_course)) ? "&#10003;": '') !!}</td>
            </tr>
            <tr>
                <td colspan="4" class="td_normal">
                    <p>
                        <span class="contract_label_bold">INSURANCE :</span><br>
                        <span class="contract_value">Expiry Date : {{$seafarerContract->insurance_expiry_date}}</span>
                    </p>
                </td>
                <td colspan="11" class="td_normal" valign="top">
                    <p>
                        <span class="contract_label">TYPE : {{$seafarerContract->insurance_type}}</span>
                    </p>
                    <p>
                        <span class="contract_value">{{$seafarerContract->insurance_company}}</span>
                    </p>
                </td>
                <td colspan="5" class="td_normal td_last_cell_in_row" valign="top">
                    <p>
                        <span class="contract_label">Medically Tested by : </span><span class="contract_value">{{$seafarerContract->tested_by}}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="12" rowspan="2" class="td_normal td_last_cell_in_col">
                    <p>
                        <span class="contract_label">Next of Kin (* Spouse / Parents ) / Contact Number :</span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->contactInfo->next_of_kin_name}}</span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->contactInfo->next_of_kin_hp}}</span>
                    </p>
                </td>
                <td colspan="8" class="td_normal td_last_cell_in_row">
                    <p>
                        <span class="contract_label_bold">Home Allotment : NIL </span><br/>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="td_normal td_last_cell_in_col">
                    <p>
                        {{--<span class="contract_label">Name : </span><span class="contract_value">NIL</span><br/>--}}
                        <span class="contract_label">Account No : </span><span class="contract_value">{{$seafarerContract->seafarer->financialInfo->account_no}}</span><br/><span class="contract_label">Bank : </span><span class="contract_value">{{$seafarerContract->seafarer->financialInfo->bank}}</span>
                    </p>

                </td>
                <td colspan="4" class="td_normal td_last_cell_in_row td_last_cell_in_col">
                    <p>
                        <span class="contract_label">Swift Code :</span><span class="contract_value">{{ $seafarerContract->seafarer->financialInfo->swift_code }}</span><br/>
                        <span class="contract_label">Country of Origin :</span><span class="contract_value">{{ $seafarerContract->seafarer->financialInfo->country_of_origin }}</span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>

        <table width="100%" cellpadding="7" cellspacing="0">
            <tbody>
            </tbody>
        </table>

        <p class="table_label">B. THE SHIPOWNER</p>

        <table width="100%" cellpadding="7" cellspacing="0">
            <tr>
                <td class="td_normal td_last_cell_in_row" colspan="2">
                    <p>
                        <span class="contract_label">Name : </span><span class="contract_value">PROPETCO SDN BHD</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="td_normal td_last_cell_in_row" colspan="2">
                    <p>
                        <span class="contract_label">Address : </span><span class="contract_value">LEVEL 1, Block B, Dataran PHB, Saujana Resort, Seksyen U2, 40150 Shah Alam, Selangor</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td width="20%" class="td_normal td_last_cell_in_col">
                    <p>
                        <span class="contract_label">Telephone / Fascimile No : </span>
                    </p>
                </td>
                <td width="80%" class="td_normal td_last_cell_in_row td_last_cell_in_col">
                    <p>
                        <span class="contract_value">606-351 5888</span>
                    </p>
                </td>
            </tr>
        </table>

        <p class="table_label">THE EMPLOYER [if different from the Shipowner]</p>

        <table width="100%" cellpadding="7" cellspacing="0">
            <tr>
                <td class="td_normal td_last_cell_in_row td_last_cell_in_col">
                    <p>
                        <span class="contract_label">Name : </span><span class="contract_value">N / A</span>
                    </p>
                </td>
            </tr>
        </table>

        <p class="table_label">C. THE SHIP</p>

        <table width="100%" cellpadding="7" cellspacing="0">
            <tbody>
            <tr>
                <td class="td_normal">
                    <p>
                        <span class="contract_label">4. Name :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->vessel->name }}</span>
                    </p>
                </td>
                <td colspan="2" class="td_normal">
                    <p>
                        <span class="contract_label">IMO No :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->vessel->imo_number }}</span>
                    </p>
                </td>
                <td colspan="2" class="td_normal">
                    <p>
                        <span class="contract_label">Port of registry :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->vessel->port_of_registry->name }}</span>
                    </p>
                </td>
                <td class="td_normal">
                    <p>
                        <span class="contract_label">Flag :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->vessel->flag }}</span>
                    </p>
                </td>
                <td class="td_normal" valign="top">
                    <p>
                        <span class="contract_label">Date Signed On : ** </span><br/>
                        <span class="contract_value">{{ $seafarerContract->sign_on_date }}</span>
                    </p>
                </td>
                <td class="td_normal td_last_cell_in_row" valign="top">
                    <p>
                        <span class="contract_label">Date Signed Off: ** </span><br/>
                        <span class="contract_value">{{ $seafarerContract->sign_off_date }}</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="td_normal td_last_cell_in_col">
                    <p>
                        <span class="contract_label">Port where position is taken up :</span><br/>
                        <span class="contract_value">{{ $seafarerContract->sign_on_port->name }}</span>
                    </p>
                </td>
                <td colspan="2" class="td_normal td_last_cell_in_col" valign="top">
                    <p>
                        <span class="contract_label">Last Ship Joined :</span><br/>
                        <span class="contract_value">{{($seafarerContract->previous_contract()) ? $seafarerContract->previous_contract()->vessel->name : ''}}</span>
                    </p>
                </td>
                <td colspan="2" class="td_normal td_last_cell_in_col" valign="top">
                    <p>
                        <span class="contract_label">Last Signed Off Date :</span><br/>
                        <span class="contract_value">{{($seafarerContract->previous_contract()) ? $seafarerContract->previous_contract()->sign_off_date : ''}}</span>
                    </p>
                </td>
                <td colspan="2" class="td_normal td_last_cell_in_row td_last_cell_in_col" valign="top">
                    <p>
                        <span class="contract_value">Remarks : ** Subject to vessel production and varies each month</span>
                    </p>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="table_label">D. TERMS OF THE CONTRACT</p>

        <table width="100%" cellpadding="7" cellspacing="0">
            <tbody>
            <tr>
                <td class="td_normal">
                    <p>
                        <span class="contract_label">5. Basic {{ $seafarerContract->pay_frequency }} wage: </span><br/>
                        <span class="contract_value">{{$seafarerContract->currency . number_format($seafarerContract->basic_salary)}}</span>
                    </p>

                </td>
                <td class="td_normal">
                    <p>
                        <span class="contract_label">Monthly Allowance (Applicable while on-board ) :</span><br/>
                        <span class="contract_value">** TARGETTED MONTHLY ALLOWANCE : 0 - 30% </span>
                    </p>
                </td>
                <td class="td_normal td_last_cell_in_row">
                    @if($seafarerContract->pay_leave == 0)
                    <p>
                        <span class="contract_label">6. Pay Leave: </span><br><span class="contract_value">Inclusive</span><br/>
                    </p>
                    @else
                    <p>
                        <span class="contract_label">6. Pay Leave: {{($seafarerContract->pay_leave <= 30) ? 'Number of Days' : 'Fixed'}}</span><br/>
                        <span class="contract_value"> ({{(($seafarerContract->pay_leave <= 30)) ? $seafarerContract->pay_leave ."/30 DAY" : $seafarerContract->currency. number_format($seafarerContract->pay_leave)}})</span>
                    </p>
                        @endif
                </td>
            </tr>
            <tr>
                <td class="td_normal">
                    <p>
                        <span class="contract_label">Period of employment </span><br/>
                        <span class="contract_label">(Date of First Join ): </span><br/>
                        <span class="contract_value">{{$seafarerContract->seafarer->date_of_join}}</span>
                    </p>
                </td>
                <td class="td_normal">
                    <p>
                        <span class="contract_label">Status of Contract ( Contract) </span><br/>
                        {{--<span class="contract_label">Permanent : OVERTIME + Standby Pay</span><br/>--}}
                        <span class="contract_label">Contract : </span><span class="contract_value">
                            @if($seafarerContract->pay_leave > 0)
                                OVERTIME (LEAVE PAY)
                            @else
                                Fixed
                            @endif
                        </span>
                    </p>
                </td>
                <td class="td_normal td_last_cell_in_row">
                    <p>
                        <span class="contract_label_bold">7. Working Hours</span><br/>
                        <span class="contract_label">Basic hours of work per Day : </span><span class="contract_value">12</span><br/>
                        <span class="contract_label">Basic hours of work per Week : </span><span class="contract_value">84</span>
                    </p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="td_normal td_last_cell_in_row td_last_cell_in_col" style="padding: 0in 0.08in -0.03in">
                    <ol>
                        <li/>
                        <p class="contract_terms">
                            The applicable Propetco T&amp;C shall be considered to be incorporated into and to form part of the contract. Refer to Appendix 1 Propetco T&amp;C : Propetco Sdn Bhd Terms &amp; Conditions / Handbook for Seafarers.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            Ship’s Articles shall be deemed for all purposes to include
                            the terms of this Contract (including the applicable Propetco T&amp;C)
                            and it shall be the duty of the Company to ensure that the
                            Ship’s Articles reflect these terms. These terms shall take
                            precedence over all other terms.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            crew shall attend the necessary Trainings and Certified in
                            accordance with the mandatory instruments adopted by the
                            International Maritime Organization. The crew shall not be
                            engaged and allowed to work unless they are trained or certified
                            as competent or otherwise qualified to perform their
                            duties.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            Ship Owner shall have at their discretion, the option of
                            transferring Seafarers from one vessel to another vessel,
                            provided that there will not be any interruption of time for
                            calculation of leave benefits nor increase in length of service.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            Seafarer shall not be entitled to wages for any time during
                            which he unlawfully refuses to be transferred or neglects to
                            work, when required, whether before or after the time fixed by
                            the agreement for his commencement of such work.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The Seafarer’s rights to wages and provisions shall be taken to
                            begin at the time he commences work or at the time specified in
                            the agreement, whichever first happens. Travelling time to join
                            vessel shall not be considered payable. In conclusion crew
                            arriving vessel after <b>15:00hours</b>
                            shall not be counted and payable day.
                        </p>
                        <li/>
                        <p class="contract_terms" style="line-height: 150%">
                            <b>All
                                Seafarers are responsible to pay for their PERSONAL INCOME TAXES
                                imposed by the Country of Origin.
                            </b>
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            first thirty (30) days of service shall be considered a
                            probationary period, which entitles the Ship Owner or its
                            representative, i.e. the Master of the vessel, to terminate the
                            Crew Contract by giving seven (7) days’ notice.
                        </p>
                        <br>
                    </ol>
                </td>
            </tr>
            </tbody>
        </table>
        <table width="100%" cellpadding="7" cellspacing="0" style="padding-top: 80px;">
            <tbody>
            <tr>
                <td colspan="3" class="td_normal td_last_cell_in_row td_last_cell_in_col">
                    <p class="contract_label_bold">TERMS OF THE CONTRACT/ Cont.</p>
                    <ol start="9" style="padding-bottom: 0px; margin-bottom: 0px">
                        <li/>
                        <p class="contract_terms">
                            During
                            the probationary period, the Seafarer is entitled to terminate
                            the Crew Contract by giving seven (7) day’s notice.
                        </p>
                        <li/>
                        <p class="contract_terms">If
                            the Crew Contract is terminated within the probationary period
                            by the Owners/Company, the repatriation costs shall be paid by
                            the Owners/Company.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            If
                            the Crew Contract is terminated within the probationary period
                            by the Seafarer, the repatriation costs shall be paid by the
                            Seafarer.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            Ship Owners / Company shall not be entitled to terminate the
                            Crew Contract of a Seafarer prior to the expiration of his
                            period of engagement, except as specified below;
                        </p>
                    </ol>
                    <ol type="i" style="padding-top: 0px; margin-top: 0px">
                        <li/>
                        <p class="contract_terms">
                            upon the total loss of the Ship; or
                        </p>
                        <li/>
                        <p class="contract_terms">
                            when
                            the Ship has been laid up for a continuous period of at least
                            one (1) month; or
                        </p>
                        <li/>
                        <p class="contract_terms">
                            upon
                            the sale of the Ship;
                        </p>
                        <li/>
                        <p class="contract_terms">
                            upon
                            the misconduct of a Seafarer giving rise to a lawful entitlement
                            to dismiss; or
                        </p>
                        <li/>
                        <p class="contract_terms">
                            upon
                            repatriating the Seafarer due to sickness or, injury
                        </p>
                    </ol>
                    <ol start="13">
                        <li/>
                        <p class="contract_terms">
                            The
                            crew shall be considered as released of his duties and
                            discharged from this contract with the Employer upon completion
                            of their shipboard service as stated in the <b>Duration
                                of Service Onboard </b>and
                            both parties have no holding of each other from his/her
                            obligations.
                        </p>
                    </ol>
                    <p class="contract_terms" style="padding-left: 0.4in">
                        For
                        avoidance of doubt, this Contract shall be deemed terminated upon
                        completion of their shipboard service <b>unless
                            an employment agreement has been drawn up prior to the engagement
                            and agreed by both parties.</b>
                    </p>
                    <ol start="14">
                        <li/>
                        <p class="contract_terms" style="line-height: 150%">
                            <b>If
                                the Crew / Crews are found to be involved in a STRIKE / RIOT at
                                the Owners premise or on the Vessel, he shall be terminated
                                within 24hours upon confirmation given by the Master / the Ship
                                Owner.</b>
                        </p>
                        <li/>
                        <p class="contract_terms" style="line-height: 150%">
                            <b>For
                                safety reasons NO PERSONAL HANDPHONES are allowed while Crews
                                are on duty at Bridge, Deck &amp; Engine Room. The crew shall
                                provide to their immediate family members the Vessels Official
                                Handphones and Office Contact Number in case of emergencies. </b>
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            misuse of legitimate drugs, or the use, possession,
                            distribution, or sale of illicit or non prescription drugs on
                            board is prohibited.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            Ship Owner / Company will carry out urine / blood etc for
                            pre-employment, periodic, random, unannounced, and post-accident
                            tests to detect for alcohol consumption or drug abuse.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            When
                            any crew are suspected beyond reasonable doubt of using,
                            distributing or smuggling dangerous drugs / alcohol, instant
                            dismissal, together with further legal action as necessary shall
                            be taken against the crew.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            The
                            detection by the representative of the Ship Owner / Master of an
                            illicit or non-prescription drug, following a random,
                            unannounced or a post-accident test, will initiate formal
                            proceedings that will result in suspension from duty pending
                            full investigation, or dismissal at the next most suitable port.
                            Crew salary during the duration of this proceeding if found
                            positive shall be deducted from the month salary.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            Refusal
                            to cooperate / insubordination of Master’s instruction in a
                            random, unannounced or post-accident drug / alcohol test, will
                            be considered as obstructing an authorised person in the course
                            of his lawful duty, and will, therefore, be grounds for
                            disciplinary proceedings.</p>
                        <li/>
                        <p class="contract_terms">
                            Upon
                            the misconduct of a Seafarer giving rise to a lawful entitlement
                            to dismiss, the Ship Owner /Company shall, prior to dismissal,
                            hold a hearing before a Committee consisting of the Master as
                            Chairman, Chief Officer, Technical General Manager and Crewing
                            HOD.</p>
                        <li/>
                        <p class="contract_terms">
                            The
                            Chairman shall question the Seafarer and any witnesses who might
                            be able to provide information in the case. The remaining
                            members of the Committee and the Seafarer may ask questions
                            either through the Chairman or directly, with the Chairman's
                            consent. The submitted statements shall be entered into the log
                            book. The statements shall be read out to those who have
                            submitted them. If the Master makes a decision in the matter,
                            the Master shall state the grounds for it and the decision shall
                            be entered into the log book. The members of the Committee shall
                            verify by signature the accuracy of the statements.</p>
                        <li/>
                        <p class="contract_terms">
                            A
                            decision on dismissal shall be made as soon as possible and, at
                            the latest, within fourteen (14) days after the circumstances of
                            the case become known, unless special conditions necessitate a
                            longer time limit. The Seafarer shall, if possible, be informed
                            of the decision immediately.</p>
                        <li/>
                        <p class="contract_terms">
                            Fighting
                            onboard shall not be tolerated and any damages to Ship Owner’s
                            property caused by the crew during any unwanted incident shall
                            be compensated by CREW.</p>
                        <li/>
                        <p class="contract_terms">
                            Crews
                            at all times not allowed to possess any kind of dangerous
                            weapon/weapons on board such as knife, sword, parang, air gun,
                            fireworks and etc. Crew will be dismissed immediately if found
                            possessing dangerous weapons onboard.</p>
                        <li/>
                        <p class="contract_terms">
                            Gambling
                            and excessive betting is strictly prohibited onboard.</p>
                        <li/>
                        <p class="contract_terms">
                            Crews
                            are to take very good care of company vessels and assets onboard
                            to the best possible. Misuse of company assets will result to
                            disciplinary action and crew shall be removed immediately from
                            the vessel.</p>
                        <li/>
                        <p class="contract_terms">
                            Crew
                            by all means are not allowed and prohibited from collaborating,
                            dealing or selling vessel MGO or bunker. If found guilty, he/she
                            will be charge under the criminal acts.</p>
                        <li/>
                        <p class="contract_terms">
                            Crew
                            is prohibited to leak any information about the vessels or
                            projects the vessel are involved in, or information about the
                            company to third party or any regulatory authority without the
                            Employer or written approval. If found guilty, he or she will be
                            brought to court under breaching company secrecy clause.
                        </p>
                        <li/>
                        <p class="contract_terms">
                            Crew
                            on board must all the times perform his/her duties to the best
                            possible and strictly follow the superior instructions.</p>
                        <li/>
                        <p class="contract_terms">
                            For
                            the purpose of this Crew Contract, no public holidays shall be
                            observed whilst the crew are duty onboard, unless announced by
                            the Ship Owner.</p>
                        <li/>
                        <p class="contract_terms">
                            etc</p>
                        <br>
                    </ol>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="table_label">CONFIRMATION OF THE CONTRACT</p>

        <table width="100%" cellpadding="7" cellspacing="0" style="table-layout: fixed;">
            <tr>
                <td width="33%" class="td_normal td_last_cell_in_col" valign="top">
                    <p class="contract_label">Signature of Shipowner/Employer on behalf of the Shipowner:
                    </p>
                    @if(!$seafarerContract->is_dummy)
                        <p style="margin-left: 50px; margin-top: -15px">
                            <span><img src="{{asset('print/propetco_chop.jpg')}}" alt="" width="100px"></span>
                        </p>
                        <p class="contract_label">
                            Place: glenmarie shah alam<br>
                            Date : <b>{{\Illuminate\Support\Carbon::createFromFormat('Y-m-d',$seafarerContract->scheduled_sign_on_date)->format('d M Y')}}</b>
                        </p>
                    @endif
                </td>
                <td width="33%" class="td_normal td_last_cell_in_col" valign="top">
                    <p class="contract_label">
                        Signature of Seafarer:<br>
                        <b>(PLEASE SIGN &amp; PUT DATE OF SIGNATURE )</b>
                    </p><br><br><br><br>
                    <p class="contract_label">Place:
                        <br>
                        Date:
                    </p>
                </td>
                <td width="34%" class="td_normal td_last_cell_in_col td_last_cell_in_row" valign="top" style="word-wrap:break-word">
                    <p class="contract_label" style="margin-bottom: 0in">REMARKS</p>
                    <p class="table_label">{{ $seafarerContract->remarks }}</p>
                </td>
            </tr>
        </table>

        <p class="contract_label" style="margin-bottom: 0in; line-height: 100%">
            * Delete where applicable <br>
            ** Shall be adjusted according to actual sign on date / vessel movemen
        </p>
    </main>

</body>
</html>
