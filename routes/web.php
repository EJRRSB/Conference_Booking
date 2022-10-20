<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// USERS
Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
Route::get('/users/getAllUser', [App\Http\Controllers\UserController::class, 'getAllUser'])->name('getAllUser');
Route::post('/users/deleteUser', [App\Http\Controllers\UserController::class, 'deleteUser'])->name('deleteUser');
Route::post('/users/addUser', [App\Http\Controllers\UserController::class, 'addUser'])->name('addUser');
Route::get('/users/getUser/{id}', [App\Http\Controllers\UserController::class, 'getUser'])->name('getUser');
Route::post('/users/updateUser/', [App\Http\Controllers\UserController::class, 'updateUser'])->name('updateUser');
Route::post('/users/approveUser', [App\Http\Controllers\UserController::class, 'approveUser'])->name('approveUser');
Route::get('/users/downloadEmployeeBulkUploadTemplate', [App\Http\Controllers\UserController::class, 'downloadEmployeeBulkUploadTemplate'])->name('downloadEmployeeBulkUploadTemplate');
Route::post('/users/BulkUploadUser', [App\Http\Controllers\UserController::class, 'BulkUploadUser'])->name('BulkUploadUser');
Route::post('/users/changePassUser/', [App\Http\Controllers\UserController::class, 'changePassUser'])->name('changePassUser');



// ROOMS
Route::get('/rooms', [App\Http\Controllers\RoomController::class, 'index'])->name('rooms');
Route::get('/rooms/getAllRooms', [App\Http\Controllers\RoomController::class, 'getAllRooms'])->name('getAllRooms');
Route::post('/users/deleteRoom', [App\Http\Controllers\RoomController::class, 'deleteRoom'])->name('deleteRoom');
Route::post('/users/addRoom', [App\Http\Controllers\RoomController::class, 'addRoom'])->name('addRoom');
Route::get('/users/getRoom/{id}', [App\Http\Controllers\RoomController::class, 'getRoom'])->name('getRoom');
Route::post('/users/updateRoom/', [App\Http\Controllers\RoomController::class, 'updateRoom'])->name('updateRoom');



// BOOKINGS
Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('bookings');
Route::get('/bookings/getAllBookings', [App\Http\Controllers\BookingController::class, 'getAllBookings'])->name('getAllBookings');
Route::get('/bookings/getAvailableRooms', [App\Http\Controllers\BookingController::class, 'getAvailableRooms'])->name('getAvailableRooms');
Route::post('/bookings/addBooking', [App\Http\Controllers\BookingController::class, 'addBooking'])->name('addBooking');
Route::post('/bookings/editBooking', [App\Http\Controllers\BookingController::class, 'editBooking'])->name('editBooking');
Route::post('/bookings/updateStatusBooking', [App\Http\Controllers\BookingController::class, 'updateStatusBooking'])->name('updateStatusBooking');
Route::post('/bookings/updateMultipleStatusBooking', [App\Http\Controllers\BookingController::class, 'updateMultipleStatusBooking'])->name('updateMultipleStatusBooking');
// Route::post('/bookings/declineBooking', [App\Http\Controllers\BookingController::class, 'declineBooking'])->name('declineBooking');
// Route::post('/bookings/cancelBooking', [App\Http\Controllers\BookingController::class, 'cancelBooking'])->name('cancelBooking');
Route::get('/bookings/getParticipants', [App\Http\Controllers\BookingController::class, 'getParticipants'])->name('getParticipants');
Route::get('/bookings/getBookingById/{id}', [App\Http\Controllers\BookingController::class, 'getBookingById'])->name('getBookingById');



// DASHBOARDD
Route::get('/dashboard/getCountsData', [App\Http\Controllers\DashboardController::class, 'getCountsData'])->name('getCountsData');
Route::get('/dashboard/getChartData', [App\Http\Controllers\DashboardController::class, 'getChartData'])->name('getChartData');



// REPORTS
Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports');
Route::get('/reports/ListOfBookingsByStatus', [App\Http\Controllers\ReportController::class, 'ListOfBookingsByStatus'])->name('ListOfBookingsByStatus');
Route::get('/reports/ListOfArchivedBookings', [App\Http\Controllers\ReportController::class, 'ListOfArchivedBookings'])->name('ListOfArchivedBookings');
Route::get('/reports/ListOfUsersByStatus', [App\Http\Controllers\ReportController::class, 'ListOfUsersByStatus'])->name('ListOfUsersByStatus');
Route::get('/reports/ListOfAllRooms', [App\Http\Controllers\ReportController::class, 'ListOfAllRooms'])->name('ListOfAllRooms');



// CALENDAR
Route::get('/calendar', [App\Http\Controllers\CalendarController::class, 'index'])->name('calendar');
Route::get('/calendar/getCalendarInfo', [App\Http\Controllers\CalendarController::class, 'getCalendarInfo'])->name('getCalendarInfo'); 
Route::get('/calendar/getAllCalendarRooms', [App\Http\Controllers\CalendarController::class, 'getAllCalendarRooms'])->name('getAllCalendarRooms'); 
