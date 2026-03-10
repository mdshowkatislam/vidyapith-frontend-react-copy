<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Student;
use App\Repositories\Interfaces\VersionRepositoryInterface;

use App\Models\Version;

class VersionRepository implements VersionRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Version::on('db_read')->get();
    }

    public function create($data)
    {
        return Version::create($data);
    }

    public function update($data)
    {
        $version = Version::where('uid', $data['uid'])->first();

        if ($version) {
            $version->version_name_en = $data['version_name_en'];
            $version->version_name_bn = $data['version_name_bn'];
            $version->branch_id = $data['branch_id'];
            $version->eiin = $data['eiin'];
            $version->rec_status = $data['rec_status'];
            $version->save();
            return $version;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Version::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return Version::on('db_read')->select('uid', 'version_name_en', 'version_name_bn')->where('eiin', $eiin)->get();
        } else {
            return Version::on('db_read')->select('uid', 'version_id', 'branch_id', 'version_name_en', 'version_name_bn', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
        }
    }

    public function getByVersionId($eiin, $optimize = null, $version_id)
    {
        if ($optimize) {
            return Version::on('db_read')->whereIn('uid', $version_id)->select('uid', 'version_name_en', 'version_name_bn')->where('eiin', $eiin)->get();
        } else {
            return Version::on('db_read')->whereIn('uid', $version_id)->select('uid', 'version_id', 'branch_id', 'version_name_en', 'version_name_bn', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Version::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Version::where('uid', $id)->delete();
    }

    public function getRelatedItemsForVersion($related_items, $id)
    {
        $eiin = app('sso-auth')->user()->eiin;

        $section_list = Section::where('eiin', $eiin)->where('version_id', $id)->get();
        $student_list = Student::where('eiin', $eiin)->where('shift', $id)->get();
        $subjectTeacher_list = ClassRoom::where('eiin', $eiin)->where('version_id', $id)->get();

        $related_items['section_items'] = $section_list;
        $related_items['student_items'] = $student_list;
        $related_items['subject_teachers'] = $subjectTeacher_list;

        return $related_items;
    }

    public function getByBranch($branch_id)
    {
        return Version::on('db_read')->select('uid', 'version_name_en', 'version_name_bn')
                ->where('eiin', app('sso-auth')->user()->eiin)
                ->where('branch_id', $branch_id)
                ->where('rec_status', 1)
                ->get();
    }
}
