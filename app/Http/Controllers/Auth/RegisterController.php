<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;


use App\Mail\User_email;
use Illuminate\Support\Facades\Mail;
use App\Repositories\BookingRepository;
 
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->repository = new BookingRepository();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    { 
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'], 
            'last_name'  => ['required', 'string', 'max:255'], 
            'role'       => ['integer'],
            // 'phone_number' => ['required', 'max:255' ],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password'   => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {  
        return User::create([
            'first_name'   => $data['first_name'],
            'middle_name'  => $data['middle_name'],
            'last_name'    => $data['last_name'],
            'role'         => 2,
            'status'       => 2,
            'phone_number' => $data['phone_number'],
            'email'        => $data['email'],
            'password'     => Hash::make('1234'),
            // 'password'     => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

         // $this->guard()->login($user);
        //this commented to avoid register user being auto logged in
 
         // EMAIL TO USER
        $details = [
            'subject'     => 'Meeting Room Reservation System - User Registration for Approval',  
            'title'       => 'Dear  ' . $request['first_name'] . ' ' .  $request['last_name'] . ',',  
            'body'        => 'You have successfully registered to the Meeting Room Reservation System. Your registration is now pending for approval.',  
            // 'credentials' => 'Here are your credentials: ',  
            // 'email'       => $request['email'],  
            // 'password'    => $request['password'],  Str::random(10);
        ]; 
        
        Mail::to($request['email'])->send(new User_email($details));
        

        
        $records =  $this->repository->getAdminUsers();    
        foreach($records as $record) { 
            // EMAIL TO ADMIN
            $details = [
                'subject' => 'Meeting Room Reservation System - User Registration for Approval',  
                'title'   => 'Dear  ' . $record->first_name . ' ' .  $record->last_name . ',',  
                'body'    => $request['first_name'] . ' ' .  $request['last_name'] . ' has registered to the Conference and is waiting for your approval.',  
            ]; 
            Mail::to($record->email)->send(new User_email($details)); 
       }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        //we can send users account formation email here or anything we want with users even fire that Registered event created earlier

    }
}
