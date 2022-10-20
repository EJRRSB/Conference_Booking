<body>
    <p>{{ $details['title'] }}</p> 
    <p>{{ $details['body'] }}</p> 
    @if (isset($details['credentials'])) 
    <p>{{ $details['credentials'] }}</p>
    @endif
    @if (isset($details['email'])) 
    <p>{{ $details['email'] }}</p>
    @endif
    @if (isset($details['password'])) 
    <p>{{ $details['password'] }}</p>
    @endif
    <p>Thank You</p>  
    <p>Best regards,</p>  
    <p>Meeting Room Reservation System</p>  
    <p>This is a system-generated message. Do not reply to this email address.</p> 
</body>