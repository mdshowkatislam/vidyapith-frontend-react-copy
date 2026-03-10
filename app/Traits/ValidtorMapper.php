<?php
namespace App\Traits;

trait ValidtorMapper
{
    public function Validtor($fields){
        if (!$fields) {
            return;
        }
        $entity = [];
        $jsonResponse = json_decode($fields, true);
        foreach($jsonResponse as $key =>  $field) {
            $entity[$key] = $field[0];
        };
        return $entity;
    }  
}
