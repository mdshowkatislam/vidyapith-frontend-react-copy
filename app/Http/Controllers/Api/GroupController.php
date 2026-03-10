<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Group\GroupStoreRequest;
use App\Http\Requests\Group\GroupUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\GroupService;
use Exception;

class GroupController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $authInfo = getAuthInfo();
            $eiinId = $authInfo['eiin'];
            $groupList = $this->groupService->getByEiinId($eiinId);
            return $this->successResponse($groupList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GroupStoreRequest $request)
    {
        try {
            $data = [
                'group_name_bn'   => $request->group_name_bn,
                'group_name_en'   => $request->group_name_en,
                'eiin'              => app('sso-auth')->user()->eiin,
                'rec_status'        => $request->rec_status ?? 1,
            ];
            $group = $this->groupService->create($data);
            $message = 'গ্রুপ সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($group, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // $message = 'গ্রুপ তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $groupData = $this->groupService->getById($id);
            return $this->successResponse($groupData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GroupUpdateRequest $request)
    { 
         \Log::info('uu12');
    
        try {
            $data = [
                'uid'               => $request->uid,
                'group_name_bn'   => $request->group_name_bn,
                'group_name_en'   => $request->group_name_en,
                'eiin'              => getAuthInfo()['eiin'],
                'rec_status'        => $request->rec_status ?? 1,
            ];
\Log::info('uu2');
            $group = $this->groupService->update($data);

            $message = 'গ্রুপ সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($group, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            \Log::info('uu');
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'গ্রুপ আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->groupService->delete($id);

        return response()->json(['status' => 'success', 'message' => 'গ্রুপ এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}
