<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerBankController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*Application flow
CUSTOMER FLOW
    1. register as a customer
    2. login as a customer
    3. create customer record
    4. create customer bank record
    5. upload documents

ADMIN FLOW
    1. login
    2. list customers
    3. list customer banks
    4. list customer documents
    5. Approve customers (just changing status)
    6. Change customer kyc level (just change kyc level)

   * */

Route::any("custom-login", function () {
    return "Unauthenticated";
})->name("login");

//auth endpoints
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware("auth:sanctum")->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
//auth endpoint ends

//admin endpoints
Route::middleware("auth:sanctum")->prefix("admin")->group(function () {
    Route::get('list-customers', [CustomerController::class, 'index']); //this list all customers
    Route::get('/show-customer/{customer_id}', [CustomerController::class, 'show']); //this shows a single customer
    Route::get('/activate-customer/{customer_id}', [CustomerController::class, 'activateCustomer']);
    Route::get('/deactivate-customer/{customer_id}', [CustomerController::class, 'deactivateCustomer']);

    Route::post('/change-customer-kyc-level', [CustomerController::class, 'changeCustomerKyc']);

});
//admin endpoint ends


//customer endpoints
Route::middleware("auth:sanctum")->group(function () { //it means you must login to access the routes in the group
    Route::post('/store-customer', [CustomerController::class, 'store']);
    Route::post('/store-bank', [CustomerBankController::class, 'store']);
    Route::post('/store-document', [CustomerDocumentController::class, 'store']);

});
//customer endpoints end








