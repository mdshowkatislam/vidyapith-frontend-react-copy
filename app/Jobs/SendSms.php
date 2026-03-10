<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Models\Institute;

use App\Helper\MyLogs;

use App\Services\Api\AuthService;

use App\Mail\CreateUserAccountEmail;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle()
    {
        $this->autoSendCallBack();
    }

    private function autoSendCallBack()
    {
        $inistitues = Institute::on('db_read')->whereNull('caid')->limit(10)->get();
        foreach ($inistitues as $inistitue) {
            $ins_response = $this->authService->institute($inistitue);
            if (@$ins_response->status == true && @$inistitue->eiin) {
                try {
                    $ins_data = (object) $ins_response->data;

                    DB::beginTransaction();
                    Institute::where('eiin', @$inistitue->eiin)
                        ->update(['caid' => $ins_data->caid, 'role' => @$ins_data->role]);

                    $headteacher = Teacher::on('db_read')->where('eiin', $inistitue->eiin)->first();
                    $headteacher['role'] = @$ins_data->role;

                    $response = $this->authService->teacher($headteacher, $headteacher->eiin, 3);
                    if (@$response->status == true && @$headteacher['pdsid']) {
                        $response_data = (object) $response->data;

                        $findCaid = Teacher::where('caid', $response_data->caid)->first();
                        if (empty($findCaid)) {
                            Teacher::where('eiin', $inistitue->eiin)
                                ->update(['caid' => $response_data->caid, 'role' => @$ins_data->role]);
                            DB::commit();
                        }
                    } else {
                        DB::rollback();
                        MyLogs::HeadTeacher($headteacher, @$response->message ?? 'pdsid not found');
                    }
                } catch (Exception $e) {
                    DB::rollback();
                    MyLogs::log($e->getMessage(), 'HeadTeacher', "POST");
                }
            } else {
                MyLogs::Institute($inistitue, @$ins_response->message ?? 'eiin not found');
            }
        }
    }
}
