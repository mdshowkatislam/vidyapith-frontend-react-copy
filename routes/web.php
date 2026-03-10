<?php

use App\Http\Controllers\Admin\AutoSeedInstituteController;
use App\Http\Controllers\Admin\StudentAttachedInstituteController;
use App\Http\Controllers\Admin\StudentBoardRegistrationController;
use App\Http\Controllers\Api\InstituteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AjaxController;
use App\Models\StudentHistory;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// ========================= All Testing Routes Here =========================
// ===========================================================================
Route::get('/log', function () {
    Log::debug('This is a debug log test');
    return 'Log written!';
});
Route::get('/phpinfo', function () {
    ob_start();
    phpinfo();
    $phpinfo = ob_get_clean();

    return response()->view('phpinfo-check', [
        'phpinfo' => $phpinfo,
        'curl_loaded' => extension_loaded('curl'),
        'curl_init' => function_exists('curl_init'),
        'curl_exec' => function_exists('curl_exec'),
        'allow_url_fopen' => ini_get('allow_url_fopen'),
    ]);
});

Route::get('/php_info', function () {
    phpinfo();
});

Route::get('/curl-test', function () {
    $ch = curl_init('https://www.google.com');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $out = curl_exec($ch);
    return ['success' => $out !== false, 'error' => curl_error($ch)];
});

Route::get('/guzzle-test', function () {
    $client = new \GuzzleHttp\Client();
    $res = $client->get('https://www.google.com');
    return ['status' => $res->getStatusCode()];
});

Route::get('/guzzle-test2', function () {
    $handler = HandlerStack::create(new StreamHandler());

    $client = new Client([
        'handler' => $handler,
        'timeout' => 30,
        'verify' => true,
    ]);

    $res = $client->get('https://www.google.com');

    return [
        'status' => $res->getStatusCode(),
        'body' => substr($res->getBody()->getContents(), 0, 100),
    ];
});
Route::get('/fopen-test', function () {
    $fp = @fopen('https://www.google.com', 'r');
    if (!$fp) {
        return [
            'success' => false,
            'error' => error_get_last(),
        ];
    }
    return ['success' => true];
});

Route::get('/artisan_migrate', function () {
    // abort_unless(app()->environment('local'),403);
    // abort_unless(auth()->check() && auth()->user()->is_admin,403);
    Artisan::call('migrate', ['--force' => true]);
    Log::info('migrate command run successfully');
    return 'php artisan migrate command executed successfully.';
});
Route::get('/artisan_optimize', function () {
    // abort_unless(app()->environment('local'),403);
    // abort_unless(auth()->check() && auth()->user()->is_admin,403);
    Artisan::call('optimize:clear');
    Artisan::call('optimize');
    Log::info('optimize command run successfully');
    return 'php artisan optimize command executed successfully.';
});
Route::get('/artisan_storage_link', function () {
    // abort_unless(app()->environment('local'),403);
    // abort_unless(auth()->check() && auth()->user()->is_admin,403);
    Artisan::call('storage:link');
    Log::info('storage:link command run successfully');
    return 'php artisan storage:link command executed successfully.';
});

// For Checking Environments
Route::get('/test-env', function () {
    return [
        'environment' => app()->environment(),
        'is_local' => app()->environment('local'),
        'is_production' => app()->environment('production'),
    ];
});

// ========================= All Testing Routes End =========================
// ===========================================================================

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider and all of them will
 * | be assigned to the "web" middleware group. Make something great!
 * |
 */
Route::get('/', function () {
    return 'Server Running';
});

