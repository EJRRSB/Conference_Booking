  
<body> 
    <div class="table-responsive" style="width: 60%">
        <p>{{ $details->subject }}</p> 
        <p>{{ $details->intro }}</p> 
        <p>{{ $details->body }} </p>  
        <table class="table mb-0" style="border: 1px solid;">
            <thead class="table-light" style="border: 1px solid;">
            </thead> 
            <tbody style="border: 1px solid;">   
                <tr> 
                    <td style="background-color: #F0F8FF;">Date: </td>
                    @if (isset($details->date)) 
                        <td>{{ $details->date }}</td>
                    @endif 
                    @if (isset($details->changes['date'])) 
                        <td>{{ $details->changes['date'] }}</td>
                    @endif  
                </tr> 
                <tr>
                    <td style="background-color: #F0F8FF;">Start Time: </td>
                    @if (isset($details->start_time))  
                        <td>{{  date("h:i A", strtotime($details->start_time))  }} {{  'GMT' . date('O', strtotime($details->start_time)) }}</td>
                    @endif
                    @if (isset($details->changes['start_time'])) 
                        <td>{{ $details->changes['start_time'] }} {{  'GMT' . date('O', strtotime($details->changes['start_time'])) }}</td>
                    @endif 
                </tr>
                <tr>
                    <td style="background-color: #F0F8FF;">End Time: </td>
                    @if (isset($details->end_time))  
                        <td>{{  date("h:i A", strtotime($details->end_time))}} {{  'GMT' . date('O', strtotime($details->end_time)) }}</td>
                    @endif
                    @if (isset($details->changes['end_time'])) 
                        <td>{{ $details->changes['end_time'] }} {{  'GMT' . date('O', strtotime($details->changes['end_time'])) }}</td>
                    @endif 
                </tr>
                <tr>
                    <td style="background-color: #F0F8FF;">Booked Room:</td>
                    @if (isset($details->room))  
                        <td>{{ $details->room->name }}</td>
                    @endif
                    @if (isset($details->changes['room_name'])) 
                        <td>{{ $details->changes['room_name'] }}</td>
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
                    @if (isset($details->changes['participants']))  
                        <td>
                            @foreach($details->changes['participants'] as $participant)
                                @if (isset($participant->user))  
                                    <span>{{ $participant->user->first_name . ' ' . $participant->user->last_name  }} </span><br>
                                @else
                                    <span>{{ $participant->guest_email . ' (guest)'  }} </span><br>
                                @endif
                            @endforeach
                        </td> 
                    @else
                    <td>No changes</td>
                    @endif
                </tr> 
                <tr>
                    <td style="background-color: #F0F8FF;">Mode:</td>
                    @if (isset($details->mode))  
                        <td>{{ $details->mode }}</td>
                    @endif
                    @if (isset($details->changes['mode'])) 
                        <td>{{ $details->changes['mode'] }}</td>
                    @endif 
                </tr> 
                <tr>
                    <td style="background-color: #F0F8FF;">Type:</td>
                    @if (isset($details->type))  
                        <td>{{ $details->type }}</td>
                    @endif
                    @if (isset($details->changes['type'])) 
                        <td>{{ $details->changes['type'] }}</td>
                    @endif 
                </tr> 
                    <tr> 
                        <td style="background-color: #F0F8FF;">Internal Info:</td>
                        @if (isset($details->internal_option)) 
                            <td>{{ $details->internal_option }}</td>
                        @else
                            <td></td>
                        @endif
                        @if (isset($details->changes['internal_option'])) 
                            <td>{{ $details->changes['internal_option'] }}</td>
                        @endif 
                    </tr> 
                    <tr>
                        <td style="background-color: #F0F8FF;">External Engagement Number:</td>
                        @if (isset($details->client_number))  
                            <td>{{ $details->client_number }}</td>
                        @else
                            <td></td>
                        @endif
                        @if (isset($details->changes['client_number'])) 
                            <td>{{ $details->changes['client_number'] }}</td>
                        @endif 
                    </tr> 
                    <tr>
                        <td style="background-color: #F0F8FF;">External Client Name:</td>
                        @if (isset($details->client_name))  
                            <td>{{ $details->client_name }}</td>
                        @else
                            <td></td>
                        @endif
                        @if (isset($details->changes['client_name'])) 
                            <td>{{ $details->changes['client_name'] }}</td>
                        @endif 
                    </tr> 
                    <tr>
                        <td style="background-color: #F0F8FF;">External Client Type:</td>
                        @if (isset($details->client_type))  
                            <td>{{ $details->client_type }}</td>
                        @else
                            <td></td>
                        @endif
                        @if (isset($details->changes['client_type'])) 
                            <td>{{ $details->changes['client_type'] }}</td>
                        @endif 
                    </tr>
                    <tr>
                        <td style="background-color: #F0F8FF;">Purpose: </td>
                        @if (isset($details->purpose))  
                            <td>{{ $details->purpose }}</td>
                        @endif
                        @if (isset($details->changes['purpose'])) 
                            <td>{{ $details->changes['purpose'] }}</td>
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
                        @if (isset($details->changes['agenda']))  
                            <td>
                                @foreach($details->changes['agenda'] as $agenda)
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
                        @if (isset($details->changes['it_requirements']))  
                            <td>
                                @foreach($details->changes['it_requirements'] as $it_requirements)
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