<?php
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB
use Illuminate\Support\Facades\Route;;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\DesignationController;
use App\Http\Controllers\Api\StaffController;
use App\Http\Controllers\Api\StudentV2Controller;
use App\Http\Controllers\Api\TeacherV3Controller;
use App\Http\Controllers\Api\UpazillaController;



Route::get('/division/{id}', [DivisionController::class, 'getById']);
Route::get('/district/{id}', [DistrictController::class, 'getById']);
Route::get('/upazila/{id}', [UpazillaController::class, 'getById']);

// Teacher Api List

Route::get('/teacherAsEmp/{profileId}', [TeacherV3Controller::class, 'getByEmpId']);

Route::get('/teacherAsEmpShort/{profileId}', [TeacherV3Controller::class, 'getByEmpIdShort']);

// Staff Api List
Route::get('/staffAsEmpShort/{profileId}', [StaffController::class, 'getByEmpId']);
// Student Api List

Route::get('/studentAsUniqueIdShort/{profileId}', [StudentV2Controller::class, 'getByUniqueId']);

// For filtered employees
Route::post('/filtered-employees', [EmployeeController::class, 'filterEmployees']);
Route::get('/designation_id/{uid}', [DesignationController::class, 'getById']);




