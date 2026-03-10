<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\SmsController;
use App\Http\Controllers\Api\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InstituteController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DivisionController;
use App\Http\Controllers\Api\UpazillaController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PiEvalutionController;
use App\Http\Controllers\Api\BiEvalutionController;
use App\Http\Controllers\Api\BiTranscriptController;
use App\Http\Controllers\Api\DashboardV2Controller;
use App\Http\Controllers\Api\PiBiReviewController;
use App\Http\Controllers\Api\TeacherV2Controller;
use App\Http\Controllers\Api\TranscriptController;
use App\Http\Controllers\Api\InstituteChartDataController;
use App\Http\Controllers\Api\StudentAttendanceController;
use App\Http\Controllers\Api\ResultConfigureController;
use App\Http\Controllers\Api\LibFineConfigureController;
use App\Http\Controllers\Api\InventoryCategoryController;
use App\Http\Controllers\Api\InventoryItemController;
use App\Http\Controllers\Api\InventoryProductController;
use App\Http\Controllers\Api\InventoryStoreController;
use App\Http\Controllers\Api\InventoryRackController;
use App\Http\Controllers\Api\InventoryShelvesController;
use App\Http\Controllers\Api\InventoryBoxController;
use App\Http\Controllers\Api\InventoryStoreInOutController;
use App\Http\Controllers\Api\InstituteCategoryController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\DashboardV3Controller;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\ClassWiseSubjectController;
use App\Http\Controllers\Api\VersionController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\ClassController;
use App\Http\Controllers\Api\ClassRoomController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\PiEvalutionV2Controller;
use App\Http\Controllers\Api\BiEvalutionV2Controller;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\StudentPromotionController;
use App\Http\Controllers\Api\StudentSectionChangeController;
use App\Http\Controllers\Api\StudentV2Controller;
use App\Http\Controllers\Api\TeacherV3Controller;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ExamConfigureController;
use App\Http\Controllers\Api\AttendanceConfigureController;
use App\Http\Controllers\Api\ClassTestController;
use App\Http\Controllers\Api\WeeklyTestController;
use App\Http\Controllers\Api\BiWeeklyTestController;
use App\Http\Controllers\Api\MonthlyTestController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\TermExamController;
use App\Http\Controllers\Api\FinalExamController;
use App\Http\Controllers\Api\MarkDistributionController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\CombineSubjectController;
use App\Http\Controllers\Api\CertificateController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/send-sms', [SmsController::class, 'send']);

Route::get('/app_version', function () {
    $app_version = DB::table('app_versions')->first();
    return response()->json(['status' => true, 'data' => $app_version], 200);
});
Route::get('v2/maintenance', function () {
    $app_version = DB::table('app_versions')->find(2);
    return response()->json([
        'status' => true,
        'is_maintenance' => intval($app_version->version),
        'message' => 'নিরাপত্তার স্বার্থে মূল্যায়ন সংক্রান্ত সকল সেবা পরবর্তীতে নির্দেশনা না দেওয়া পর্যন্ত বিদ্যাপীঠ প্লাটফর্ম কর্তৃপক্ষের নির্দেশক্রমে সাময়িকভাবে বন্ধ থাকবে।'
        // 'message' => 'প্রতিষ্ঠান প্রধানের আইডি থেকে ষান্মাসিক সামষ্টিক মূল্যায়নের প্রশ্নপত্র ও নির্দেশনা সহজে ডাউনলোড নিশ্চিত করার সুবিধার্থে বিকাল ৬ টা থেকে মূল্যায়নের দিন সকাল ১০ টা পর্যন্ত সকল ধরনের মূল্যায়ন কার্যক্রম (ওয়েব ও মোবাইল অ্যাাপ) বন্ধ থাকবে। আপনাদের সহযোগিতার জন্য ধন্যবাদ।

        // ১। আগামী ৩ জুলাই পরবর্তি সময়েও শিখনকালীন মূল্যায়ন চলমান থাকবে
        // ২। ষান্মাসিক সামস্টিক মূল্যায়নের পূর্বেই শিখনকালীন মূল্যায়ন শেষ করতে হবে
        // ৩। অন্যথায় অ্যাপ্সের মাধ্যমে শিক্ষার্থীদের বিষয়ভিত্তিক ট্রান্সক্রিপ্ট তৈরী করা সম্ভভ হবে না।'
    ], 200);
});
Route::get('v2/notice', function () {
    return response()->json([
        'status' => true,
        'message' => 'সকলের অবগতির জন্য জানানো যাচ্ছে যে, ২০২৪ সালের শিক্ষাবর্ষের কার্যক্রম চালু করার লক্ষ্যে বিদ্যাপীঠ এর সকল কার্যক্রম সাময়িকভাবে বন্ধ থাকবে। সবার সহযোগিতার জন্য ধন্যবাদ।'
    ], 200);
});

