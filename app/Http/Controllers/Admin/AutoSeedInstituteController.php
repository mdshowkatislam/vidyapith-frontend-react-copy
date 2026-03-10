<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Teacher;
use App\Models\Institute;

use App\Helper\MyLogs;
use App\Jobs\SendSms;

use App\Services\Api\AuthService;

class AutoSeedInstituteController extends Controller
{

    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function autoSend(Request $request)
    {
        try {
            ini_set('max_execution_time', 3600 * 3); // 3600 seconds = 60 * 3 minutes
            set_time_limit(3600 * 3);

            if ((int) $request->q !== 1) {
                return "inistitue not create.";
            }

            // $inistitues = Institute::on('db_read')->whereNull('caid')->limit(2)->orderby('id', 'desc')->get();
            // MyLogs::smsCountLog(count($inistitues) , 'count', "POST");
            // for($i = 0; $i <= count($inistitues); $i+=1) {

            //     $this->autoSendCallBack();

            //     echo $i;
            //     echo '<br>';
            // }

            // MyLogs::smsCountLog($i, 'count', "POST");
            echo "done";
        } catch (Exception $e) {
            MyLogs::log($e->getMessage(), 'Institute', "POST");
        }
    }

    private function autoSendCallBack()
    {
        $inistitues = Institute::whereNull('caid')->limit(10)->get();
        foreach ($inistitues as $inistitue) {
            $ins_response = $this->authService->institute($inistitue, 1);
            if (@$ins_response->status == true && @$inistitue->eiin) {
                try {
                    $ins_data = (object) $ins_response->data;

                    DB::beginTransaction();
                    $findInstitute = Institute::where('caid', $ins_data->caid)->first();
                    if (!($findInstitute)) {
                        Institute::where('eiin', @$inistitue->eiin)
                            ->update(['caid' => $ins_data->caid, 'role' => @$ins_data->role]);
                    }

                    $headteacher = Teacher::on('db_read')->where('eiin', $inistitue->eiin)->first();
                    $headteacher['role'] = @$ins_data->role;

                    $response = $this->authService->teacher($headteacher, $inistitue->eiin, 3);

                    if (@$response->status == true && @$headteacher['pdsid']) {
                        $response_data = (object) $response->data;

                        $findCaid = Teacher::where('eiin', $inistitue->eiin)->count();
                        if ($findCaid == 0) {
                            Teacher::where('eiin', $inistitue->eiin)
                                ->update(['caid' => $response_data->caid, 'role' => @$ins_data->role]);
                        }
                        DB::commit();
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