Route::get('/trigger-500', function () {
    abort(500);
});
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('seed-ins', [AutoSeedInstituteController::class, 'autoSend'])->name('seed-ins');
Route::get('system/statistics', [App\Http\Controllers\HomeController::class, 'stats'])->name('stats');
Route::get('login', [LoginController::class, 'login'])->name('login');
Route::get('login/callback', [LoginController::class, 'handleCallback'])->name('login.callback');

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::group(['middleware' => ['auth', 'share.sso']], function () {
    // Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    // Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home2', [App\Http\Controllers\HomeController::class, 'noipunnoDashboard'])->name('home2');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'noipunnoDashboard3'])->name('home');
    // Route::get('/', [App\Http\Controllers\HomeController::class, 'noipunnoDashboard2'])->name('home');
    Route::get('/noipunno-dashboard-style-components', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardComponents'])->name('noipunno.dashboard.components');
    Route::get('/noipunno-dashboard/upazilla', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardUpazilla'])->name('noipunno.dashboard.upazilla');
    Route::get('/noipunno-dashboard/school-details', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSchoolDetails'])->name('noipunno.dashboard.schoolDetails');
    Route::get('/noipunno-dashboard/school-focal', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSchoolFocal'])->name('noipunno.dashboard.SchoolFocal');
    // Route::get('/noipunno-dashboard/student-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardStudentAdd'])->name('noipunno.dashboard.student.add');
    // Route::get('/noipunno-dashboard/student-edit', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardStudentEdit'])->name('noipunno.dashboard.student.edit');

    // Route::get('/noipunno-dashboard/teacher-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardTeacherAdd'])->name('noipunno.dashboard.teacher.add');
    // Route::get('/noipunno-dashboard/teacher-edit', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardTeacherEdit'])->name('noipunno.dashboard.teacher.edit');
    Route::get('/institute_wise_branch', [App\Http\Controllers\HomeController::class, 'instituteWiseBranch'])->name('institute_wise_branch');
    Route::get('/branch_wise_version', [App\Http\Controllers\HomeController::class, 'branchWiseVersion'])->name('branch_wise_version');
    Route::get('/class_wise_subject', [App\Http\Controllers\HomeController::class, 'classWiseSubject'])->name('class_wise_subject');
    Route::get('/class_wise_section', [App\Http\Controllers\HomeController::class, 'classWiseSection'])->name('class_wise_section');
    Route::get('/section_wise_year', [App\Http\Controllers\HomeController::class, 'sectionWiseYear'])->name('section_wise_year');
    Route::get('/division_wise_district', [App\Http\Controllers\HomeController::class, 'divisionWiseDistrict'])->name('division_wise_district');
    Route::get('/district_wise_upazila', [App\Http\Controllers\HomeController::class, 'districtWiseUpazila'])->name('district_wise_upazila');
    Route::get('/upazila_wise_eiin_institute', [App\Http\Controllers\HomeController::class, 'upazilaWiseEiinInstitute'])->name('upazila_wise_eiin_institute');

    Route::get('/noipunno-dashboard/classroom-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardClassRoomAdd'])->name('noipunno.dashboard.classroom.add');
    Route::post('/noipunno-dashboard/classroom-store', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardClassRoomStore'])->name('noipunno.dashboard.classroom.store');
    Route::get('/noipunno-dashboard/classroom-edit/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardClassRoomEdit'])->name('noipunno.dashboard.classroom.edit');
    Route::post('/noipunno-dashboard/classroom-update/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardClassRoomUpdate'])->name('noipunno.dashboard.classroom.update');
    Route::post('/noipunno-dashboard/classroom-delete', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardClassRoomDelete'])->name('noipunno.dashboard.classroom.delete');

    Route::get('/noipunno-dashboard/branch-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardBranchAdd'])->name('noipunno.dashboard.branch.add');
    Route::post('/noipunno-dashboard/branch-store', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardBranchStore'])->name('noipunno.dashboard.branch.store');
    Route::get('/noipunno-dashboard/branch-edit/{id?}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardBranchEdit'])->name('noipunno.dashboard.branch.edit');
    Route::put('/noipunno-dashboard/branch-update/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardBranchUpdate'])->name('noipunno.dashboard.branch.update');
    Route::post('/noipunno-dashboard/branch-delete', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardBranchDelete'])->name('noipunno.dashboard.branch.delete');
    // Route::delete('/noipunno-dashboard/branch-delete/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardBranchDelete'])->name('noipunno.dashboard.branch.delete');

    Route::get('/noipunno-dashboard/shift-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardShiftAdd'])->name('noipunno.dashboard.shift.add');
    Route::post('/noipunno-dashboard/shift-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardShiftStore'])->name('noipunno.dashboard.shift.store');
    Route::get('/noipunno-dashboard/shift-edit/{id?}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardShiftEdit'])->name('noipunno.dashboard.shift.edit');
    Route::put('/noipunno-dashboard/shift-edit/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardShiftUpdate'])->name('noipunno.dashboard.shift.update');
    Route::post('/noipunno-dashboard/shift-delete', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardShiftDelete'])->name('noipunno.dashboard.shift.delete');
    // Route::delete('/noipunno-dashboard/shift-delete/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardShiftDelete'])->name('noipunno.dashboard.shift.delete');

    Route::get('/noipunno-dashboard/version-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardVersionAdd'])->name('noipunno.dashboard.version.add');
    Route::post('/noipunno-dashboard/version-store', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardVersionStore'])->name('noipunno.dashboard.version.store');
    Route::get('/noipunno-dashboard/version-edit/{id?}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardVersionEdit'])->name('noipunno.dashboard.version.edit');
    Route::put('/noipunno-dashboard/version-update/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardVersionUpdate'])->name('noipunno.dashboard.version.update');
    Route::post('/noipunno-dashboard/version-delete', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardVersionDelete'])->name('noipunno.dashboard.version.delete');
    // Route::delete('/noipunno-dashboard/version-delete/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardVersionDelete'])->name('noipunno.dashboard.version.delete');

    Route::get('/noipunno-dashboard/section-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSectionAdd'])->name('noipunno.dashboard.section.add');
    Route::post('/noipunno-dashboard/section-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSectionStore'])->name('noipunno.dashboard.section.store');
    Route::get('/noipunno-dashboard/section-edit', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSectionEdit'])->name('noipunno.dashboard.section.edit');
    Route::put('/noipunno-dashboard/section-edit', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSectionUpdate'])->name('noipunno.dashboard.section.update');
    Route::post('/noipunno-dashboard/section-delete', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSectionDelete'])->name('noipunno.dashboard.section.delete');
    // Route::delete('/noipunno-dashboard/section-delete/{id}', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSectionDelete'])->name('noipunno.dashboard.section.delete');

    Route::get('/noipunno-dashboard/session-add', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSessionAdd'])->name('noipunno.dashboard.session.add');
    Route::get('/noipunno-dashboard/session-edit', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardSessionEdit'])->name('noipunno.dashboard.session.edit');

    Route::get('/teachers', [App\Http\Controllers\Admin\TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/teachers/add', [App\Http\Controllers\Admin\TeacherController::class, 'add'])->name('teacher.add');
    // Route::get('/teachers/add-emis', [App\Http\Controllers\Admin\TeacherController::class, 'addEmis'])->name('teacher.add.emis');
    Route::post('/teachers', [App\Http\Controllers\Admin\TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/teachers/{id?}/edit', [App\Http\Controllers\Admin\TeacherController::class, 'edit'])->name('teacher.edit');
    Route::get('/teachers/{id?}/emis', [App\Http\Controllers\Admin\TeacherController::class, 'fromEmis'])->name('teacher.fromEmis');
    Route::get('/teachers/{id?}/banbies', [App\Http\Controllers\Admin\TeacherController::class, 'fromBanbies'])->name('teacher.fromBanbies');
    Route::put('/teachers/{id}', [App\Http\Controllers\Admin\TeacherController::class, 'update'])->name('teacher.update');
    Route::get('/teachers/get-teachers', [App\Http\Controllers\Admin\TeacherController::class, 'getAllTeachersByPdsID'])->name('teacher.getAllTeachersByPdsID');
    Route::post('/teachers/delete', [App\Http\Controllers\Admin\TeacherController::class, 'delete'])->name('teacher.delete');

    Route::get('/institutes', [App\Http\Controllers\Admin\InstituteController::class, 'index']);
    Route::post('/institutes', [App\Http\Controllers\Admin\InstituteController::class, 'store']);
    Route::get('/institutes/{id?}/edit', [App\Http\Controllers\Admin\InstituteController::class, 'edit'])->name('institute.edit');
    Route::put('/institutes/{id?}', [App\Http\Controllers\Admin\InstituteController::class, 'update'])->name('institute.update');

    Route::get('/students', [App\Http\Controllers\Admin\StudentController::class, 'index'])->name('student.index');
    Route::get('/students/add', [App\Http\Controllers\Admin\StudentController::class, 'add'])->name('student.add');
    Route::post('/students', [App\Http\Controllers\Admin\StudentController::class, 'store'])->name('student.store');
    Route::get('/students/{id}/edit', [App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('student.edit');
    Route::get('/students/{id}/edit-board-reg', [App\Http\Controllers\Admin\StudentController::class, 'edit_board_reg'])->name('student.edit_board_reg');
    Route::get('/students/{id}/print', [App\Http\Controllers\Admin\StudentController::class, 'print'])->name('student.print');
    Route::put('/students/{id}', [App\Http\Controllers\Admin\StudentController::class, 'update'])->name('student.update');
    Route::put('/students/{id}/update_reg', [App\Http\Controllers\Admin\StudentController::class, 'update_reg'])->name('student.update_reg');
    Route::get('/students/download', [App\Http\Controllers\Admin\StudentController::class, 'download'])->name('student.download');
    Route::post('/students-import', [App\Http\Controllers\Admin\StudentController::class, 'import'])->name('student.import');
    Route::post('/students-import/result', [App\Http\Controllers\Admin\StudentController::class, 'importResult'])->name('student.import.result');
    Route::get('/students-export/result/{id}', [App\Http\Controllers\Admin\StudentController::class, 'exportFailedData'])->name('student.export.data');
    Route::post('/remove-session-variable', [App\Http\Controllers\Admin\StudentController::class, 'removeSessionVariable']);
    Route::get('/student/get-branch-info', [App\Http\Controllers\Admin\StudentController::class, 'getAllRequiredDropdownForStudents'])->name('student.getBranchData');
    Route::get('/student/get-section-info', [App\Http\Controllers\Admin\StudentController::class, 'getSectionDropdownForStudents'])->name('student.getSectionData');
    Route::post('/students/delete', [App\Http\Controllers\Admin\StudentController::class, 'delete'])->name('student.delete');
    Route::post('/students/rec-status', [App\Http\Controllers\Admin\StudentController::class, 'studentRecStatus'])->name('student.rec_status');

    Route::get('/students/issue-transfer', [App\Http\Controllers\Admin\StudentTransferController::class, 'issueTransfer'])->name('student.issue.transfer');
    Route::get('/students/issue-transfer/list', [App\Http\Controllers\Admin\StudentTransferController::class, 'issueTransferList'])->name('student.issue.transfer.list');
    Route::get('/students/issue-transfer/add/{student_uid}', [App\Http\Controllers\Admin\StudentTransferController::class, 'issueTransferAdd'])->name('student.issue.transfer.add');
    Route::post('/students/issue-transfer/store', [App\Http\Controllers\Admin\StudentTransferController::class, 'issueTransferStore'])->name('student.issue.transfer.store');
    Route::get('/students/transfer-certificate/{student_uid}', [App\Http\Controllers\Admin\StudentTransferController::class, 'generateTransferCertificate'])->name('student.issue.transfer.certificate.generate');

    Route::get('/students/transfer-student/add', [App\Http\Controllers\Admin\StudentTransferController::class, 'TransferStudentAdd'])->name('student.transfer.add');

    Route::get('/students/promote', [App\Http\Controllers\Admin\StudentPromotionController::class, 'studentPromote'])->name('student.promote');
    Route::get('/students/promote/list', [App\Http\Controllers\Admin\StudentPromotionController::class, 'studentPromoteList'])->name('student.promote.list');
    Route::get('/students/promoted/list', [App\Http\Controllers\Admin\StudentPromotionController::class, 'studentPromotedList'])->name('student.promoted.list');
    Route::post('/students/promote/store', [App\Http\Controllers\Admin\StudentPromotionController::class, 'studentPromoteStore'])->name('student.promote.store');

    Route::get('/students/section-change', [App\Http\Controllers\Admin\StudentSectionChangeController::class, 'studentSectionChange'])->name('student.section_change');
    Route::get('/students/section-change/list', [App\Http\Controllers\Admin\StudentSectionChangeController::class, 'studentSectionChangeList'])->name('student.section_change.list');
    Route::get('/students/section-changed/list', [App\Http\Controllers\Admin\StudentSectionChangeController::class, 'studentSectionChangedList'])->name('student.section_changed.list');
    Route::post('/students/section-change/store', [App\Http\Controllers\Admin\StudentSectionChangeController::class, 'studentSectionChangeStore'])->name('student.section_change.store');

    Route::get('/students/attached-institute', [StudentAttachedInstituteController::class, 'studentAttachedInstitute'])->name('student.attached_institute');
    Route::get('/students/attached-institute/list', [StudentAttachedInstituteController::class, 'studentAttachedInstituteList'])->name('student.attached_institute.list');
    Route::get('/students/attached-institute-done/list', [StudentAttachedInstituteController::class, 'studentAttachedInstituteDoneList'])->name('student.attached_institute_d.list');
    Route::post('/students/attached-institute/store', [StudentAttachedInstituteController::class, 'studentAttachedInstituteStore'])->name('student.attached_institute.store');

    Route::get('/students/attached-institute-request/list', [StudentAttachedInstituteController::class, 'studentAttachedInstituteRequestList'])->name('student.attached_institute_request.list');
    Route::get('/students/attached-institute-request-student/list/{eiin}/{class}', [StudentAttachedInstituteController::class, 'studentAttachedInstituteRequestStudentList'])->name('student.attached_institute_request_student.list');
    Route::get('/students/attached-institute-requested-student/list', [StudentAttachedInstituteController::class, 'studentAttachedInstituteRequestedStudentList'])->name('student.attached_institute_requested_student.list');
    Route::post('/students/attached-institute-request/store', [StudentAttachedInstituteController::class, 'studentAttachedInstituteRequestStore'])->name('student.attached_institute_request.store');

    Route::prefix('students/board-registration')->group(function () {
        Route::get('/', [StudentBoardRegistrationController::class, 'studentBoardRegistrtion'])->name('student.board_registration');
        Route::get('/class/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionClass'])->name('student.board_registration.class');
        Route::get('/student/list', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionList'])->name('student.board_registration.list');
        Route::post('/payment/store', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionPaymentStore'])->name('student.board_registration.payment.store');
        Route::get('/temp/list/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionTempList'])->name('student.board_registration.temp.list');
        Route::get('/registered/list/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionRegisteredList'])->name('student.board_registration.registered.list');
        Route::post('/temp/store', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionTempStore'])->name('student.board_registration.temp.store');
        Route::post('/store', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionStore'])->name('student.board_registration.store');
        Route::get('/payment-redirect', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionPaymentRedirect'])->name('student.board_registration.payment_redirect');
        Route::get('/payment-list', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionPaymentList'])->name('student.board_registration.payment.list');
        Route::get('/payment-list-print', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionPaymentListPrint'])->name('student.board_registration.payment.list_print');
        Route::get('/challan-voucher/{payment_uid}', [StudentBoardRegistrationController::class, 'generateChallanVoucher'])->name('student.board_registration.challan.generate');

        Route::get('/payment-tab/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionPaymentTab'])->name('student.board_registration.payment_tab');
        Route::get('/student/list-tab/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionListTab'])->name('student.board_registration.list_tab');
        Route::get('/temp/list-tab/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionTempListTab'])->name('student.board_registration.temp.list_tab');
        Route::get('/temp/list-print/{class_id}/{temp}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionTempListPrint'])->name('student.board_registration.temp.list_print');
        Route::get('/registered/list-tab/{class_id}', [StudentBoardRegistrationController::class, 'studentBoardRegistrtionRegisteredListTab'])->name('student.board_registration.registered.list_tab');
    });

    Route::get('/students/report', [App\Http\Controllers\HomeController::class, 'noipunnoDashboardStudentsReport'])->name('students.report');

    Route::get('/upazila-list', [App\Http\Controllers\Admin\UpazillaController::class, 'index'])->name('upazila-index');
    Route::get('/upazila-edit/{id}', [App\Http\Controllers\Admin\UpazillaController::class, 'edit'])->name('upazila-edit');
    Route::post('/upazila-update/{id}', [App\Http\Controllers\Admin\UpazillaController::class, 'update'])->name('upazila-update');

    Route::post('/change-pi-bi-approve-status-subject-wise/{id}', [App\Http\Controllers\HomeController::class, 'changePiBiApproveStatusSubjectWise'])->name('change_pi_bi_approve_status_subject_wise');
    Route::post('/change-pi-bi-approve-status/{id}', [App\Http\Controllers\HomeController::class, 'changePiBiApproveStatus'])->name('change_pi_bi_approve_status');
    Route::get('/change-pi-bi-approve-all', [App\Http\Controllers\HomeController::class, 'changePiBiApproveAll'])->name('change_pibi_approve_all');

    Route::get('/otp-view', [App\Http\Controllers\Admin\PinResetController::class, 'otpPageView'])->name('otp_view');
    Route::get('/change-new-pin', [App\Http\Controllers\Admin\PinResetController::class, 'changeNewPin'])->name('change_new_pin');

    Route::get('get-exam-paper', [InstituteController::class, 'getExamPaper'])->name('getExamPaper');
    Route::get('/institutes/paper', [App\Http\Controllers\Admin\InstituteController::class, 'getExamPaper'])->name('institute.paper');
    Route::get('/resubmit-paper', [App\Http\Controllers\Admin\ResubmitPaperController::class, 'resubmitPaper'])->name('resubmit_paper');
});

Route::get('logs/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('logs/' . $folder . '/' . $filename);
    if (!File::exists($path)) {
        abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header('Content-Type', $type);
    return $response;
});

// Public certificate verification route - accessible without authentication
Route::get('verify/certificate/{certificateId}', [App\Http\Controllers\Api\CertificateController::class, 'show']);