// Route::get('/institute', [InstituteController::class, 'index']);
// Route::post('/institute', [InstituteController::class, 'store']);

// Route::get('/teacher', [TeacherController::class, 'index']);
// Route::post('/teacher', [TeacherController::class, 'store']);

// Route::get('/student', [StudentController::class, 'index']);
// Route::post('/student', [StudentController::class, 'store']);
Route::get('/division', [DivisionController::class, 'index']);
Route::get('/district', [DistrictController::class, 'index']);
Route::get('/upazilla', [UpazillaController::class, 'index']);
Route::get('/designation', [TeacherV2Controller::class, 'designationList']);

Route::resource('institutes-categories', InstituteCategoryController::class);
Route::get('board-by-id/{id}', [BoardController::class, 'boardById']);
Route::resource('boards', BoardController::class);

Route::post('/spg/DataUpdate', [PaymentController::class, 'spgDataUpdate']);

// Route::group(['middleware' => ['api.share.sso']], routes: function () {

    // Route::group(['middleware' => ['json.response', 'api.share.sso']], function () {
    Route::post('/head-teacher', [InstituteController::class, 'storeInstituteHeadMaster']);
    Route::get('/teacher-dashboard', [DashboardController::class, 'teacherDashboard']);
    Route::get('/own-subjects', [TeacherController::class, 'OwnSubject']);
    Route::get('/pi-bi-evaluation-list', [TeacherController::class, 'PiBiEvaluationList']);
    Route::get('/transcript', [TranscriptController::class, 'transcript']);
    Route::get('/transcript-by-student', [TranscriptController::class, 'transcriptByStudent']);
    Route::get('/report-card', [TranscriptController::class, 'reportCard']);
    Route::get('/report-card-by-student-single-subject', [TranscriptController::class, 'reportCardByStudentSingleSubject']);
    Route::get('/report-card-by-student', [TranscriptController::class, 'reportCardByStudent']);
    Route::get('/bi-report-card-by-student', [TranscriptController::class, 'reportCardByStudentBi']);

    Route::post('/division', [DivisionController::class, 'store']);
    Route::put('/division/{id}', [DivisionController::class, 'update']);

    Route::post('/district', [DistrictController::class, 'store']);
    Route::put('/district/{id}', [DistrictController::class, 'update']);

    Route::post('/upazilla', [UpazillaController::class, 'store']);
    Route::put('/upazilla/{id}', [UpazillaController::class, 'update']);

    Route::post('/pi-evaluation', [PiEvalutionController::class, 'store']);
    Route::post('/bi-evaluation', [BiEvalutionController::class, 'store']);

    Route::get('/get-pi-evaluation-by-pi', [PiEvalutionController::class, 'getPiEvaluationByPi']);
    Route::get('/get-bi-evaluation-by-bi', [BiEvalutionController::class, 'getBiEvaluationByBi']);

    Route::get('/upazila-institute-headmaster/{uzid}', [InstituteController::class, 'upazilaInstituteWithHeadMaster']);
    Route::get('/institute-teacher', [InstituteController::class, 'upazilaTeachers']);
    Route::post('/institute-headmaster', [InstituteController::class, 'updateInstituteHeadMaster']);

    Route::get('/institute', [InstituteController::class, 'index']);
    Route::post('/institute', [InstituteController::class, 'store']);

    Route::get('/teacher', [TeacherController::class, 'index']);
    Route::post('/teacher', [TeacherController::class, 'store']);
    // Route::put('/teachers/{id}', [TeacherController::class, 'update']);
    Route::put('/teachers/{id}', [App\Http\Controllers\Admin\TeacherController::class, 'update']);

    Route::get('/student', [StudentController::class, 'index']);
    Route::post('/student', [StudentController::class, 'store']);

    Route::group(['prefix' => 'v2'], function () {
        Route::get('/division', [DivisionController::class, 'index']);
        Route::get('/district', [DistrictController::class, 'index']);
        Route::get('/upazilla', [UpazillaController::class, 'index']);

        Route::get('/teacher-dashboard', [DashboardV2Controller::class, 'teacherDashboard']);
        Route::get('/class-room-info', [TeacherV2Controller::class, 'classRoomInfo']);
        Route::get('/class-room-information', [TeacherV2Controller::class, 'classRoomInformation']);
        Route::get('/own-subjects', [TeacherV2Controller::class, 'ownSubject']);
        Route::get('/get-bi', [TeacherV2Controller::class, 'getBi']);
        Route::get('/get-common-info', [TeacherV2Controller::class, 'getCommonInfo']);
        Route::get('/pi-bi-evaluation-list', [TeacherV2Controller::class, 'PiBiEvaluationList']);
        Route::post('/teachers/{id}', [TeacherV2Controller::class, 'update']);

        Route::get('/class-students', [TeacherV2Controller::class, 'classStudents']);

        Route::post('/pi-evaluation', [PiEvalutionController::class, 'store']);
        Route::post('/bi-evaluation', [BiEvalutionController::class, 'store']);

        Route::get('/get-pi-evaluation-by-pi', [PiEvalutionController::class, 'getPiEvaluationByPi']);
        Route::get('/get-bi-evaluation-by-bi', [BiEvalutionController::class, 'getBiEvaluationByBi']);

        Route::get('/transcript', [TranscriptController::class, 'transcript']);
        Route::get('/transcript-by-student', [TranscriptController::class, 'transcriptByStudent']);
        Route::get('/report-card', [TranscriptController::class, 'reportCard']);
        Route::get('/report-card-by-student-single-subject', [TranscriptController::class, 'reportCardByStudentSingleSubject']);
        Route::get('/report-card-by-student', [TranscriptController::class, 'reportCardByStudent']);
        Route::get('/bi-transcript-by-student', [BiTranscriptController::class, 'biTranscriptByStudent']);
        Route::get('/bi-report-card-by-student', [BiTranscriptController::class, 'biReportCardByStudent']);

        Route::put('/teachers/{id}', [App\Http\Controllers\Admin\TeacherController::class, 'update']);
        Route::get('/transcript-by-student-pdf', [TranscriptController::class, 'transcriptByStudentPdf']);
        Route::get('/bi-transcript-by-student-pdf', [BiTranscriptController::class, 'biTranscriptByStudentPdf']);

        Route::post('/pi-review', [PiEvalutionController::class, 'storePiReview']);
        Route::post('/bi-review', [BiEvalutionController::class, 'storeBiReview']);

        Route::post('/pi-bi-review', [PiBiReviewController::class, 'storePiBiReview']);

        Route::post('/store-attendance', [StudentAttendanceController::class, 'storeAttendance']);
        Route::get('/get-attendance', [StudentAttendanceController::class, 'getAttendance']);

        Route::get('/get-institute-data', [InstituteChartDataController::class, 'getInstituteData']);
        Route::get('/get-student-data', [InstituteChartDataController::class, 'getStudentData']);
        Route::get('/get-institute-teacher', [InstituteChartDataController::class, 'getBoardWiseInstituteTeacher']);
        Route::match(['get', 'post'], '/get-teacher-subjectwise', [InstituteChartDataController::class, 'getTeacherSubjectWise']);

        Route::get('/board-list', [InstituteChartDataController::class, 'getBoards']);
        Route::get('/division-by-districts', [InstituteChartDataController::class, 'divisionByDistricts']);
        Route::get('/district-by-upazilas', [InstituteChartDataController::class, 'districtByUpazilas']);
    });

    Route::group(['prefix' => 'v3'], function () {
        // Common Api List
        Route::get('/user-details', [DashboardV2Controller::class, 'userDetails']);

        Route::get('/class-list', [DashboardV2Controller::class, 'classList']);
        Route::get('/board', [BoardController::class, 'index']);
        Route::get('/division', [DivisionController::class, 'index']);
        Route::get('/division/{id}', [DivisionController::class, 'getById']);
        Route::get('/district', [DistrictController::class, 'index']);
        Route::get('/district/{id}', [DistrictController::class, 'getById']);
        Route::get('/division_wise_district', [DistrictController::class, 'divisionWiseDistrict']);
        Route::get('/upazila', [UpazillaController::class, 'index']);
        Route::get('/upazila/{id}', [UpazillaController::class, 'getById']);
        
        Route::get('/district_wise_upazila', [UpazillaController::class, 'districtWiseUpazila']);
        Route::get('/designation', [TeacherV3Controller::class, 'designationList']);
        
        Route::get('/sync-division', [DivisionController::class, 'syncDivisionData']);

        // Dashboard Api List
        Route::get('/dashboard-summary', [DashboardV2Controller::class, 'dashboardSummary']);
        Route::get('/dashboard-statistics', [DashboardV2Controller::class, 'dashboardStatistics']);
        Route::get('/pi-bi-review-list', [DashboardV2Controller::class, 'piBiReviewList']);
        Route::post('/change-pi-bi-approve-status/{id}', [DashboardV2Controller::class, 'changePiBiApproveStatus']);
        Route::post('/change-pi-bi-approve-status-subject-wise/{id}', [DashboardV2Controller::class, 'changePiBiApproveStatusSubjectWise']);

        // Institute Api List
        Route::get('/institute-by-eiin/{eiin}', [InstituteController::class, 'getByEiin']);
        Route::post('/institute-info/update', [InstituteController::class, 'updateInstitute']);

        // Branch Api List
        Route::get('/branch-list', [BranchController::class, 'index']);
        Route::get('/branch/{id}', [BranchController::class, 'getById']);
        Route::post('/branch-store', [BranchController::class, 'store']);
        Route::post('/branch-update', [BranchController::class, 'update']);
        Route::delete('/branch-delete/{id}', [BranchController::class, 'destroy']);

        // Shift Api List
        Route::get('/shift-list', [ShiftController::class, 'index']);
        Route::get('/shift/{id}', [ShiftController::class, 'getById']);
        Route::post('/shift-store', [ShiftController::class, 'store']);
        Route::post('/shift-update', [ShiftController::class, 'update']);
        Route::delete('/shift-delete/{id}', [ShiftController::class, 'destroy']);
        Route::post('/branch_wise_shift', [ShiftController::class, 'branchWiseShift']);

        // Version Api List
        Route::get('/version-list', [VersionController::class, 'index']);
        Route::get('/version/{id}', [VersionController::class, 'getById']);
        Route::post('/version-store', [VersionController::class, 'store']);
        Route::post('/version-update', [VersionController::class, 'update']);
        Route::delete('/version-delete/{id}', [VersionController::class, 'destroy']);
        Route::post('/branch_wise_version', [VersionController::class, 'branchWiseVersion']);

        // Session Api List
        Route::get('/session-list', [SessionController::class, 'index']);
        Route::get('/session/{id}', [SessionController::class, 'getById']);
        Route::post('/session-store', [SessionController::class, 'store']);
        Route::post('/session-update', [SessionController::class, 'update']);
        Route::delete('/session-delete/{id}', [SessionController::class, 'destroy']);

        // Class Api List
        Route::get('/class-list', [ClassController::class, 'index']);
        Route::get('/class/{id}', [ClassController::class, 'getById']);
        Route::post('/class-store', [ClassController::class, 'store']);
        Route::post('/class-update', [ClassController::class, 'update']);
        Route::delete('/class-delete/{id}', [ClassController::class, 'destroy']);

        // Section Api List
        Route::get('/section-list', [SectionController::class, 'index']);
        Route::get('/section/{id}', [SectionController::class, 'getById']);
        Route::post('/section-store', [SectionController::class, 'store']);
        Route::post('/section-update', [SectionController::class, 'update']);
        Route::delete('/section-delete/{id}', [SectionController::class, 'destroy']);
        Route::post('/class_wise_section', [SectionController::class, 'classWiseSection']);

        // group Api List
        Route::get('/group-list', [GroupController::class, 'index']);
        Route::get('/group/{id}', [GroupController::class, 'getById']);
        Route::post('/group-store', [GroupController::class, 'store']);
        Route::post('/group-update', [GroupController::class, 'update']);
        Route::delete('/group-delete/{id}', [GroupController::class, 'destroy']);
        
        // subject Api List
        Route::get('/subject-list', [SubjectController::class, 'index']);
        Route::get('/subject/{id}', [SubjectController::class, 'getById']);
        Route::post('/subject-store', [SubjectController::class, 'store']);
        Route::post('/subject-update', [SubjectController::class, 'update']);
        Route::delete('/subject-delete/{id}', [SubjectController::class, 'destroy']);
        
        //Combine subject Api List
        Route::get('/combine-subject-list', [CombineSubjectController::class, 'index']);
        Route::post('/combine-subject-store', [CombineSubjectController::class, 'store']);
        Route::post('/combine-subject-update', [CombineSubjectController::class, 'update']);
        Route::delete('/combine-subject-delete/{id}', [CombineSubjectController::class, 'destroy']);

        // Class-wise-subject Api List
        Route::get('/class-wise-subject-list', [ClassWiseSubjectController::class, 'index']);
        Route::get('/class-wise-subject/{id}', [ClassWiseSubjectController::class, 'getById']);
        Route::post('/class-wise-subject-store', [ClassWiseSubjectController::class, 'store']);
        Route::post('/class-wise-subject-update', [ClassWiseSubjectController::class, 'update']);
        Route::delete('/class-wise-subject-delete/{class_id}/{session_id}', [ClassWiseSubjectController::class, 'destroy']);

        // Classroom Api List
        Route::get('/classroom-list', [ClassRoomController::class, 'index']);
        Route::get('/classroom/{id}', [ClassRoomController::class, 'getById']);
        Route::post('/classroom-store', [ClassRoomController::class, 'store']);
        Route::post('/classroom-update', [ClassRoomController::class, 'update']);
        Route::delete('/classroom-delete/{id}', [ClassRoomController::class, 'destroy']);
        Route::get('/class_wise_subject', [ClassRoomController::class, 'classWiseSubject']);

        // Teacher Api List
        Route::get('/teacher-list', [TeacherV3Controller::class, 'index']);
        Route::get('/teacher/{id}', [TeacherV3Controller::class, 'getById']);
        Route::post('/teacher-get-by-caid', [TeacherV3Controller::class, 'getByCaId']);
        Route::post('/teacher-store', [TeacherV3Controller::class, 'store']);
        Route::post('/teacher-update', [TeacherV3Controller::class, 'update']);
        Route::delete('/teacher-delete/{id}', [TeacherV3Controller::class, 'destroy']);
        Route::post('/teacher-exists-check', [TeacherV3Controller::class, 'teacherExistsCheckByPdsidOrIndex']);
        Route::post('/teacher-details', [TeacherV3Controller::class, 'teacherDetails']);
        Route::post('/bulk-teacher-sms-send', [TeacherV3Controller::class, 'bulkTeacherSmsSend']);
        
        
        // Staff Api List
        Route::get('/staff-list', [StaffController::class, 'index']);
        Route::get('/staff/{id}', [StaffController::class, 'getById']);
        Route::post('/staff-store', [StaffController::class, 'store']);
        Route::post('/staff-update', [StaffController::class, 'update']);
        Route::delete('/staff-delete/{id}', [StaffController::class, 'destroy']);
        Route::post('/bulk-staff-sms-send', [StaffController::class, 'bulkStaffSmsSend']);

        // Student Api List
        Route::get('/get-all-student', [StudentV2Controller::class, 'getAllStudent']);
        Route::get('/student-list', [StudentV2Controller::class, 'index']);
        Route::get('/student-pginate-list', [StudentV2Controller::class, 'pginateIndex']);
        Route::get('/student/{id}', [StudentV2Controller::class, 'getById']);
        Route::post('/student-store', [StudentV2Controller::class, 'store']);
        Route::post('/student-quick-reg', [StudentV2Controller::class, 'studentQuickReg']);
        Route::post('/student-excel-upload', [StudentV2Controller::class, 'studentExcelUpload']);
        Route::post('/student-update', [StudentV2Controller::class, 'update']);
        Route::delete('/student-delete/{id}', [StudentV2Controller::class, 'destroy']);
        Route::post('/student-status-change', [StudentV2Controller::class, 'studentChangeStatus']);
        Route::post('/class-wise-student', [StudentV2Controller::class, 'classWiseStudent']);
        Route::post('/section-wise-student', [StudentV2Controller::class, 'sectionWiseStudent']);
        Route::post('/section-wise-student-list', [StudentV2Controller::class, 'sectionWiseStudentList']);
        Route::post('/bulk-student-sms-send', [StudentV2Controller::class, 'bulkStudentsSmsSend']);

        // Student Promotion Api List
        Route::get('/students/promote/list', [StudentPromotionController::class, 'studentPromoteList']);
        Route::post('/students/promote/store', [StudentPromotionController::class, 'studentPromoteStore']);

        // Student Section Change Api List
        Route::get('/students/section-change/list', [StudentSectionChangeController::class, 'studentSectionChangeList']);
        Route::post('/students/section-change/store', [StudentSectionChangeController::class, 'studentSectionChangeStore']);

        // Evaluation Api List
        Route::post('/class-teacher-check', [EvaluationController::class, 'classTeacherCheck']);
        Route::post('/teacher-subject-list', [EvaluationController::class, 'teacherSubjectList']);
        Route::get('/oviggota-pi-list', [EvaluationController::class, 'oviggotaPiList']);
        Route::get('/bi-list', [EvaluationController::class, 'biList']);
        Route::post('/pi-selection-list', [EvaluationController::class, 'piSelectionList']);

        // Pi Evaluation Api List
        Route::post('/pi-evaluation', [PiEvalutionV2Controller::class, 'store']);
        Route::get('/get-pi-evaluation-by-pi', [PiEvalutionV2Controller::class, 'getPiEvaluationByPi']);

        // Pi Review Api List
        Route::post('/pi-review', [PiEvalutionV2Controller::class, 'storePiReview']);

        // Bi Evaluation Api List
        Route::post('/bi-evaluation', [BiEvalutionV2Controller::class, 'store']);
        Route::get('/get-bi-evaluation-by-bi', [BiEvalutionV2Controller::class, 'getBiEvaluationByBi']);

        // Bi Review Api List
        Route::post('/bi-review', [BiEvalutionV2Controller::class, 'storeBiReview']);

        //End api for classroom management
        Route::post('/institute/is-exists',                 [InstituteController::class, 'is_exists']);
        Route::get('boards',                                [InstituteController::class, 'boards']);
        Route::get('get-board-by-district-id/{district_id}', [InstituteController::class, 'getBoardByDistrictId']);
        Route::post('/head-teacher/store',                  [InstituteController::class, 'storeInstituteHeadMaster']);
        Route::post('/institute',                           [InstituteController::class, 'store']);
        Route::get('/institute',                            [InstituteController::class, 'index']);
        Route::get('/institute/{uid}',                      [InstituteController::class, 'getById']);
        Route::post('/institute/update',                    [InstituteController::class, 'update']);
        Route::post('/institute-headmaster',                [InstituteController::class, 'updateInstituteHeadMaster']);
        Route::post('/institute/search-teacher-by-pdsid',   [TeacherController::class,   'searchTeacherByPDSID']);
        Route::get('/upazilla/{id}/total-teachers',         [TeacherController::class,   'upazillaTotalTeachers']);
        Route::get('/upazilla/{id}/total-institutes',       [InstituteController::class, 'upazillaTotalInstitutes']);
        Route::get('/upazilla/{id}/total-students',         [StudentController::class,   'upazillaTotalStudents']);
        Route::get('/upazilla/{id}/total-sections',         [InstituteController::class, 'upazillaTotalSections']);

        //Api for foreign Institute
        Route::get('/foreign/total-institutes',       [InstituteController::class, 'foreignTotalInstitutes']);
        Route::get('/foreign/total-teachers',         [TeacherController::class,   'foreignTotalTeachers']);
        Route::get('/foreign/total-students',         [StudentController::class,   'foreignTotalStudents']);
        //End api for Foreign Institute


        //Api for upazilla
        Route::get('/upazilla/{id}/categoryWiseInstitute',  [DashboardV3Controller::class,   'categoryWiseInstitute']);
        Route::get('/upazilla/{id}/classWiseStudent',       [DashboardV3Controller::class,   'classWiseStudent']);
        Route::get('/upazilla/{id}/subjectWiseTeacher',     [DashboardV3Controller::class,   'subjectWiseTeacher']);
        Route::get('/countries',                            [CountryController::class,       'countryList']);
        //Api for upazilla

        //Api for Foreign Institute
        Route::get('/foreign/category-wise-institute',      [DashboardV3Controller::class,   'foreignCategoryWiseInstitute']);
        Route::get('/foreign/class-wise-student',           [DashboardV3Controller::class,   'foreignClassWiseStudent']);
        Route::get('/foreign/subject-wise-teacher',         [DashboardV3Controller::class,   'foreignSubjectWiseTeacher']);

        //Api for upazilla

        //Teacher list api
        Route::post('/teachers/list',           [TeacherController::class, 'teacherList']);
        Route::post('/student/list',            [StudentController::class, 'index']);
        Route::get('/institute/details/{eiin}', [InstituteController::class, 'getByIdWithDetails']);

        // Exam Configure api
        Route::get('/exam-configure', [ExamConfigureController::class, 'index']);
        Route::get('/exam-configure/{id}', [ExamConfigureController::class, 'getById']);
        Route::post('/exam-configure-store', [ExamConfigureController::class, 'store']);
        Route::post('/exam-configure-update', [ExamConfigureController::class, 'update']);
        Route::delete('/exam-configure-delete/{id}', [ExamConfigureController::class, 'destroy']);
        Route::post('/category-wise-exam', [ExamConfigureController::class, 'categoryWiseExam']);
       
        // Attendance api
        Route::get('attendance-configure', [AttendanceConfigureController::class, 'index']);
        Route::get('/attendance-configure/{id}', [AttendanceConfigureController::class, 'getById']);
        Route::post('/attendance-configure-store', [AttendanceConfigureController::class, 'store']);
        Route::post('/attendance-configure-update', [AttendanceConfigureController::class, 'update']);
        Route::delete('/attendance-configure-delete/{id}', [AttendanceConfigureController::class, 'destroy']);
        Route::post('/get-att-config-data', [AttendanceConfigureController::class, 'getAttConfigData']);

        // Exam Mark Distribution api
        Route::get('/mark-distribution-list', [MarkDistributionController::class, 'index']);
        Route::get('/mark-distribution/{id}', [MarkDistributionController::class, 'getById']);
        Route::post('/mark-distribution-store', [MarkDistributionController::class, 'store']);
        Route::post('/mark-distribution-update', [MarkDistributionController::class, 'update']);
        Route::delete('/mark-distribution-delete/{id}', [MarkDistributionController::class, 'destroy']);
        Route::post('/mark-distribution-edit-mode-on', [MarkDistributionController::class, 'markDistributionEditModeOn']);

        //attendance api
        Route::get('/attendance-list', [AttendanceController::class, 'index']);
        Route::get('/attendance/{id}', [AttendanceController::class, 'getById']);
        Route::post('/attendance-store', [AttendanceController::class, 'store']);
        Route::post('/attendance-store-ai', [AttendanceController::class, 'storeByAi'])->withoutMiddleware(['api.share.sso']);
        Route::post('/attendance-store-machine', [AttendanceController::class, 'storeByFingerPrint'])->withoutMiddleware(['api.share.sso']);
        Route::post('/attendance-update', [AttendanceController::class, 'update']);
        Route::delete('/attendance-delete/{id}', [AttendanceController::class, 'destroy']);
        Route::post('/student-wise-attendance', [AttendanceController::class, 'studentWiseAttendance']);
        Route::post('/section-wise-monthly-attendance', [AttendanceController::class, 'sectionWiseMonthlyAttendance']);

        // Student Dashboard Attendance API 
        Route::post('/student/dashboard/attendance', [AttendanceController::class, 'getMyAttendance']);
        Route::post('/student/dashboard/attendance/bydate', [AttendanceController::class, 'getAttendanceByDate']);

      
        //result-configure api
        Route::get('/result-configure-list', [ResultConfigureController::class, 'index']);
        Route::get('/result-configure/{id}', [ResultConfigureController::class, 'getById']);
        Route::post('/result-configure-store', [ResultConfigureController::class, 'store']);
        Route::post('/result-configure-update', [ResultConfigureController::class, 'update']);
        Route::delete('/result-configure-delete/{id}', [ResultConfigureController::class, 'destroy']);
        Route::post('/subject-wise-result-configure', [ResultConfigureController::class, 'subjectWiseResultConfigure']);

        Route::post('/generate-subject-wise-result', [ResultConfigureController::class, 'generateSubjectWiseResult']);
        Route::post('/subject-wise-result-store', [ResultConfigureController::class, 'subjectWiseResultStore']);
        Route::post('/tabulation-sheet', [ResultConfigureController::class, 'tabulationSheet']);
        Route::post('/section-wise-tabulation-sheet', [ResultConfigureController::class, 'sectionWiseTabulationSheet']);
        Route::get('/sync-mark-dristibution-data', [ResultConfigureController::class, 'syncMarkDistribution']);


        //inventory category api
        Route::get('/inventory-category-list', [InventoryCategoryController::class, 'index']);
        Route::get('/inventory-category/{id}', [InventoryCategoryController::class, 'getById']);
        Route::post('/inventory-category-store', [InventoryCategoryController::class, 'store']);
        Route::post('/inventory-category-update', [InventoryCategoryController::class, 'update']);
        Route::delete('/inventory-category-delete/{id}', [InventoryCategoryController::class, 'destroy']);
     
        //inventory item api
        Route::get('/inventory-item-list', [InventoryItemController::class, 'index']);
        Route::get('/inventory-item/{id}', [InventoryItemController::class, 'getById']);
        Route::post('/inventory-item-store', [InventoryItemController::class, 'store']);
        Route::post('/inventory-item-update', [InventoryItemController::class, 'update']);
        Route::delete('/inventory-item-delete/{id}', [InventoryItemController::class, 'destroy']);
        
        //inventory product api
        Route::get('/inventory-product-list', [InventoryProductController::class, 'index']);
        Route::get('/inventory-product/{id}', [InventoryProductController::class, 'getById']);
        Route::post('/inventory-product-store', [InventoryProductController::class, 'store']);
        Route::post('/inventory-product-update', [InventoryProductController::class, 'update']);
        Route::delete('/inventory-product-delete/{id}', [InventoryProductController::class, 'destroy']);
        Route::get('/inventory-product-general-list', [InventoryProductController::class, 'generalIndex']);
        Route::get('/inventory-product-library-list', [InventoryProductController::class, 'libraryIndex']);
     
        //inventory store api
        Route::get('/inventory-store-list', [InventoryStoreController::class, 'index']);
        Route::get('/inventory-store/{id}', [InventoryStoreController::class, 'getById']);
        Route::post('/inventory-store-store', [InventoryStoreController::class, 'store']);
        Route::post('/inventory-store-update', [InventoryStoreController::class, 'update']);
        Route::delete('/inventory-store-delete/{id}', [InventoryStoreController::class, 'destroy']);
             
        //inventory rack api
        Route::get('/inventory-rack-list', [InventoryRackController::class, 'index']);
        Route::get('/inventory-rack/{id}', [InventoryRackController::class, 'getById']);
        Route::post('/inventory-rack-store', [InventoryRackController::class, 'store']);
        Route::post('/inventory-rack-update', [InventoryRackController::class, 'update']);
        Route::delete('/inventory-rack-delete/{id}', [InventoryRackController::class, 'destroy']);
             
        //inventory shelves api
        Route::get('/inventory-shelves-list', [InventoryShelvesController::class, 'index']);
        Route::get('/inventory-shelves/{id}', [InventoryShelvesController::class, 'getById']);
        Route::post('/inventory-shelves-store', [InventoryShelvesController::class, 'store']);
        Route::post('/inventory-shelves-update', [InventoryShelvesController::class, 'update']);
        Route::delete('/inventory-shelves-delete/{id}', [InventoryShelvesController::class, 'destroy']);
             
        //inventory box api
        Route::get('/inventory-box-list', [InventoryBoxController::class, 'index']);
        Route::get('/inventory-box/{id}', [InventoryBoxController::class, 'getById']);
        Route::post('/inventory-box-store', [InventoryBoxController::class, 'store']);
        Route::post('/inventory-box-update', [InventoryBoxController::class, 'update']);
        Route::delete('/inventory-box-delete/{id}', [InventoryBoxController::class, 'destroy']);
        

        //lib-fine-configure api
        Route::get('/lib-fine-configure-list', [LibFineConfigureController::class, 'index']);
        Route::get('/lib-fine-configure/{id}', [LibFineConfigureController::class, 'getById']);
        Route::post('/lib-fine-configure-store', [LibFineConfigureController::class, 'store']);
        Route::post('/lib-fine-configure-update', [LibFineConfigureController::class, 'update']);
        Route::delete('/lib-fine-configure-delete/{id}', [LibFineConfigureController::class, 'destroy']);
    
        
        //Store In/Out Api
        Route::get('/inventory-store-in-list', [InventoryStoreInOutController::class, 'storeInList']);
        Route::post('/inventory-store-in', [InventoryStoreInOutController::class, 'storeIn']);

        Route::get('/inventory-store-out-list', [InventoryStoreInOutController::class, 'storeOutList']);
        Route::post('/inventory-store-out', [InventoryStoreInOutController::class, 'storeOut']);

        Route::post('/inventory-store-return', [InventoryStoreInOutController::class, 'storeReturn']);
        Route::post('/student-library-fine', [InventoryStoreInOutController::class, 'studentLibraryFine']);
        Route::post('/student-library-fine-paid', [InventoryStoreInOutController::class, 'studentLibraryFinePaid']);

        // Certificate routes - Only the 3 essential endpoints needed
        Route::get('certificates/search', [CertificateController::class, 'search']);
        Route::post('certificates', [CertificateController::class, 'store']);
        require __DIR__ . '/api_attendance.php';
    });
// });


