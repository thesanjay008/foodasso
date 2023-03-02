<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('customer')->group( function() {
    Route::post('getsplashScreen', 'Api\Customer\CommonController@getsplashScreen');
    Route::post('login', 'Api\Customer\Auth\AuthController@login');
    Route::post('registration', 'Api\Customer\Auth\AuthController@registration');
    Route::post('activeAccount', 'Api\Customer\Auth\AuthController@active');
    Route::post('sendOTP', 'Api\Customer\CommonController@sendOTP');
    Route::post('verifyOTP', 'Api\Customer\CommonController@verifyOTP');
    Route::post('forgot_password', 'Api\Customer\Auth\PasswordResetController@forgot_password');
    Route::post('reset_password', 'Api\Customer\Auth\PasswordResetController@reset_password');
    Route::post('logout', 'Api\Customer\Auth\PasswordResetController@logout');

    Route::middleware(['auth:api'])->group( function () {
      
      //USER
      Route::get('get_myProfile', 'Api\Customer\UserController@profile');
      Route::post('updateProfile', 'Api\Customer\UserController@update');
      Route::get('get_dependent_types', 'Api\Customer\UserController@get_dependent_types');
      Route::post('save_dependent', 'Api\Customer\UserController@save_dependent');
      Route::post('edit_dependent', 'Api\Customer\UserController@edit_dependent');
      Route::post('delete_dependent', 'Api\Customer\UserController@delete_dependent');
      Route::post('get_dependents', 'Api\Customer\UserController@get_dependents');
      Route::get('dependent/{id?}', 'Api\Customer\UserController@show_dependent');
      Route::get('get_addresses', 'Api\Customer\UserController@addresses');
      Route::post('save_address', 'Api\Customer\UserController@save_address');
      Route::post('delete_address', 'Api\Customer\UserController@delete_address');
      Route::post('my_activity', 'Api\Customer\UserController@myActivity');
      Route::get('my_appointments', 'Api\Customer\UserController@appointments');
      Route::post('edit_appointment', 'Api\Customer\UserController@editAppointment');
      Route::post('cancel_appointment', 'Api\Customer\UserController@cancelAppointment');
      Route::post('update_deviceToken', 'Api\Customer\UserController@update_deviceToken');
    	Route::get('logout', 'Api\Customer\Auth\AuthController@logout');
      
      // SETTINGS
      Route::post('save_generalSettings', 'Api\Customer\UserController@savegeneralSettings');
      Route::get('get_generalSettings', 'Api\Customer\UserController@getgeneralSettings');
      Route::post('saveInterested_tags', 'Api\Customer\UserController@save_tags');
    	
    	Route::get('get_interestedTags','Api\Customer\InterestedTagController@index');
    	

      // Dependents
      //Route::post('get_dependents','Api\Customer\DependentController@index');
      //Route::get('dependent/{id}','Api\Customer\DependentController@show');

      // DOCTORS
      Route::post('book_doctor','Api\Customer\DoctorController@book');
      Route::post('doctorAppointmentPreview', 'Api\Customer\DoctorController@appointmentPreview');

      // NURSES
      Route::get('get_nurses/{nursing_home_id}','Api\Customer\NurseController@index');
      Route::get('nurse/{id}','Api\Customer\NurseController@show');
      Route::post('get_nurseAvailability', 'Api\Customer\NurseController@availabilities');
      Route::post('nurseAppointmentPreview', 'Api\Customer\NurseController@appointmentPreview');
      Route::post('book_nurse','Api\Customer\NurseController@book');
    
      // MEDICAL ROOM 
      Route::post('get_madicalRooms','Api\Customer\MedicalController@index');
      Route::post('madicalRoom/{id}','Api\Customer\MedicalController@show');


      // CART
      Route::get('carts','Api\Customer\CartController@index');
      Route::post('addToCart','Api\Customer\CartController@add');
      Route::post('updateCart','Api\Customer\CartController@update');
      Route::post('deleteCart','Api\Customer\CartController@delete');
      Route::get('checkout','Api\Customer\CartController@checkout');
      Route::post('place_order','Api\Customer\CartController@place_order');
      
      // NOTIFICATION
      Route::get('notifications','Api\Customer\NotificationController@list');
      
      // PURCHASE
      Route::post('purchase_moduleService','Api\Customer\PurchaseModuleService@purchase');
      Route::get('purchase_history','Api\Customer\OrderController@index');
      Route::post('medication_medical_order_detail','Api\Customer\OrderController@MedicationMedicalOrderDetail');

      Route::post('get/medicals','Api\Customer\MedicalController@index');
      Route::get('medical/{slug}','Api\Customer\MedicalController@show');

      // RATINGS
      Route::post('get_ratingQuestions','Api\Customer\RatingController@index');
      Route::post('submitReview','Api\Customer\RatingController@submit');

      // MEDICAL EQUIPMENT
      Route::post('get_medicalEquipments','Api\Customer\MedicalController@equipments');

      //MEDICAL REMINDER
      Route::post('add_medicationReminder','Api\Customer\MedicationReminderController@add_medical_reminder');
      Route::get('get_medicationReminder','Api\Customer\MedicationReminderController@index');
      Route::post('get_medicationReminderDetails','Api\Customer\MedicationReminderController@reminder_details');
      Route::post('delete_medicationReminder','Api\Customer\MedicationReminderController@delete_reminder');
      Route::post('edit_medicationReminder','Api\Customer\MedicationReminderController@edit_medical_reminder');
      Route::post('toggle_medicationReminderStatus','Api\Customer\MedicationReminderController@toggle_status');
    });
    
    // FOR TESTING
    Route::get('testing', 'Api\Customer\CommonController@test');
    Route::get('run_reminderCron', 'CronController@reminderCron');
    
    // COUTRTY, STATE, CITY LIST
    Route::get('get_countries', 'Api\Customer\CountryController@index');
    Route::get('get_state', 'Api\Customer\StateController@index');
    Route::get('get_cities', 'Api\Customer\CityController@index');

    // DEPARTMENT LIST
    Route::get('get_departments','Api\Customer\DepartmentController@index');
    Route::get('get_services','Api\Customer\ServiceController@index');

    /* EVENTS */
    Route::post('get_events', 'Api\Customer\EventController@index');
    Route::get('event/{slug}', 'Api\Customer\EventController@event');

    // medical rooms
    Route::post('get_medicalRooms','Api\Customer\MedicalRoomController@index');
    Route::post('get_hospitalMedicalRooms','Api\Customer\MedicalRoomController@hospital_rooms');
    Route::get('medicalRoom/{id}','Api\Customer\MedicalRoomController@show');
    Route::post('madicalRoomDetails','Api\Customer\HospitalController@hospitalRooms');
    Route::get('madicalRoomfilter_masterData','Api\Customer\MedicalRoomController@masterData');


    // HEALTH INSURANCE
    Route::post('get_insuranceCompanies', 'Api\Customer\InsuranceController@index');
    Route::post('get_insurancePackages','Api\Customer\InsuranceController@packages');
    Route::get('insurancePackage/{id?}','Api\Customer\InsuranceController@showpackage');
    Route::get('insuranceCompany_filter_masterData','Api\Customer\InsuranceController@masterData');

    // OFFERS
    Route::post('get_offers', 'Api\Customer\OfferController@index');
    Route::get('offer/{id}', 'Api\Customer\OfferController@show');

    // HOSPITAL
    Route::post('get_hospitals','Api\Customer\HospitalController@index');
    Route::get('hospital/{id}','Api\Customer\HospitalController@show');
    Route::get('get_ins_package_detail','Api\Customer\InsuranceController@show');

    // CLINICS
    Route::post('get_clinics', 'Api\Customer\ClinicController@index');
    Route::get('clinic/{id}', 'Api\Customer\ClinicController@show');

    // DOCTORS
    Route::post('get_doctors', 'Api\Customer\DoctorController@index');
    Route::get('doctor/{id?}', 'Api\Customer\DoctorController@show');
    Route::post('get_doctorAvailability/{id?}', 'Api\Customer\DoctorController@availabilities');

    // NURSING HOME
    Route::post('get_nursingHomes','Api\Customer\NursingHomeController@index');
    Route::get('nursingHome/{id}','Api\Customer\NursingHomeController@show');

    // PHARMACIES 
    Route::post('get_pharmacies','Api\Customer\PharmacyController@index');
    Route::post('pharmacy','Api\Customer\PharmacyController@show');
    //Route::post('pharmacy_new','Api\Customer\PharmacyController@show_new');

    // PRODUCTS
    Route::post('get_categoryProducts','Api\Customer\ProductController@categoryProducts');

    

    // laboratory
    Route::post('get_laboratories','Api\Customer\LaboratoryController@index');
    Route::post('get_laboratoryPackages','Api\Customer\LaboratoryController@package_index'); 
    Route::get('laboratoryPackage/{package_id?}','Api\Customer\LaboratoryController@package_show');
    Route::get('laboratory_filter_masterData','Api\Customer\LaboratoryController@masterData');

    // PAYMENT FLOW STATUS
    Route::get('payment-success','Api\Customer\OrderController@paymet_success');
    Route::get('payment-success-message','Api\Customer\OrderController@paymet_success_message');
    Route::get('payment-failed','Api\Customer\OrderController@paymet_failed');
    Route::get('payment-failed-message','Api\Customer\OrderController@paymet_failed_message');
});