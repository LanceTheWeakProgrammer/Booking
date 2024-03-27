<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminLogin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\UserQueriesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\WebRatingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TechProfileController;


//Operator Routes
Route::prefix('operator')->group(function () {
    Route::get('/login', [TechProfileController::class, 'showOperatorLoginForm'])->name('operator.login');
    Route::post('/login', [TechProfileController::class, 'operatorLogin'])->name('operator.login.post');
    Route::get('/logout', [TechProfileController::class, 'operatorLogout'])->name('operator.logout');
});

Route::middleware(['auth:operator'])->group(function () {


    Route::get('/operator/dashboard', [TechProfileController::class, 'showOperatorDashboard'])->name('operator.dashboard');
    Route::get('/operator/profile', [TechProfileController::class, 'showOperatorProfile'])->name('operator.profile');
    Route::post('/update-description', [TechProfileController::class, 'updateDescription']);

//Schedule Routes    
    Route::get('/operator/schedules', [ScheduleController::class, 'showSchedules'])->name('operator.schedules');
    Route::post('/mark-as-done/{bookingId}', [ScheduleController::class,'markAsDone'])->name('mark-as-done');
    Route::post('/update-status', [ScheduleController::class, 'updateStatus'])->name('update.status');

//Transaction Routes
    Route::get('/get-transactions', [TransactionController::class, 'getTransactions'])->name('operator.get-transactions');
    Route::get('/operator/transactions', [TransactionController::class, 'showTransactions'])->name('operator.transactions');
    Route::post('/approve-transaction/{bookingId}', [TransactionController::class, 'approveTransaction'])->name('approve-transaction');
    Route::post('/decline-transaction/{bookingId}', [TransactionController::class, 'declineTransaction'])->name('decline-transaction');

//Booking Routes
    Route::post('/decline-reschedule/{bookingId}', [BookingController::class, 'declineReschedule'])->name('decline-reschedule');
    Route::post('/approve-reschedule/{bookingId}', [BookingController::class, 'approveReschedule'])->name('approve-reschedule');
    Route::post('/reschedule-operator-booking/{bookingId}', [BookingController::class, 'requestOperatorReschedule'])->name('request-operator-reschedule');
});

Route::middleware(['auth:operator'])->group(function () {
    Route::get('/operator-notifications', [NotificationController::class, 'fetchOperatorNotif'])->name('operator.notifications.fetch');
    Route::post('/operator-notifications/mark-all-read', [NotificationController::class, 'markAllOperatorRead'])->name('operator-notifications.markAllRead');
    Route::post('/operator-notifications/{id}/mark-read', [NotificationController::class, 'markOperatorRead'])->name('operator-notifications.markRead');
    Route::post('/operator-notifications/{id}/delete', [NotificationController::class, 'deleteOperatorNotif']);
});

Route::get('/send-test-email', [MailController::class, 'sendTestEmail']);


// Admin Login Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLogin::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLogin::class, 'login'])->name('admin.login.post');
    Route::get('/logout', [AdminLogin::class, 'logout'])->name('admin.logout');
});

// Admin Dashboard Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('admin/assets', [DashboardController::class, 'carousel'])->name('admin.assets');
});

//Admin Bookings Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/bookings', [BookingController::class, 'showBookings'])->name('admin.bookings');
    Route::get('/fetch-bookings', [BookingController::class, 'fetchBookings'])->name('fetch.bookings');
    Route::post('/approve-booking/{bookingId}', [BookingController::class, 'approveBooking'])->name('approve.booking');
    Route::post('/decline-booking/{bookingId}', [BookingController::class, 'declineBooking'])->name('decline.booking');
    Route::post('/delete-data/{bookingId}', [BookingController::class, 'deleteData'])->name('delete.data');
    Route::get('/fetch-service-price', [BookingController::class,'fetchServicePrice'])->name('fetch.service');
    Route::post('/cancel-booking/{bookingId}', [BookingController::class, 'cancelBooking'])->name('cancel.booking');
});

//Admin Settings Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/settings', [SettingsController::class, 'settings'])->name('admin.settings');
    Route::get('/general', [SettingsController::class, 'getGeneral']);
    Route::get('/contacts', [SettingsController::class, 'getContacts']);
    Route::get('/members', [SettingsController::class, 'getMembers']);
    Route::post('/update-general', [SettingsController::class, 'updateGeneral']);
    Route::post('/update-contacts', [SettingsController::class, 'updateContacts']);
    Route::post('/shutdown', [SettingsController::class, 'updateShutDown']); 
    Route::post('/add-member', [SettingsController::class, 'addMember']);
    Route::post('/remove', [SettingsController::class, 'removeMember']);
});

//Admin Carousel Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/assets', [CarouselController::class, 'carousel'])->name('admin.assets');
    Route::get('/get-carousel', [CarouselController::class, 'getCarousel']);
    Route::post('/add-image', [CarouselController::class, 'addImage']);
    Route::match(['get', 'post'], '/remove-carousel', [CarouselController::class, 'removeImage']);

    // Routes for promos
    Route::get('/get-promos', [PromoController::class, 'getPromos']);
    Route::post('/add-promo', [PromoController::class, 'addPromo']);
    Route::post('/remove-promo', [PromoController::class, 'removePromo']);
});


