<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Student;
use App\Repositories\Interfaces\ClassRepositoryInterface;

use App\Models\ClassName;

class ClassRepository implements ClassRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return ClassName::on('db_read')->get();
    }

    public function create($data)
    {
        return ClassName::create($data);
    }

    public function update($data)
    {
        $class = ClassName::where('uid', $data['uid'])->first();

        if ($class) {
            $class->class_name_bn = $data['class_name_bn'];
            $class->class_name_en = $data['class_name_en'];
            $class->eiin = $data['eiin'];
            $class->rec_status = $data['rec_status'];
            $class->save();
            return $class;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return ClassName::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return ClassName::on('db_read')->select('uid', 'class_name_bn', 'class_name_en', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
    }

    public function getByClassId($eiin, $optimize = null, $class_id)
    {
        return ClassName::on('db_read')->whereIn('uid', $class_id)->select('uid', 'class_name_bn', 'class_name_en', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return ClassName::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return ClassName::where('uid', $id)->delete();
    }

}
