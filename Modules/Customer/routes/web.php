<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Backend\CustomersController;

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
/*
*
* Backend Routes
*
* --------------------------------------------------------------------
*/
Route::group(['prefix' => 'app', 'as' => 'backend.', 'middleware' => ['auth']], function () {
    /*
    * These routes need view-backend permission
    * (good if you want to allow more than one group in the backend,
    * then limit the backend features by different roles or permissions)
    *
    * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
    */

    /*
     *
     *  Backend Customers Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('index_list', [CustomersController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [CustomersController::class, 'index_data'])->name('index_data');
        Route::get('show/{id}', [CustomersController::class, 'show'])->name('show');
        Route::get('trashed', [CustomersController::class, 'trashed'])->name('trashed');
        Route::get('trashed/{id}', [CustomersController::class, 'restore'])->name('restore');
        Route::post('bulk-action', [CustomersController::class, 'bulk_action'])->name('bulk_action');
        Route::post('change-password', [CustomersController::class, 'change_password'])->name('change_password');
        Route::post('update-status/{id}', [CustomersController::class, 'update_status'])->name('update_status');
        Route::post('block-customer/{id}', [CustomersController::class, 'block_customer'])->name('block-customer');
        Route::post('verify-customer/{id}', [CustomersController::class, 'verify_customer'])->name('verify-customer');
        Route::get('export', [CustomersController::class, 'export'])->name('export');
        Route::post('unique_email', [CustomersController::class, 'uniqueEmail'])->name('unique_email');

        // POST-only update endpoint to avoid method spoofing issues in some environments
        Route::post('{id}/update', [CustomersController::class, 'update'])->name('post_update');
        
        // Devis routes
        Route::get('get-packages', [CustomersController::class, 'getPackages'])->name('get_packages');
        Route::get('get-package-details', [CustomersController::class, 'getPackageDetails'])->name('get_package_details');
        Route::get('get-taxes', [CustomersController::class, 'getTaxes'])->name('get_taxes');
        Route::post('save-devis', [CustomersController::class, 'saveDevis'])->name('save_devis');
        Route::get('customer-devis/{customerId}', [CustomersController::class, 'getCustomerDevis'])->name('customer_devis');
        Route::get('test-devis/{customerId}', [CustomersController::class, 'testDevis'])->name('test_devis');
        Route::post('update-devis-status/{devisId}', [CustomersController::class, 'updateDevisStatus'])->name('update_devis_status');
        Route::post('convert-devis/{devisId}', [CustomersController::class, 'convertDevisToFacture'])->name('convert_devis');
        Route::delete('delete-devis/{devisId}', [CustomersController::class, 'deleteDevis'])->name('delete_devis');
        Route::get('print-devis/{customerId}', [CustomersController::class, 'printDevis'])->name('print_devis');
        Route::get('print-devis-pdf/{devisId}', [CustomersController::class, 'printDevisPdf'])->name('print_devis_pdf');
        Route::get('print-facture-pdf/{factureId}', [CustomersController::class, 'printFacturePdf'])->name('print_facture_pdf');
        Route::get('print-latest-facture/{customerId}', [CustomersController::class, 'printLatestFacture'])->name('print_latest_facture');
        Route::get('print-booking-invoice/{bookingId}', [CustomersController::class, 'printBookingInvoice'])->name('print_booking_invoice');
        Route::post('add-payment/{bookingId}', [CustomersController::class, 'addPayment'])->name('add-payment');
        Route::delete('remove-payment/{bookingId}', [CustomersController::class, 'removePayment'])->name('remove-payment');
        Route::post('add-devis-facture-payment/{factureId}', [CustomersController::class, 'addDevisFacturePayment'])->name('add-devis-facture-payment');
        Route::delete('remove-devis-facture-payment/{factureId}', [CustomersController::class, 'removeDevisFacturePayment'])->name('remove-devis-facture-payment');

        // Medical Records routes
        Route::get('{customerId}/medical-records', [CustomersController::class, 'getMedicalRecords'])->name('medical_records.index');
        Route::post('{customerId}/medical-records', [CustomersController::class, 'storeMedicalRecord'])->name('medical_records.store');
        Route::delete('medical-records/{recordId}', [CustomersController::class, 'deleteMedicalRecord'])->name('medical_records.delete');

        // Medical History routes
        Route::get('{customerId}/medical-history', [CustomersController::class, 'getMedicalHistory'])->name('medical_history.index');
        Route::post('{customerId}/medical-history', [CustomersController::class, 'storeMedicalHistory'])->name('medical_history.store');
        Route::delete('medical-history/{historyId}', [CustomersController::class, 'deleteMedicalHistory'])->name('medical_history.delete');
        // Types
        Route::get('medical-history-types', [CustomersController::class, 'getMedicalHistoryTypes'])->name('medical_history.types');
        Route::post('medical-history-types', [CustomersController::class, 'storeMedicalHistoryType'])->name('medical_history.types_store');

        // Acts (Actes) routes
        Route::get('{customerId}/acts', [CustomersController::class, 'getActs'])->name('acts.index');
        Route::post('{customerId}/acts', [CustomersController::class, 'storeAct'])->name('acts.store');
        Route::put('acts/{actId}', [CustomersController::class, 'updateAct'])->name('acts.update');
        Route::delete('acts/{actId}', [CustomersController::class, 'deleteAct'])->name('acts.delete');
        Route::get('acts-options', [CustomersController::class, 'actOptions'])->name('acts.options');
        Route::get('acts-service-meta/{serviceId}', [CustomersController::class, 'actServiceMeta'])->name('acts.service_meta');

        // Act Gallery routes
        Route::get('acts/{actId}/galleries', [CustomersController::class, 'getActGalleries'])->name('acts.galleries.index');
        Route::post('acts/{actId}/galleries', [CustomersController::class, 'storeActGallery'])->name('acts.galleries.store');
        Route::post('galleries/{galleryId}/images', [CustomersController::class, 'addActGalleryImages'])->name('acts.galleries.images.add');
        Route::put('galleries/{galleryId}', [CustomersController::class, 'updateActGallery'])->name('acts.galleries.update');
        Route::delete('galleries/{galleryId}/images/{mediaId}', [CustomersController::class, 'deleteActGalleryImage'])->name('acts.galleries.images.delete');
        Route::delete('galleries/{galleryId}', [CustomersController::class, 'deleteActGallery'])->name('acts.galleries.delete');

    });
    Route::resource('customers', CustomersController::class);
});
