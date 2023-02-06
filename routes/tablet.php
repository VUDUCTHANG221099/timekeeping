<?php
 use App\Http\Controllers\API\apiController;
 use App\Http\Controllers\tablet\checkoutController;
 use App\Http\Controllers\tablet\loginController;
 use App\Http\Middleware\tablet\CheckloginMiddleware;
 use App\Http\Middleware\tablet\ChecklogoutMiddleware;
 use Illuminate\Support\Facades\Route;

 Route::get('', function () {
     return redirect()->route('tabletLoginGet');
 });
 Route::middleware(ChecklogoutMiddleware::class)->group(function () {
     Route::prefix('dang-nhap')->group(
         function () {
             Route::get("/", [loginController::class, 'index'])->name("tabletLoginGet"); //Check
             Route::post("/", [loginController::class, "login"])->name("tabletLoginPost"); //Check
         }
     );
 });
 Route::middleware(checkloginMiddleware::class)->group(function () {
     Route::prefix('checkout')->group(
         function () {
             Route::get("/", [checkoutController::class, 'index'])->name("tabletCheckoutGet"); //Check
             Route::post("/", [checkoutController::class, 'checkout'])->name("tabletCheckoutPost"); //Check
         }
     );
     Route::get("logout/", [loginController::class, 'logout'])->name("tabletLogoutGet"); //Check
     Route::prefix('api')->group(
         function () {
             Route::get("project/{dayWork?}", [apiController::class, 'listProject'])->name('listProject'); //Check
             //In file checkout of folder tablet
             Route::get('count-project-of-employee/{day_work?}', [apiController::class, 'countProject'])->name('countProject'); //Check
             //In file checkout of folder tablet
         }
     );
 });
