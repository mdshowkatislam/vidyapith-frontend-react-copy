<?php

namespace App\Repositories\Interfaces;

interface StudentRepositoryInterface
{
    public function list($request);

    public function create($data, $class_room_uid);

    public function update($data, $id, $class_room_uid);
    
    public function getById($id);

    public function getTotalStudentByEiinId($id);
    
    public function getByEiinId($id, $is_not_paginate, $search, $paginate_number);

    public function getBranchByEiinId($id);

    public function getVersionByEiinId($branch, $id);

    public function getShiftByEiinId($branch, $id);

    public function getWithTrashedById($data, $eiin);

    public function checkRollExists($caid, $roll);

    public function getSectionByEiinId($branch, $class, $shift, $version, $id);

    public function getByCaId($id);

    public function getByUId($id);

    public function authAccountCreateStudent($data);

    public function delete($id);
    
    public function getRelatedItemsForStudent($related_items, $id);

    public function changeStatus($student_uid, $status);
}
