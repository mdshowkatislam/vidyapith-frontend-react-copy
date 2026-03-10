<?php

namespace App\Repositories;
use App\Repositories\Interfaces\LibFineConfigurationRepositoryInterface;
use App\Models\LibFineConfiguration;

class LibFineConfigurationRepository implements LibFineConfigurationRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return LibFineConfiguration::on('db_read')->with('branch')->get();
    }

    public function create($data)
    {
        return LibFineConfiguration::create($data);
    }

    public function update($data)
    {
        $fine = LibFineConfiguration::where('uid', $data['uid'])->first();

        if ($fine){
            $fine->branch_id            = $data['branch_id'];
            $fine->fine_type            = $data['fine_type'];
            $fine->fine_amount          = $data['fine_amount'];
            $fine->damage_fine_amount   = $data['damage_fine_amount'];
            $fine->loss_fine_amount     = $data['loss_fine_amount'];
            $fine->save();
            return $fine;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
       // return LibFineConfiguration::on('db_read')->select('uid', 'eiin', 'branch_id', 'shift_id', 'version_id', 'class_id', 'section_id', 'subject_code', 'exam_no', 'exam_name', 'exam_full_mark', 'exam_date', 'exam_time', 'exam_start_time', 'exam_end_time', 'exam_details_info', 'status')->where('uid', $id)->first();

        return LibFineConfiguration::on('db_read')
            ->with(['branch' => function($query) {
                $query->select('id', 'branch_name');
            }])
            ->where('uid', $id)
            ->first();
    }

    // public function getByEiinId($eiin, $optimize = null)
    // {
    //     return LibFineConfiguration::on('db_read')->with('branch')->where('eiin', $eiin)->get();
    // }

    public function getByEiinId($eiin, $optimize = null)
    {
        return LibFineConfiguration::on('db_read')
            ->with(['branch' => function($query) {
                $query->select('id', 'branch_name');
            }])
            ->where('eiin', $eiin)
            ->get();
    }

    public function delete($id)
    {
        return LibFineConfiguration::where('uid', $id)->delete();
    }

}
