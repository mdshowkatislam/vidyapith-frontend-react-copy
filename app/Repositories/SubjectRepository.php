<?php

namespace App\Repositories;

use App\Repositories\Interfaces\SubjectRepositoryInterface;

use App\Models\Subject;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Subject::on('db_read')->get();
    }

    public function create($data)
    {
        return Subject::create($data);
    }

    public function update($data)
    {
        $subject = Subject::where('uid', $data['uid'])->first();

        if ($subject) {
            $subject->subject_name_bn   = $data['subject_name_bn'];
            $subject->subject_name_en   = $data['subject_name_en'];
            $subject->subject_code      = $data['subject_code'];
            $subject->session           = $data['session'];
            $subject->eiin              = $data['eiin'];
            $subject->rec_status        = $data['rec_status'];
            $subject->save();
            return $subject;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Subject::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return Subject::on('db_read')->select('uid', 'subject_name_bn', 'subject_name_en', 'subject_code', 'session', 'eiin', 'rec_status')->where('eiin', $eiin)->get();

    }
    public function getBySubjectId($eiin, $optimize = null, $subject_id)
    {
        return Subject::on('db_read')->whereIn('uid', $subject_id)->select('uid', 'subject_name_bn', 'subject_name_en', 'subject_code', 'session', 'eiin', 'rec_status')->where('eiin', $eiin)->get();

    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Subject::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Subject::where('uid', $id)->delete();
    }

    public function getByCondition(array $conditions)
    {
        $query = Subject::on('db_read');

        foreach ($conditions as $condition) {
            if (is_callable($condition)) {
                $query = $query->where($condition);
            } else {
                $query = $query->where($condition[0], $condition[1], $condition[2]);
            }
        }

        return $query->first();
    }
}
