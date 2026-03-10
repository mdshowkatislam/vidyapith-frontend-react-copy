<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Session\SessionStoreRequest;
use App\Http\Requests\Session\SessionUpdateRequest;
use App\Traits\ApiResponser;
use App\Traits\ValidtorMapper;
use App\Services\SessionService;
use Exception;

class SessionController extends Controller
{
    use ApiResponser, ValidtorMapper;

    private $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $eiinId = getAuthInfo()['eiin'];;
            // $eiinId = app('sso-auth')->user()->eiin;
            $sessionList = $this->sessionService->getByEiinId($eiinId);
            return $this->successResponse($sessionList, Response::HTTP_OK);
        } catch (Exception $exc) {
            return $this->errorResponse($exc->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SessionStoreRequest $request)
    {
        try {
            $data = [
                'session'    => $request->session,
                'eiin'       => app('sso-auth')->user()->eiin,
                'rec_status' => $request->rec_status ?? 1,
            ];
            $session = $this->sessionService->create($data);
            $message = 'সেশন সফলভাবে তৈরি করা হয়েছে।';
            return $this->successResponseWithData($session, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'সেশন তৈরি করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    public function getById($id)
    {
        try {
            $sessionData = $this->sessionService->getById($id);
            return $this->successResponse($sessionData, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse("Data not found", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SessionUpdateRequest $request)
    {
        try {
            $data = [
                'uid'        => $request->uid,
                'session'    => $request->session,
                'eiin'       => app('sso-auth')->user()->eiin,
                'rec_status' => $request->rec_status ?? 1,
            ];

            $session = $this->sessionService->update($data);

            $message = 'সেশন সফলভাবে আপডেট করা হয়েছে।';
            return $this->successResponseWithData($session, $message, Response::HTTP_OK);
        } catch (Exception $e) {
            // return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            $message = 'সেশন আপডেট করা সম্ভব হয় নি।';
            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->sessionService->delete($id);

        return response()->json(['status' => 'success', 'message' => 'সেশন এর তথ্যটি মুছে ফেলা হয়েছে।']);
    }
}
