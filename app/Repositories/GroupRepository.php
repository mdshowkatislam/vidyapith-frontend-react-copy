<?php

namespace App\Repositories;

use App\Repositories\Interfaces\GroupRepositoryInterface;

use App\Models\Group;

class GroupRepository implements GroupRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Group::on('db_read')->get();
    }

    public function create($data)
    {
        return Group::create($data);
    }

    public function update($data)
    {
        \Log::info('888');
        \Log::info($data['uid']);
        $group = Group::where('uid', $data['uid'])->first();

        if ($group) {
            $group->group_name_bn   = $data['group_name_bn'];
            $group->group_name_en   = $data['group_name_en'];
            $group->eiin            = $data['eiin'];
            $group->rec_status      = $data['rec_status'];
            $group->save();
            return $group;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Group::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        return Group::on('db_read')->select('uid', 'group_name_bn', 'group_name_en', 'eiin', 'rec_status')->where('eiin', $eiin)->get();

    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Group::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Group::where('uid', $id)->delete();
    }

    public function getByCondition(array $conditions)
    {
        $query = Group::on('db_read');

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