//Admin User Queries Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/userqueries', [UserQueriesController::class, 'userqueries'])->name('admin.user_queries');
    Route::post('/userqueries/update', [UserQueriesController::class, 'update'])->name('userqueries.update');
    Route::post('/userqueries/store', [UserQueriesController::class, 'store'])->name('admin.user_queries.store');
});

//Admin Service Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/services', [ServiceController::class, 'service'])->name('admin.services');;
    Route::get('/get-car', [ServiceController::class, 'getCar']);
    Route::post('/add-car', [ServiceController::class, 'addCar']);
    Route::post('/remove-car', [ServiceController::class, 'removeCar']);
    Route::get('/get-service', [ServiceController::class, 'getService']);
    Route::post('/add-service', [ServiceController::class, 'addService']);
    Route::post('/remove-service', [ServiceController::class, 'removeService']); 
});

//Admin Operators Routes

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/operators', [OperatorController::class, 'operators'])->name('admin.operators');
    Route::post('/add-operator', [OperatorController::class, 'addOperator']);
    Route::get('/get-operators', [OperatorController::class, 'getAllOperators']);
    Route::post('/submit-edit', [OperatorController::class, 'editOperator']);
    Route::get('/edit-details', [OperatorController::class, 'getOperator']);
    Route::get('/view-details', [OperatorController::class, 'getOperator']);
    Route::post('/delete-operator', [OperatorController::class, 'deleteOperator']);
    Route::post('/toggle-active', [OperatorController::class, 'toggleStatus']);
});

//Admin Manage User Routes

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/manage_users', [UserManageController::class, 'index'])->name('admin.manage_users');
    Route::get('/user-details/{userId}', [UserManageController::class, 'getUserDetails'])->name('admin.user_details');
    Route::post('/set-flag/{userId}', [UserManageController::class, 'setFlag'])->name('admin.set_flag');
    Route::post('/unban/{userId}', [UserManageController::class, 'unban'])->name('admin.unban');
    Route::post('/dismiss/{userId}', [UserManageController::class, 'dismissUser']);
});

//Admin Notfication Routes
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin-notifications', [NotificationController::class, 'fetchAdminNotif'])->name('admin.notifications.fetch');
    Route::post('/admin-notifications/mark-all-read', [NotificationController::class, 'markAllAdminRead'])->name('admin-notifications.markAllRead');
    Route::post('/admin-notifications/{id}/mark-read', [NotificationController::class, 'markAdminRead'])->name('admin-notifications.markRead');
    Route::post('/admin-notifications/{id}/delete', [NotificationController::class, 'deleteAdminNotif']);
});



//User Routes
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->name('logout');
Route::get('/home', [ViewController::class, 'home'])->name('home');
Route::get('/about', [ViewController::class, 'about'])->name('about');
Route::get('/contact', [ViewController::class, 'contact'])->name('contact');
Route::get('/service', [ViewController::class, 'service'])->name('service');
Route::get('/operator', [ViewController::class, 'operator'])->name('operator');
Route::get('/bookings', [ViewController::class, 'bookings'])->name('bookings');
Route::post('/update-user-status', [UserController::class, 'updateUserStatus']);
Route::post('/submit-web-rating', [WebRatingController::class, 'submitRating'])->name('submit.rating'); 
Route::redirect('/', '/home');

Route::middleware(['auth'])->group(function () {
    Route::get('operator_details/{operatorID}', [ViewController::class, 'operator_details']);
    Route::get('booking_details/{operatorID}', [ViewController::class, 'booking_details']);

    // Bookings
    Route::get('/get-lists', [BookingController::class, 'getListing'])->name('get-lists');
    Route::post('/create-booking', [BookingController::class, 'create'])->name('create');
    Route::get('/receipt/{booking_id}', [ViewController::class, 'receipt'])->name('receipt');
    Route::post('/clear-history', [BookingController::class, 'clearHistory'])->name('clear.history');
    Route::post('/delete-history/{bookingId}', [BookingController::class, 'deleteHistory'])->name('delete.history');
    Route::post('/request-cancel-booking/{bookingId}', [BookingController::class, 'requestCancelBooking'])->name('cancel.request');
    Route::post('/reschedule-booking/{bookingId}', [BookingController::class,'requestReschedule'])->name('request.reschedule');
    Route::post('/approve-operator-reschedule/{bookingId}', [BookingController::class,'approveOperatorReschedule'])->name('approve-operator-request');
    Route::post('/decline-operator-reschedule/{bookingId}', [BookingController::class,'declineOperatorReschedule'])->name('decline-operator-request');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'fetch'])->name('notifications.fetch');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/{id}/delete', [NotificationController::class, 'delete']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/profile-data', [ProfileController::class, 'profileData'])->name('profile.data');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('update.profile');

    //Rating
    Route::post('/submit-rating', [RatingController::class, 'submitRating']);
    Route::get('/get-ratings/{operatorId}', [RatingController::class, 'getRatings']);
    Route::post('/ratings/{id}/{action}', [RatingController::class, 'likeDislike']);
});



/*
|------------------------- -------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


require __DIR__.'/auth.php';

