<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Upazilla;
use App\Services\StaffService;
use App\Services\StudentService;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    protected $teacherService;
    protected $staffService;
    protected $studentService;

    public function __construct(
        TeacherService $teacherService,
        StaffService $staffService,
        StudentService $studentService
    ) {
        $this->teacherService = $teacherService;
        $this->staffService = $staffService;
        $this->studentService = $studentService;
    }

    /**
     * Filter employees (teachers, staff, students) by location filters.
     */
    public function filterEmployees(Request $request)
    {
        Log::info('xyz');
        Log::info($request->all());
        try {
            // Manual validation to be more flexible with types
            $personType = $request->input('person_type', []);
            $profileIds = $request->input('profile_ids', []);

            // Validate person_type
            if (empty($personType) || !is_array($personType)) {
                return response()->json([
                    'status' => false,
                    'message' => 'person_type is required and must be an array.',
                    'data' => [],
                ], 422);
            }

            foreach ($personType as $type) {
                if (!in_array($type, [1, 2, 3])) {
                    return response()->json([
                        'status' => false,
                        'message' => 'person_type must contain only 1, 2, or 3.',
                        'data' => [],
                    ], 422);
                }
            }

            // Validate profile_ids if provided
            if (!empty($profileIds) && !is_array($profileIds)) {
                return response()->json([
                    'status' => false,
                    'message' => 'profile_ids must be an array.',
                    'data' => [],
                ], 422);
            }

            $allProfiles = [];

            // Convert profile IDs to strings for proper comparison with emp_id/student_unique_id
            $requestedProfileIds = array_map('strval', $profileIds);

            Log::info('Validated data:', [
                'person_type' => $personType,
                'profile_ids' => $requestedProfileIds
            ]);

            foreach ($personType as $type) {
                $profiles = [];

                if ($type == 3) {
                    if ($request->has('branch_uid') && !empty($request->input('shift_uid')) && $request->has('class_uid') && $request->has('section_uid')) {
                        Log::info('p222');

                        $branchUid = (string)$request->input('branch_uid');
                        $shiftUid = (string)$request->input('shift_uid');
                        $classUid = (string)$request->input('class_uid');
                        $sectionUid = (string)$request->input('section_uid');

                        $allStudents = Student::where('branch', $branchUid)
                            ->where('shift', $shiftUid)
                            ->where('class', $classUid)
                            ->where('section', $sectionUid)
                            ->count();
                        Log::info('Total students in filters: ' . $allStudents);

                        $students = Student::whereNotNull('student_unique_id')
                            ->where('student_unique_id', '!=', '')
                            ->when($request->filled('branch_uid'), fn($q) => $q->where('branch', $branchUid))
                            ->when($request->filled('shift_uid'), fn($q) => $q->where('shift', $shiftUid))
                            ->when($request->filled('class_uid'), fn($q) => $q->where('class', $classUid))
                            ->when($request->filled('section_uid'), fn($q) => $q->where('section', $sectionUid))
                            ->select(['student_unique_id', 'uid', 'student_name_en', 'roll']);

                        Log::info('OOOO', ['students' => $students->get()->toArray()]);

                        $profiles = $students
                            ->get()
                            ->map(function ($item) use ($type) {
                                return [
                                    'profile_id' => trim($item->student_unique_id),
                                    'uid' => $item->uid,
                                    'name' => $item->student_name_en,
                                    'person_type' => $type,
                                    'roll' => $item->roll,
                                ];
                            })
                            ->filter(function ($item) {
                                return !empty($item['profile_id']) && $item['profile_id'] !== '';
                            })
                            ->values()
                            ->toArray();
                    } else {
                        // Log::info('p1');
                        // Optimized student query with eager loading
                        $query = Student::query()
                            ->whereNotNull('student_unique_id')
                            ->where('student_unique_id', '!=', '')
                            ->select(['student_unique_id', 'uid', 'student_name_en', 'division_id', 'district_id', 'upazilla_id', 'branch', 'shift', 'class', 'section']);

                        // Apply profile_id filter if provided
                        if (!empty($requestedProfileIds)) {
                            $query->whereIn('student_unique_id', $requestedProfileIds);
                        }

                        // Eager load relationships to avoid N+1 queries
                        $profiles = $query
                            ->with(['division', 'district', 'upazilla'])
                            ->get()
                            ->map(function ($item) use ($personType) {
                                return [
                                    'profile_id' => trim($item->student_unique_id),
                                    'uid' => $item->uid,
                                    'name' => $item->student_name_en,
                                    'person_type' => $personType,
                                    'division_name_en' => $item->division->division_name_en ?? null,
                                    'district_name_en' => $item->district->district_name_en ?? null,
                                    'upazilla_name_en' => $item->upazilla->upazila_name_en ?? null,
                                    'division_id' => $item->division_id,
                                    'district_id' => $item->district_id,
                                    'upazilla_id' => $item->upazilla_id,
                                    'branch_uid' => $item->branch,
                                    'shift_uid' => $item->shift,
                                    'class_uid' => $item->class,
                                    'section_uid' => $item->section,
                                ];
                            })
                            ->filter(function ($item) {
                                return !empty($item['profile_id']) && $item['profile_id'] !== '';
                            })
                            ->values()
                            ->toArray();
                    }
                } else if ($type == 1) {
                    // Optimized teacher query with eager loading
                    $query = Teacher::query()
                        ->whereNotNull('emp_id')
                        ->where('emp_id', '!=', '')
                        ->select(['emp_id', 'uid', 'name_en', 'division_id', 'district_id', 'upazilla_id']);

                    // Apply profile_id filter if provided
                    if (!empty($requestedProfileIds)) {
                        $query->whereIn('emp_id', $requestedProfileIds);
                        // Log::info('Filtering teachers by emp_id:', ['emp_ids' => $requestedProfileIds]);
                    }

                    // Eager load relationships to avoid N+1 queries
                    $profiles = $query
                        ->with(['division', 'district', 'upazilla'])
                        ->get()
                        ->map(function ($item) use ($type) {
                            return [
                                'profile_id' => trim($item->emp_id),
                                'uid' => $item->uid,
                                'name' => $item->name_en,
                                'person_type' => $type,
                                'division_name_en' => $item->division->division_name_en ?? null,
                                'district_name_en' => $item->district->district_name_en ?? null,
                                'upazilla_name_en' => $item->upazilla->upazila_name_en ?? null,
                                'division_id' => $item->division_id,
                                'district_id' => $item->district_id,
                                'upazilla_id' => $item->upazilla_id,
                            ];
                        })
                        ->filter(function ($item) {
                            return !empty($item['profile_id']) && $item['profile_id'] !== '';
                        })
                        ->values()
                        ->toArray();

                    // Log::info('Teachers found after filtering:', ['count' => count($profiles)]);
                } else if ($type == 2) {
                    // Optimized staff query with eager loading
                    $query = Staff::query()
                        ->whereNotNull('emp_id')
                        ->where('emp_id', '!=', '')
                        ->select(['emp_id', 'uid', 'name_en', 'division_id', 'district_id', 'upazilla_id']);

                    // Apply profile_id filter if provided
                    if (!empty($requestedProfileIds)) {
                        $query->whereIn('emp_id', $requestedProfileIds);
                        // Log::info("Filtering staff by emp_id:", ['emp_ids' => $requestedProfileIds]);
                    }

                    // Eager load relationships to avoid N+1 queries
                    $profiles = $query
                        ->with(['division', 'district', 'upazilla'])
                        ->get()
                        ->map(function ($item) use ($type) {
                            return [
                                'profile_id' => trim($item->emp_id),
                                'uid' => $item->uid,
                                'name' => $item->name_en,
                                'person_type' => $type,
                                'division_name_en' => $item->division->division_name_en ?? null,
                                'district_name_en' => $item->district->district_name_en ?? null,
                                'upazilla_name_en' => $item->upazilla->upazila_name_en ?? null,
                                'division_id' => $item->division_id,
                                'district_id' => $item->district_id,
                                'upazilla_id' => $item->upazilla_id,
                            ];
                        })
                        ->filter(function ($item) {
                            return !empty($item['profile_id']) && $item['profile_id'] !== '';
                        })
                        ->values()
                        ->toArray();

                    // Log::info("Staff found after filtering:", ['count' => count($profiles)]);
                }

                $allProfiles = array_merge($allProfiles, $profiles ?? []);
                // Log::info("After processing person_type $type, total profiles: " . count($allProfiles));
            }

            // Log::info("Final result count:", ['count' => count($allProfiles)]);
            // Log::info("All Profiles:", ['allprofiles' => $allProfiles]);

            return response()->json([
                'status' => true,
                'message' => 'Filtered employee list retrieved successfully.',
                'data' => $allProfiles,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error filtering employees: ' . $e->getMessage(), [
                'request' => $request->all(),
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Server error during filter processing.',
                'data' => [],
            ], 500);
        }
    }
}
