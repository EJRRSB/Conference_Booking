  
<body> 
    <div class="table-responsive" style="width: 60%">
        <p>{{ $details->subject }}</p> 
        <p>{{ $details->intro }}</p> 
        <p>{{ $details->body }} </p> 
        <table class="table mb-0" style="border: 1px solid;">
            <thead class="table-light" style="border: 1px solid;">
            </thead> 
            <tbody style="border: 1px solid;">   
                @if (isset($details->date)) 
                    <tr> 
                        <td style="background-color: #F0F8FF;">Date: </td>
                        <td>{{ $details->date }}</td>
                    </tr> 
                @endif
                @if (isset($details->recurring_booked_dates))  
                    <tr>
                        <td style="background-color: #F0F8FF;">Booked Dates: </td> 
                            <td>
                                @foreach($details->recurring_booked_dates as $recurring_booked_date)
                                <span>{{ $recurring_booked_date  }}</span><br>
                                @endforeach
                            </td>
                    </tr>
                @endif 
                @if (isset($details->recurring_unavailable_dates))  
                    <tr>
                        <td style="background-color: #F0F8FF;">Unavailable Dates: </td>
                            <td>
                                @foreach($details->recurring_unavailable_dates as $recurring_unavailable_date)
                                <span>{{ $recurring_unavailable_date  }} </span><br>
                                @endforeach
                            </td>
                    </tr>
                @endif 
                <tr>
                    <td style="background-color: #F0F8FF;">Start Time: </td>
                    @if (isset($details->start_time))  
                        <td>{{  date("h:i A", strtotime($details->start_time)) }} {{  'GMT' . date('O', strtotime($details->start_time)) }}</td>
                    @endif
                </tr>
                <tr>
                    <td style="background-color: #F0F8FF;">End Time: </td>
                    @if (isset($details->end_time))  
                        <td>{{  date("h:i A", strtotime($details->end_time))}} {{  'GMT' . date('O', strtotime($details->end_time)) }}</td>
                    @endif
                </tr>
                <tr>
                    <td style="background-color: #F0F8FF;">Booked Room:</td>
                    @if (isset($details->room))  
                        <td>{{ $details->room->name }}</td>
                    @endif
                </tr> 
                <tr>
                    <td style="background-color: #F0F8FF;">Participants:</td>
                    @if (isset($details->participants))  
                        <td>
                            @foreach($details->participants as $participant)
                                @if (isset($participant->user))  
                                    <span>{{ $participant->user->first_name . ' ' . $participant->user->last_name  }} </span><br>
                                @else
                                    <span>{{ $participant->guest_email . ' (guest)'  }} </span><br>
                                @endif
                            @endforeach
                        </td> 
                    @endif
                </tr> 
                <tr>
                    <td style="background-color: #F0F8FF;">Mode:</td>
                    @if (isset($details->mode))  
                        <td>{{ $details->mode }}</td>
                    @endif
                </tr> 
                <tr>
                    <td style="background-color: #F0F8FF;">Type:</td>
                    @if (isset($details->type))  
                        <td>{{ $details->type }}</td>
                    @endif
                </tr> 
                @if (isset($details->internal_option)) 
                    <tr> 
                            <td style="background-color: #F0F8FF;">Internal Info:</td>
                            <td>{{ $details->internal_option }}</td>
                    </tr> 
                @endif
                @if (isset($details->client_number))  
                    <tr>
                        <td style="background-color: #F0F8FF;">External Engagement Number:</td>
                        <td>{{ $details->client_number }}</td>
                    </tr> 
                @endif
                @if (isset($details->client_name))  
                    <tr>
                        <td style="background-color: #F0F8FF;">External Client Name:</td>
                            <td>{{ $details->client_name }}</td>
                    </tr> 
                @endif
                @if (isset($details->client_type))  
                    <tr>
                        <td style="background-color: #F0F8FF;">External Client Type:</td>
                            <td>{{ $details->client_type }}</td>
                    </tr>
                @endif
                <tr>
                    <td style="background-color: #F0F8FF;">Purpose: </td>
                    @if (isset($details->purpose))  
                        <td>{{ $details->purpose }}</td>
                    @endif
                </tr>
                <tr>
                    <td style="background-color: #F0F8FF;">Agenda:</td> 
                    @if (isset($details->agenda))  
                        <td>
                            @foreach($details->agenda as $agenda)
                            <span>{{ $agenda  }} </span><br>
                            @endforeach
                        </td>
                    @endif
                </tr>
                <tr>
                    <td style="background-color: #F0F8FF;">IT Requirements:</td>
                    @if (isset($details->it_requirements))  
                        <td>
                            @foreach($details->it_requirements as $it_requirements)
                            <span>{{ $it_requirements  }} </span><br>
                            @endforeach
                        </td>
                    @endif 
                </tr> 
            </tbody>
        </table>
        @if (isset($details->link))  
            <p>{{ $details->link }} </p> 
        @endif
        <p>Thank You</p>  
        <p>Best regards,</p>  
        <p>Meeting Room Reservation System</p>  
        <p>This is a system-generated message. Do not reply to this email address.</p> 
    </div> 
</body>