<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Student;
use App\Models\Version;
use App\Repositories\Interfaces\SectionRepositoryInterface;

class SectionRepository implements SectionRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Section::on('db_read')->get();
    }

    public function create($data)
    {
        return Section::create($data);
    }

    public function update($data)
    {
        $Section = Section::where('uid', $data['uid'])->first();

        if ($Section) {
            $Section->section_name    = $data['section_name'];
            $Section->section_name_en = $data['section_name_en'];
            $Section->section_details = $data['section_details'];
            $Section->eiin            = $data['eiin'];
            $Section->branch_id       = $data['branch_id'];
            $Section->shift_id        = $data['shift_id'];
            $Section->version_id      = $data['version_id'];
            $Section->class_id        = $data['class_id'];
            $Section->rec_status      = $data['rec_status'];
            $Section->save();

            return $Section;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Section::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return Section::on('db_read')->select('uid', 'section_name')->where('eiin', $eiin)->get();
        } else {
            return Section::on('db_read')->select('uid', 'section_name', 'section_name_en', 'eiin', 'branch_id', 'class_id', 'shift_id', 'version_id', 'section_details')
            ->where('eiin', $eiin)->get();
        }
    }

    public function getBySectionId($eiin, $optimize = null, $section_id)
    {
        if ($optimize) {
            return Section::on('db_read')->whereIn('uid', $section_id)->select('uid', 'section_name')->where('eiin', $eiin)->get();
        } else {
            return Section::on('db_read')->whereIn('uid', $section_id)->select('uid', 'section_name', 'section_name_en', 'eiin', 'branch_id', 'class_id', 'shift_id', 'version_id', 'section_details')
            ->where('eiin', $eiin)->get();
        }
    }

    public function delete($id)
    {
        return Section::where('uid', $id)->delete();
    }

    public function getRelatedItemsForSection($related_items, $id)
    {
        $eiin = app('sso-auth')->user()->eiin;

        $student_list = Student::where('eiin', $eiin)->where('section', $id)->get();
        $subjectTeacher_list = ClassRoom::where('eiin', $eiin)->where('section_id', $id)->get();

        $related_items['student_items'] = $student_list;
        $related_items['subject_teachers'] = $subjectTeacher_list;

        return $related_items;
    }

    public function getByclass($data)
    {
        return Section::on('db_read')->select('uid', 'section_name', 'section_name_en')
        ->where('eiin', app('sso-auth')->user()->eiin)
        ->where('branch_id', $data['branch_id'])
        ->where('class_id', $data['class_id'])
        ->where('shift_id', $data['shift_id'])
        ->where('version_id', $data['version_id'])
        ->where('rec_status', 1)
        ->get();
    }
}
