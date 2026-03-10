<?php

namespace App\Helper;

use App\Models\ClassRoom;
use App\Models\SubjectTeacher;
use App\Services\TeacherService;

class TeacherInfo
{
    private $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public static function teacherInfo(){
        
        $teacherService = app(TeacherService::class);
        $teacher = $teacherService->getById(app(abstract: 'sso-auth')->user()->caid);
        $class_teacher = ClassRoom::with('class_teacher', 'subject_teachers.teacher')->where('class_teacher_id', $teacher->uid)->get();
        if(empty($teacher->class_teacher)){
            $subject_teacher = SubjectTeacher::where('teacher_uid', $teacher->uid)->get();
            return [
                'teacher_type' => 'subject_teacher', 
                'data' => $subject_teacher
            ];
        }else{
            return [
                'teacher_type' => 'class_teacher', 
                'data' => $class_teacher
            ];
        }
    }
}
