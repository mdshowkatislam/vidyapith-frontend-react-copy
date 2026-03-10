<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ClassWiseSubjectRepositoryInterface;

use App\Models\ClassWiseSubject;

class ClassWiseSubjectRepository implements ClassWiseSubjectRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return ClassWiseSubject::on('db_read')->get();
    }

    public function create($data)
    {
        return ClassWiseSubject::create($data);
    }

    public function update($data)
    {
        $classWiseSubject = ClassWiseSubject::where('uid', $data['uid'])->first();

        if ($classWiseSubject) {
            $classWiseSubject->class_id   = $data['class_id'];
            $classWiseSubject->section_id = $data['section_id'];
            $classWiseSubject->subject_id = $data['subject_id'];
            $classWiseSubject->session_id = $data['session_id'];
            $classWiseSubject->eiin       = $data['eiin'];
            $classWiseSubject->rec_status = $data['rec_status'];
            $classWiseSubject->save();
            return $classWiseSubject;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return ClassWiseSubject::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        // return ClassWiseSubject::on('db_read')->select('uid', 'class_id', 'subject_id','session_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();

        $subjects = ClassWiseSubject::on('db_read')
                ->select('uid', 'class_id', 'subject_id', 'section_id','session_id', 'eiin', 'rec_status')
                ->where('eiin', $eiin)
                ->get();

        //  \Log::info('GGG',['KK'=>$subjects->toArray()]);

        // Group the results by `class_id` and `session_id`
        $groupedData = $subjects->groupBy(function ($item) {
            return $item->class_id . '-' . $item->section_id . '-' . $item->session_id;
        })->map(function ($group) {
            return [
                'uid'        => $group->first()->uid,
                'class_id'   => $group->first()->class_id,
                'section_id'   => $group->first()->section_id,
                'subject_id' => $group->pluck('subject_id')->all(),
                'session_id' => $group->first()->session_id,
                'eiin'       => $group->first()->eiin,
                'rec_status' => $group->first()->rec_status,
            ];
        })->values(); // Reset keys to get a sequential array

        return $groupedData;
    }

    public function getBySubjectId($eiin, $optimize = null, $class_id, $subject_id)
    {
        // return ClassWiseSubject::on('db_read')->select('uid', 'class_id', 'subject_id','session_id', 'eiin', 'rec_status')->where('eiin', $eiin)->get();

        $subjects = ClassWiseSubject::on('db_read')
                ->whereIn('class_id', $class_id)
                ->whereIn('subject_id', $subject_id)
                ->select('uid', 'class_id', 'subject_id', 'session_id', 'eiin', 'rec_status')
                ->where('eiin', $eiin)
                ->get();
        // Group the results by `class_id` and `session_id`
        $groupedData = $subjects->groupBy(function ($item) {
            return $item->class_id . '-' . $item->session_id;
        })->map(function ($group) {
            return [
                'uid'        => $group->first()->uid,
                'class_id'   => $group->first()->class_id,
                'subject_id' => $group->pluck('subject_id')->all(),
                'session_id' => $group->first()->session_id,
                'eiin'       => $group->first()->eiin,
                'rec_status' => $group->first()->rec_status,
            ];
        })->values(); // Reset keys to get a sequential array

        return $groupedData;

    }

    public function getByEiinIdWithPagination($eiin)
    {
        return ClassWiseSubject::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($class_id, $session_id)
    {
        return ClassWiseSubject::where('class_id', $class_id)->where('session_id', $session_id)->delete();
    }

}
