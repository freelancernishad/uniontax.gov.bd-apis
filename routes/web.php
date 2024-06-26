<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Others\TestController;
use App\Http\Controllers\API\Sonod\SonodController;
use App\Http\Controllers\API\Others\AkpayController;
use App\Http\Controllers\API\Payment\PaymentController;
use App\Http\Controllers\API\Others\DynamicPDFController;
use App\Http\Controllers\API\Tender\TenderListController;
use App\Http\Controllers\API\Tender\TenderFormBuyController;
use App\Http\Controllers\API\Unioninfo\UnioninfoController;
use App\Http\Controllers\API\HoldingTax\HoldingtaxController;
use App\Http\Controllers\API\Expenditure\ExpenditureController;
use App\Http\Controllers\API\HoldingTax\HoldingBokeyaController;
use App\Http\Controllers\API\Notification\NotificationsController;

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
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';

Route::prefix('holding/tax')->group(function () {
    Route::get('/update/{union}', [HoldingtaxController::class, 'updateHoldingTax']);
    Route::get('/renew', [HoldingBokeyaController::class, 'renewHoldingBokeya']);
    Route::get('/bokeya/list', [HoldingtaxController::class, 'bokeyaReport'])->name('holding.tax.bokeya.list');
    Route::get('/certificate_of_honor/{id}', [HoldingtaxController::class, 'holdingCertificate_of_honor']);
    Route::get('/invoice/all/list', [HoldingtaxController::class, 'holdingAllPenddingInvoice']);
    Route::get('/invoice/{id}', [HoldingtaxController::class, 'holdingPaymentInvoice']);
    Route::get('/pay/{id}', [HoldingtaxController::class, 'holding_tax_pay_Online']);
    Route::get('/payment/success', [HoldingtaxController::class, 'holdingPaymentSuccess'])->name('holding.payment.success');
});

Route::prefix('pdf')->group(function () {
    Route::get('/tenders/{tender_id}', [TenderListController::class, 'viewpdf']);
    Route::get('/sder/download/{tender_id}', [TenderListController::class, 'downloadSderPdf']);
    Route::get('/download/{Sname}/{id}', [DynamicPDFController::class, 'pdf']);
    Route::get('/C', [DynamicPDFController::class, 'pdfC']);
});

Route::prefix('tenders')->group(function () {
    Route::get('/form/buy/{tender_id}', [TenderFormBuyController::class, 'showFormBuy']);
    Route::get('/payment/{id}', [TenderListController::class, 'PaymentCreate']);
    Route::get('/{tender_id}', [TenderListController::class, 'TenderForm']);
    Route::post('/{tender_id}', [TenderListController::class, 'TenderForm']);
    Route::post('/submit', [TenderListController::class, 'submitForm']);
    Route::get('/formpay/success', [TenderFormBuyController::class, 'tenderFormPaymentSuccess'])->name('tender.formpay.success');
    Route::get('/deposit/success', [TenderFormBuyController::class, 'tenderdepositPaymentSuccess'])->name('tender.deposit.success');
});

Route::prefix('sonod')->group(function () {
    Route::get('/payment/success/{id}', [SonodController::class, 'sonodpaymentSuccessView']);
    Route::get('/payment/{id}', [SonodController::class, 'sonodpayment']);
    Route::get('/{name}/{id}', [SonodController::class, 'sonodDownload']);
    Route::get('/invoice/{name}/{id}', [SonodController::class, 'invoice']);
    Route::get('/document/{name}/{id}', [SonodController::class, 'userDocument']);
    Route::get('/payment/success/confirm', [SonodController::class, 'sonodpaymentSuccess'])->name('sonod.payment.success.confirm');
    Route::get('/secretary/approve/{id}', [SonodController::class, 'SecretariNotificationApprove']);
    Route::get('/chairman/approve/{id}', [SonodController::class, 'ChairnamNotificationApprove']);
    Route::get('/secretary/pay/{id}', [SonodController::class, 'SecretariNotificationPay']);
});

Route::prefix('payment')->group(function () {
    Route::get('/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/confirm', [SonodController::class, 'sonodpaymentSuccessConfirm']);
    Route::get('/export', [PaymentController::class, 'export']);
});

Route::prefix('akpay')->group(function () {
    Route::get('/test', [TestController::class, 'testPayment']);
});

Route::prefix('textemail')->group(function () {
    Route::get('/', [TestController::class, 'sendEmail']);
});

Route::prefix('smstest')->group(function () {
    Route::get('/', [TestController::class, 'sendEmail2']);
});

Route::get('/details', [NotificationsController::class, 'details']);
Route::get('/unioncreate', function () {
    return view('unioncreate');
});
Route::post('/unionCreate', [UnioninfoController::class, 'unionCreate']);
Route::get('/allow/application/notification', function () {
    return view('applicationNotification');
});

Route::get('/files/{path}', function ($path) {
    return response()->file(Storage::disk('protected')->path($path));
})->where('path', '.*');

