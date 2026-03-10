<?php

namespace App\Services\ClassRoomService;

interface ClassRoomServiceInterface
{
    public function getAllClassRooms();
    public function getAllClassRoomsByEiin($eiin, $year=null);
    public function getClassRoomById($id);
    public function createClassRoom($data);
    public function updateClassRoom($id, $data);
    public function deleteClassRoom($id);
    public function getAllClassRoomsByEiinWithPagination($eiin);
    public function findOrCreateClassRoom($data);
    public function getRelatedItemsForClassroom($related_items, $id);
}
