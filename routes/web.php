<?php
use App\Http\Controllers\adminReportController;
use App\Http\Controllers\API\APIadminReportController;
use App\Http\Controllers\API\apiController;
use App\Http\Controllers\API\reportAllController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\employeeController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\positionController;
use App\Http\Controllers\projectController;
use App\Http\Controllers\RedmineCallAPIController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\SlackController;
use App\Http\Middleware\checkLoginMiddleware;
use App\Http\Middleware\checkLogoutMiddleware;
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
Route::middleware(checkLogoutMiddleware::class)->group(function () {
    Route::prefix("dang-nhap")->group(
        function () {
            Route::get("/", [loginController::class, 'index'])->name('login.get'); //Check
            Route::post("/", [loginController::class, 'login'])->name('login.post'); //Check
        }
    );
});
Route::middleware(checkLoginMiddleware::class)->group(function () {
    Route::get("/", [dashboardController::class, 'index'])->name('dashboard.get'); //Check
    Route::get("dang-xuat", [loginController::class, 'logout'])->name('logout.get'); //Check
    Route::prefix("nhan-vien")->group(
        function () {
            //code CRUD employee
            Route::get("/", [employeeController::class, 'index'])->name('employee.get.all'); //Check
            Route::get("them-nhan-vien", [employeeController::class, 'viewCreate'])->name("employee.get.viewCreate"); //Check
            Route::post("them-nhan-vien", [employeeController::class, 'create'])->name("employee.post.create"); //Check
            Route::get("xem-chi-tiet/{id?}", [employeeController::class, 'viewDetal'])->name("employee.get.detail"); //Check
            Route::put("xem-chi-tiet", [employeeController::class, 'addPercent'])->name("employee.put.AddPercent"); //Check
            Route::get("cap-nhat-nhan-vien/{id}", [employeeController::class, 'viewUpdate'])->name("employee.get.viewUpdate"); //Check
            Route::put("cap-nhat-nhan-vien", [employeeController::class, 'update'])->name("employee.put.update"); //Check
            Route::get("cap-nhat-mat-khau/{id}", [employeeController::class, 'viewchangdPassword'])->name("employee.get.changdPassword"); //Check
            Route::put("cap-nhat-mat-khau", [employeeController::class, 'changePassword'])->name("employee.put.changdPassword"); //Check
            Route::get("/destroy/{id}", [employeeController::class, 'destroy'])->name('employee.destroy'); //Check
        }
    );
    Route::prefix("du-an")->group(
        function () {
            //Code CRUD Project
            Route::get("/", [projectController::class, 'index'])->name('project.get.all'); //Check
            Route::get("them-du-an", [projectController::class, 'viewCreate'])->name("project.get.viewCreate"); //Check
            Route::post("them-du-an", [projectController::class, 'create'])->name("project.post.create"); //Check
            Route::get("xem-chi-tiet/{id}", [projectController::class, 'viewDetal'])->name("project.get.detail"); //Check
            Route::post('xem-chi-tiet', [projectController::class, 'addEmployee'])->name("project.post.addEmployee"); //Check
            Route::put("xem-chi-tiet", [projectController::class, 'addPercent'])->name("project.put.AddPercent"); //Check
            Route::get("cap-nhat-du-an/{id}", [projectController::class, 'viewUpdate'])->name("project.get.viewUpdate"); //Check
            Route::put("cap-nhat-du-an/", [projectController::class, 'update'])->name("project.put.update"); //Check
            Route::get("/destroy/{id}", [projectController::class, 'destroy'])->name('project.destroy'); //check
            Route::get("nhan-vien-vai-tro/{id}/{name}", [projectController::class, 'listEmployees'])->name("projectListEmployees");
            Route::put("nhan-vien-vai-tro", [projectController::class, "addEmpoyeeOnProject"])->name("projectAddEmployee");
        }
    );
    Route::prefix("phan-quyen")->group(
        function () {
            //Code CRUD Roles
            Route::get("/", [roleController::class, 'index'])->name("role.get.all"); //Check
            Route::get("/them-phan-quyen", [roleController::class, 'viewCreate'])->name("role.get.viewCreate"); //Check
            Route::post("/them-phan-quyen", [roleController::class, 'create'])->name("role.post.create"); //Check
            Route::get("cap-nhat-phan-quyen/{id}", [roleController::class, 'viewUpdate'])->name("role.get.viewUpdate"); //Check
            Route::put("cap-nhat-phan-quyen", [roleController::class, 'update'])->name("role.put.update"); //Check
            Route::get("/destroy/{id}", [roleController::class, 'destroy'])->name('role.destroy'); //Check
        }
    );
    Route::prefix("vai-tro")->group(
        function () {
            //Code CRUD Position
            Route::get("/", [positionController::class, 'index'])->name("position.get.all"); //Check
            Route::get('them-vai-tro', [positionController::class, 'viewCreate'])->name("position.get.viewCreate"); //Check
            Route::post("/them-vai-tro", [positionController::class, 'create'])->name("position.post.create"); //Check
            Route::get("cap-nhat-vai-tro/{id}", [positionController::class, 'viewUpdate'])->name("position.get.viewUpdate"); //Check
            Route::put("cap-nhat-vai-tro/", [positionController::class, 'update'])->name("position.put.update"); //Check
            Route::get("/destroy/{id}", [positionController::class, 'destroy'])->name('position.destroy'); //Check
        }
    );
    Route::prefix('api')->group(
        function () {
            // API of views project details
            Route::get('count-and-employee/{data?}', [apiController::class, 'employeeAndProjectCount'])
                ->name('apiEmployeeAndProjectCount');
            Route::get('remove-employee-plan/{data?}', [apiController::class, 'removeEmployeePlan'])
                ->name('apiRemoveEmployeePlan');
            // API of views project details
            // API of views dashboard and report of employee
            Route::get('list-monthly-project/{month?}', [apiController::class, 'listMonthlyProject'])
                ->name('apiListMonthlyProject');
            Route::get('plan-and-reality/{month?}', [apiController::class, 'planAndReality'])
                ->name('apiPlanAndReality');
            // API of views dashboard and report of employee
            //API in detal employee
            Route::get("project-plan-employee/{month?}", [apiController::class, 'listPlanOfEmployee'])->name('apilistPlanOfEmployee'); //Check
            Route::get('list-project-employee-month/{data?}', [apiController::class, 'listProjectOfEmployeeMonth'])->name('apilistProjectOfEmployeeMonth');
            //API in detal employee
            //API report of employee
            Route::get('reportAll/{data?}', [reportAllController::class, 'index'])->name('apiReportAllEmployeeMonth');
            //API report of employee
            //TODO: API admin Report
            //TODO: API Schedule And Reality
            Route::get('schedule-and-reality/{yearAndMonth?}', [APIadminReportController::class, 'scheduleAndReality'])
                ->name('apiScheduleAndReality');
            //TODO: API Schedule And Reality
            //TODO: API Project And Employee To Month
            Route::get(
                'project-employee-month/{yearAndMonth?}',
                [APIadminReportController::class, 'ProjectAndEmployeeToMonth']
            )->name('apiProjectAndEmployeeToMonth');
            //TODO: API Project And Employee To Month
            //TODO: API admin Report
        }
    );
    // Đồng bộ hóa với redmine
    Route::get("redmine", [RedmineCallAPIController::class, 'index'])->name('redmineIndex');
    // Đồng bộ hóa với redmine
    // Báo cáo của admin
    Route::get('bao-cao', [reportController::class, 'index'])->name('reportAdmin');
    // Báo cáo của admin
    //Xuất excel
    Route::get('export/{data?}/{user?}', [reportController::class, 'export'])->name('exportOneReport');
    Route::get('export-table-detal/{data?}', [reportController::class, 'exportTableDetal'])
        ->name('exportOneReportInAdmin');
    Route::get('export-all/{data?}', [reportController::class, 'exportAll'])->name('exportAll');
    //Xuất excel
    //Xuất pdf
    Route::get('export-pdf/{data?}', [reportController::class, 'exportPDF'])->name('exportPDF');
    //Xuất pdf
    //TODO Export excel admin report
    Route::get('export-admin/{data?}', [reportController::class, 'exportAdminReport'])->name('exportAdmin');
    //TODO Export excel admin report
    //Báo cáo tổng hợp các dự án và các nhân viên
    Route::get("tong-hop", [adminReportController::class, 'index'])->name('adminReport');
    //Báo cáo tổng hợp các dự án và các nhân viên
   
});
