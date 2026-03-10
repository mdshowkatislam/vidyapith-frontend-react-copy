<?php

namespace App\Repositories;

use App\Models\ClassRoom;
use App\Models\Section;
use App\Models\Student;
use App\Repositories\Interfaces\SessionRepositoryInterface;

use App\Models\Session;

class SessionRepository implements SessionRepositoryInterface
{
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        return Session::on('db_read')->get();
    }

    public function create($data)
    {
        return Session::create($data);
    }

    public function update($data)
    {
        $session = Session::where('uid', $data['uid'])->first();

        if ($session) {
            $session->session = $data['session'];
            $session->eiin = $data['eiin'];
            $session->rec_status = $data['rec_status'];
            $session->save();
            return $session;
        } else {
            return false;
        }
    }

    public function getById($id)
    {
        return Session::on('db_read')->where('uid', $id)->first();
    }

    public function getByEiinId($eiin, $optimize = null)
    {
        if ($optimize) {
            return Session::on('db_read')->select('uid', 'session')->where('eiin', $eiin)->get();
        } else {
            return Session::on('db_read')->select('uid', 'session', 'eiin', 'rec_status')->where('eiin', $eiin)->get();
        }
    }

    public function getByEiinIdWithPagination($eiin)
    {
        return Session::on('db_read')->where('eiin', $eiin)->paginate(20);
    }

    public function delete($id)
    {
        return Session::where('uid', $id)->delete();
    }

}
