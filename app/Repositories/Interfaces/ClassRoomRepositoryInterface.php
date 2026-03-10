<?php

namespace App\Repositories\Interfaces;

interface ClassRoomRepositoryInterface
{
    public function getAll();
    public function getAllByEiin($eiin, $year=null);
    public function getById($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
    public function getAllByEiinWithPagination($eiin);
    public function findOrCreateClassRoom($data);
    public function getRelatedItemsForClassroom($related_items, $id);
}
