<?php

namespace App\Services;

use Illuminate\Http\Request;

class SubjectServiceOld
{

    public function __construct()
    {

    }

    public function getAll($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['class_id'] = @$request_data->class_id;
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.class_wise_subject_api').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getSubjectInfo($subject_id)
    {
        $subjects = $this->getAll();
        $collection = collect($subjects);

        $foundSubject = $collection->firstWhere('uid', $subject_id);

        return $foundSubject;
    }

    public function getAllBis()
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('GET', config('configure.bis_api'));
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getAllAssessments()
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('GET', config('configure.assessment_api'));
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getChapter($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['subject_id'] = @$request_data['subject_id'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.subject_wise_chapter_api').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getCompetenceBySubject($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['subject_id'] = @$request_data['subject_id'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.competences_by_subject_api').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getOviggotaBySubject($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['subject_uid'] = @$request_data['subject_uid'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.oviggotas_by_subject_api').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getDimensionBySubject($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['subject_uid'] = @$request_data['subject_uid'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.dimensions_by_subject_api').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getBiDimension()
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('GET', config('configure.bi_dimension_api'));
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getCompetenceBychapter($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['chapter_id'] = @$request_data['chapter_id'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.competences_by_chapter_api').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getPiWeight()
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $res = $client->request('GET', config('configure.pi_weight_api'));
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getPiSelectionBySubject($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['subject_uid'] = @$request_data['subject_uid'];
        $request['session'] = @$request_data['session'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.pi_selection_by_subject').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }

    public function getSinglePi($request_data=null)
    {
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $request = new Request();
        $request['pi_uid'] = @$request_data['pi_uid'];
        $queryString = http_build_query($request->all());
        // $queryString = http_build_query($request);
        $end_point = config('configure.single_pi').'?'.$queryString;
        $res = $client->request('GET', $end_point);
        $apiData = json_decode($res->getBody()->getContents(), true);
        return $apiData['data'];
    }
}
