<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/subscription-pay', [HomeController::class, 'subscription_pay'])->name('subscription.pay');
Route::post('/single-charge', [HomeController::class, 'singleCharge'])->name('single.charge');

Route::get('/plans', [PlanController::class, 'index'])->name('plan.index');
Route::get('/plans/create', [PlanController::class, 'create'])->name('plan.create');
Route::post('/plans/store', [PlanController::class, 'store'])->name('plan.store');
Route::get('/plans/checkout/{plan_id}', [PlanController::class, 'checkout'])->name('plan.checkout');
Route::post('/plans/process', [PlanController::class, 'planProcess'])->name('plan.process');

Route::get('/subscription/list', [PlanController::class, 'subscriptionAll'])->name('subscription.list');
Route::get('/subscription/cancel', [PlanController::class, 'cancelSubscription'])->name('subscription.cancel');
Route::get('/subscription/resume', [PlanController::class, 'resumeSubscription'])->name('subscription.resume');
Route::get('/transaction', [PlanController::class, 'transaction'])->name('transaction');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
