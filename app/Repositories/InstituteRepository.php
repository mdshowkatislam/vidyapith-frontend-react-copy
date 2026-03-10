<?php

namespace App\Repositories;

use Exception;
use App\Models\Board;
use App\Models\Shift;
use App\Models\Branch;
use App\Models\Section;

use App\Models\Student;

use App\Models\Teacher;
use App\Models\Version;

use App\Models\ClassRoom;
use App\Models\Institute;
use App\Models\BoardToDistrict;
use App\Services\TeacherService;
use App\Services\Api\AuthService;
use App\Helper\UtilsPassportToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interfaces\InstituteRepositoryInterface;

class InstituteRepository implements InstituteRepositoryInterface
{
    private $teacherService;
    private $authService;

    public function __construct(
        TeacherService $teacherService,
        AuthService $authService
    ) {
        $this->teacherService = $teacherService;
        $this->authService = $authService;
    }
    public function list($request)
    {
        $upazila_id =  $request->upazila_id;


        $institute = Institute::on('db_read')->with('head_master')->select(
            'id',
            'uid',
            'eiin',
            'institute_name',
            'institute_name_bn',
            'is_foreign',
            'upazila_uid',
            'category',
            'phone',
            'head_caid'
        );

        if (app('sso-auth')->user()->caid != '4010001') {
            if ((app('sso-auth')->user()->user_type_id == '5') && (empty(app('sso-auth')->user()->upazila_id))) {
                $institute = $institute->where('is_foreign', 1);
            } else if (!empty($upazila_id)) {
                $institute = $institute->where('upazila_uid', $upazila_id);
            }
        }


        if (!empty($request->input('eiin'))) {
            $institute = $institute->where('eiin', $request->input('eiin'));
        }

        if (!empty($request->input('name'))) {
            $instituteName = $request->input('name');
            $institute = $institute->where(function ($query) use ($instituteName) {
                $query->where('institute_name', 'like', "%{$instituteName}%")
                    ->orWhere('institute_name_bn', 'like', "%{$instituteName}%");
            });
        }

        if (!empty($request->category)) {
            $institute = $institute->where('category', $request->category);
        }

        if (!empty($request->phone)) {
            $institute = $institute->where('phone', $request->phone);
        }


        if (!empty($request->type) && ($request->type == 'web')) {
            $data = DataTables::of($institute)
                ->addColumn('headmaster_name', function ($data) {
                    $head_master_name = '';

                    if (!empty($data->head_master)) {
                        $head_master_name = (!empty($data->head_master['name_en'])) ? $data->head_master['name_en'] : ((!empty($data->head_master['name_bn'])) ? $data->head_master['name_bn'] : '');
                    }

                    return $head_master_name;
                })
                ->addColumn('phone', function ($data) {
                    return !empty($data->phone) ? $data->phone : '';
                })
                ->addColumn('status', function ($data) {
                    $institute_name = $data->institute_name ?? $data->institute_name_bn;
                    $eiin = $data->eiin;
                    $head_master_name = is_array($data->head_master) ? ($data->head_master['name_en'] ?? $data->head_master['name_bn'] ?? '') : '';
                    $phone = $data->phone ?? '';

                    if (empty($institute_name)) {
                        return '<div class="np-badge np-badge-error">প্রতিষ্ঠান যুক্ত করুন</div>';
                    } elseif (empty($eiin)) {
                        return '<div class="np-badge np-badge-error">EIIN সংযুক্ত করুন</div>';
                    } elseif (empty($head_master_name)) {
                        return '<div class="np-badge np-badge-error">প্রধান শিক্ষক নির্বাচন করুন</div>';
                    } elseif (empty($phone)) {
                        return '<div class="np-badge np-badge-error">মোবাইল নম্বর সংযুক্ত করুন</div>';
                    } else {
                        return '<div class="np-badge np-badge-info">সব ঠিক আছে</div>';
                    }
                })
                ->addColumn('actions', function ($data) {
                    return '<div class="d-flex gap-2"><div class="border-end"><a type="button" class="btn btn-secondary" onclick="editFunction(\'' . $data->uid . '\')" style="color: #428f92;">Edit</a></div></div>';
                })

                ->rawColumns(['phone_number', 'status', 'actions'])
                ->make(true);

            return $data;
        }

        $total_institute = $institute->count();


        $perPage = $request->limit ?? 10; // Number of items per page
        $page = $request->page ?? 1; // Current page number

        $offset = ($page - 1) * $perPage;

        $institute = $institute->skip($offset)->take($perPage)->get();

        return ['total_institute' => $total_institute, 'institute' => $institute];
    }

    public function list_old()
    {
        $search = request()->q;
        $headmaster = (bool) request()->headmaster;
        $division_id = request()->division_id;
        $district_id = request()->district_id;
        $upazila_id = request()->upazila_id;
        $limit = request()->limit ?? 20;
        $institute = Institute::on('db_read')->with(['head_master' => function ($query) use ($headmaster) {
            if ($headmaster) {
                $query->where('designation_id', 76);
            } else {
                $query->where('designation_id', '!=', 76);
            }
        }])
            ->where(function ($query) use ($search, $division_id, $district_id, $upazila_id) {
                if (!empty($search)) {
                    if (is_numeric($search)) {
                        $query->where('institutes.eiin', 'like', "%$search%");
                    } else {
                        $query->where('institutes.institute_name', 'like', "%$search%");
                    }
                }
                if (!empty($division_id)) {
                    $query->where('division_uid', $division_id);
                }

                if (!empty($district_id)) {
                    $query->where('district_uid', $district_id);
                }

                if (!empty($upazila_id)) {
                    $query->where('upazila_uid', $upazila_id);
                }
            })
            ->paginate($limit);
        return $institute;
    }

    // public function storeInstituteHeadMaster($data)
    // {
    //     $emis_teacher = (array) DB::table('emis_teacher')->where('pdsid', $data['pdsid'])->first();
    //     if (!$emis_teacher) {
    //         throw new \ErrorException('Teacher not found');
    //     }

    //     $teacher = Teacher::where('pdsid', $data['pdsid'])->first();
    //     if (empty(@$teacher->uid)) {
    //         $emis_teacher['eiin'] = $data['eiin'];
    //         $emis_teacher['designation_id'] = @$data['designation_id'];

    //         $authRequest = $this->authService->teacher($emis_teacher, $data['eiin']);
    //         if (@$authRequest->status == true) {
    //             $authData = (object) $authRequest->data;
    //             $emis_teacher['caid'] = $authData->caid;
    //             $emis_teacher['eiin'] = $authData->eiin;
    //             $teacherService =  $this->teacherService->create($emis_teacher);
    //             if ($emis_teacher['designation_id'] == 76) {
    //                 $this->addHeadTeacher($emis_teacher['caid'], $emis_teacher['eiin']);
    //             }
    //             return $teacherService;
    //         } else {
    //             throw new \ErrorException($authRequest->message);
    //         }
    //     } else {
    //         $teacher->update(['eiin' => $data['eiin'], 'designation_id' => @$data['designation_id']]);
    //         if ($teacher->designation_id == 76) {
    //             return $this->addHeadTeacher($teacher->caid, $teacher->eiin);
    //         }
    //     }
    //     return $teacher;
    // }
    public function storeInstituteHeadMaster($data)
    {


        $emis_teacher = $this->teacherService->searchTeacherByPDSID($data);

        if (empty($emis_teacher)) {
            throw new \ErrorException('Teacher not found');
        }
        // $teacher = Teacher::where('pdsid', $data['pdsid'])->first();
        $teacher = Teacher::withTrashed()->where('pdsid', $data['pdsid'])->orWhere('index_number', $data['pdsid'])->first();

        if (empty($teacher->uid)) {

            $emis_teacher['eiin'] = $data['eiin'] ?? '';
            $emis_teacher['designation_id'] = @$data['designation_id'];

            $authRequest = $this->authService->teacher($emis_teacher, $data['eiin']);

            if (@$authRequest->status == true) {
                $authData = (object) $authRequest->data;
                $emis_teacher['caid'] = $authData->caid;
                $emis_teacher['eiin'] = $authData->eiin;
                $teacherService =  $this->teacherService->create($emis_teacher);

                // if ($emis_teacher['designation_id'] == 76) {
                $headteacher = $this->addHeadTeacher($emis_teacher['caid'], $emis_teacher['eiin']);

                return $teacherService;
            } else {
                throw new \ErrorException($authRequest->message);
            }
        } else {
            if (!empty($teacher->deleted_at)) {
                $teacher->update(['eiin' => $data['eiin'], 'designation_id' => @$data['designation_id'], 'deleted_at' => NULL]);
                // if ($teacher->designation_id == 76) {
                return $this->addHeadTeacher($teacher->caid, $teacher->eiin);
            }
        }
        return $teacher;
    }

    private function addHeadTeacher($caid, $eiin)
    {
        $institute = Institute::where('eiin', $eiin)->first();
        $institute->update(['head_caid' => $caid]);
        return $institute;
    }

    public function create($data)
    {
        $institute = new Institute;
        $institute->setConnection('mysql');

        $institute->eiin = @$data['eiin'];
        $institute->caid = @$data['caid'];

        $institute->board_uid   = ($data['is_foreign'] == 1) ? '2024010101' : $data['board_uid'];

        // $institute->board_uid    = @$data['board_uid'];
        // $institute->division_uid = @$data['division_id'];
        // $institute->district_uid = @$data['district_id'];
        // $institute->upazilla_uid = @$data['upazilla_id'];

        $institute->division_uid           = ($data['is_foreign'] == 0) ? @$data['division_id'] : null;
        $institute->district_uid           = ($data['is_foreign'] == 0) ? @$data['district_id'] : null;
        $institute->upazila_uid            = ($data['is_foreign'] == 0) ? @$data['upazilla_id'] : null;

        $institute->country                 = ($data['is_foreign'] == 1) ? @$data['country'] : null;
        $institute->city                    = ($data['is_foreign'] == 1) ? @$data['city'] : null;
        $institute->state                   = ($data['is_foreign'] == 1) ? @$data['state'] : null;
        $institute->is_foreign              =  $data['is_foreign'];

        $institute->unions = @$data['unions'];
        $institute->institute_name = @$data['institute_name'];
        $institute->institute_name_bn       = @$data['institute_name_bn'];
        $institute->institute_type = @$data['institute_type'] ?? $data['type'];
        $institute->category = @$data['category'];
        $institute->level = @$data['level'];
        $institute->mpo = @$data['mpo'];
        $institute->phone = @$data['phone'];
        $institute->head_of_institute_mobile = @$data['head_of_institute_mobile'];
        $institute->mobile = @$data['mobile'];
        $institute->email = @$data['email'];
        $institute->address = @$data['address'];
        $institute->post_office = @$data['post_office'];
        $institute->message = @$data['message'];
        $institute->data_source = @$data['data_source'];
        // $institute->head_caid               = NULL;
        $institute->head_caid = @$data['head_caid'];
        // $institute->institute_source = @$data['institute_source'];
        $directory = 'institute/logo';
        if (@$data['logo'] && $image = @$data->file('logo')) {
            $filename =  $institute->eiin . '_' . date('Ymd') . '_' . time() . '.' . $image->getClientOriginalExtension();
            $filePath = $image->storeAs(
                $directory,
                $filename,
                's3'
            );
            $institute['logo'] = $filePath;
        }

        $institute->has_eiin = !empty($data['eiin']) ? 1 : 0;
        $institute->save();
        return $institute;
    }

    public function update($request, $id)
    {
        // dd($request->all());
        // $teacher    = Teacher::on('db_read')->where('pdsid', $request['pdsid'])->where('pdsid', $request['pdsid'])->first();
        $institute = Institute::on('db_read')->where('eiin', $id)->first();

        $institute->institute_name   = $request['institute_name'];
        $institute->institute_name_bn = $request['institute_name_bn'] ?? null;
        $institute->institute_type   = $request['institute_type'] ?? null;
        $institute->category         = $request['category'] ?? null;
        $institute->phone            = $request['phone'] ?? null;
        $institute->email            = $request['email'] ?? null;
        $institute->address          = $request['address'] ?? null;
        // $institute->board_uid        = ($request['is_foreign'] == 1) ? '2024010101' : $request['board_uid'];

        $institute->division_uid     = ($request['is_foreign'] == 0) ? @$request['division_id'] : NULL;
        $institute->district_uid     = ($request['is_foreign'] == 0) ? @$request['district_id'] : NULL;
        $institute->upazila_uid      = ($request['is_foreign'] == 0) ? (@$request['upazilla_id'] ?? @$request['upazila_id']) : NULL;

        $institute->country          = ($request['is_foreign'] == 1) ? @$request['country'] : NULL;
        $institute->city             = ($request['is_foreign'] == 1) ? @$request['city'] : NULL;
        $institute->state            = ($request['is_foreign'] == 1) ? @$request['state'] : NULL;
        $institute->zip_code         = ($request['is_foreign'] == 1) ? @$request['zip_code'] : NULL;

        $institute->head_of_institute_mobile = @$request['head_of_institute_mobile'] ?? null;
        $institute->mobile = @$request['mobile'] ?? null;

        $institute->is_foreign       = $request['is_foreign'];
        // $institute->head_caid        = $request['head_caid'];
        $institute->logo             = $request['filePath'] ?? null;

        // if (!empty($teacher->caid) && ($teacher->caid !== $institute->head_caid)) {
        //     $institute->head_caid = NULL;
        // }

        // $directory = 'institute/logo';

        // if (@$request['logo'] && $image = @$request->file('logo')) {
        //     $filename =  $institute->eiin . '_' . date('Ymd') . '_' . time() . '.' . $image->getClientOriginalExtension();
        //     $filePath = $image->storeAs(
        //         $directory,
        //         $filename,
        //         's3'
        //     );
        //     $institute['logo'] = $filePath;
        // }

        // $hasImagesDirectory = Storage::exists($directory);
        // dd('$hasImagesDirectory');
        // if (! $hasImagesDirectory) {
        //     Storage::disk('vultr')->makeDirectory($directory);
        // }
        // if ($image = $request->file('logo')) {
        //     // $filename = date('Ymd') . '_' . time() . '.' . $image->getClientOriginalExtension();
        //     $filename = date('Ymd') . '_' . time() . '.' . $image->getClientOriginalExtension();
        //     $filePath = $image->storePubliclyAs(
        //         $directory,
        //         $filename,
        //         'vultr'
        //     );
        //     dd($filePath);
        //     $institute['logo'] = $filePath;
        // }

        // if ($file = $request->file('logo')) {
        //     // $filename = date('Ymd') . '_' . time() . '.' . $file->getClientOriginalExtension();
        //     $filename = 'abc.'.$file->getClientOriginalExtension();
        //     dd(Storage::disk('s3')->exists($path, '/abc.'.$file->getClientOriginalExtension()));
        //         // dd($filename);
        //         // $file->storeAs(
        //         //     $path, $filename, 's3'
        //         // );
        //         Storage::disk('s3')->putFileAs($path, $file, $filename, 'public');
        //         $institute['logo'] = $filename;
        //     }
        $institute->save();
        return $institute;
    }

    public function getById($id)
    {
        return Institute::on('db_read')->with('head_master')->where('uid', $id)->first();
    }

    public function getWithTrashedById($id)
    {
        return DB::table('teachers')->where('uid', $id)->orwhere('caid', $id)->orWhere('pdsid', $id)->first();
    }

    public function getByCaId($id)
    {
        return Teacher::on('db_read')->where('caid', $id)->orWhere('pdsid', $id)->first();
    }

    public function getByInstId($id)
    {
        return Institute::on('db_read')->where('eiin', $id)->first();
    }

    public function getByEiinId($id, $optimize = null)
    {
        if ($optimize) {
            return Institute::on('db_read')->select('uid', 'eiin', 'institute_name', 'institute_name_bn', 'board_uid')->where('eiin', $id)->first();
        } else {
            return Institute::on('db_read')->where('eiin', $id)->first();
        }
    }

    public function getByUpazilaId($id)
    {
        return Institute::on('db_read')->with('headMaster')->where('upazila_uid', $id)->get();
    }

    public function getUpazilaInstituteWithHeadMaster($upazila_id)
    {
        $search = request()->q;
        $limit = request()->limit ?? 20;
        $institutes = Institute::on('db_read')->with(['head_master'])
            ->where('upazila_uid', $upazila_id)
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    if (is_numeric($search)) {
                        $query->where('institutes.eiin', 'like', "%$search%");
                    } else {
                        $query->where('institutes.institute_name', 'like', "%$search%");
                    }
                }
            })
            ->paginate($limit);
        return $institutes;
    }

    public function getUpazilaTeachers($upazila_id)
    {
        $teachers = DB::table('emis_teacher')->where('upazilaid', $upazila_id)->where('designationid', '!=', 76)->paginate(1000);

        return $teachers;
    }

    public function updateInstituteHeadMaster($data)
    {
        Teacher::where('eiin', $data['eiin'])->delete();
        return $this->storeInstituteHeadMaster($data);
    }

    public function getByIdWithDetails($eiin)
    {

        $data['teachers']   = Teacher::on('db_read')->where('eiin', $eiin)->count();
        $data['students']   = Student::on('db_read')->where('eiin', $eiin)->count();
        $data['branches']   = Branch::on('db_read')->where('eiin', $eiin)->count();
        $data['shifts']     = Shift::on('db_read')->where('eiin', $eiin)->count();
        $data['versions']   = Version::on('db_read')->where('eiin', $eiin)->count();
        $data['sections']   = Section::on('db_read')->where('eiin', $eiin)->count();
        $data['class_rooms'] = ClassRoom::on('db_read')->where('eiin', $eiin)->count();

        return $data;
    }

    public function upazillaTotalInstitutes($request)
    {
        $totalInstitute = Institute::on('db_read')->where('upazila_uid', $request->id)->count();
        return $totalInstitute;
    }
    public function foreignTotalInstitutes()
    {
        $totalInstitute = Institute::on('db_read')->where('is_foreign', 1)->count();
        return $totalInstitute;
    }
    public function upazillaTotalSections($request)
    {
        $institutesArr          = Institute::on('db_read')->where('upazila_uid', $request->id)->pluck('eiin')->toArray();

        $totalUpazillaStudent   = Section::on('db_read')->whereIn('eiin', $institutesArr)->count();

        return $totalUpazillaStudent;
    }

    public function searchInstitute($request)
    {
        $search = $request->eiin;

        $institute = Institute::on('db_read')->select('id', 'eiin')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('eiin', 'like', "%$search%");
                }
            })
            ->first();

        return $institute;
    }

    public function boards()
    {
        $boards = Board::on('db_read')->select('uid', 'board_name_bn', 'board_name_en', 'board_short_name')->get();
        return $boards;
    }
    public function getBoardByDistrictId($districtId)
    {
        $boards = BoardToDistrict::on('db_read')->where('district_uid', $districtId)->first();
        return $boards;
    }

    public function getExamPaper()
    {
        $token = $this->generateToken();
        $postFields = json_encode([
            'secure_token' => $token
        ]);

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('POST', 'https://exam.noipunno.gov.bd/api/master/login', [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => $postFields,
                'verify' => false
            ]);

            return $response->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
             // dd($e->getResponse()->getBody()->getContents());
            return $e->getResponse()->getBody()->getContents();
        }
    }


    public function generateToken()
    {
        $user = app('sso-auth')->user();
        $institute = Institute::where('eiin', $user->eiin)->orWhere('caid', $user->eiin)->first();
        $knownPart = env('SECRET_KEY');
        $variablePart = "{$institute->uid}-{$institute->eiin}";
        $hashOfKnownPart = Hash::make($knownPart);
        $encryptedVariablePart = Crypt::encryptString($variablePart);
        $secureToken = [
            'hash' => $hashOfKnownPart,
            'encrypted' => $encryptedVariablePart,
            'institute_name' => $institute->institute_name
        ];
        return $secureToken;
    }

    public function encryptData($data, $key)
    {
        $iv = random_bytes(16); // Generate a random IV
        $cipher = "aes-256-cbc"; // Cipher method
        $encryptedData = openssl_encrypt(json_encode($data), $cipher, $key, 0, $iv);
        return base64_encode($iv . $encryptedData); // Encode IV and encrypted data to base64
    }

    public function decryptData($encryptedData, $key)
    {

        $cipher = "aes-256-cbc"; // Cipher method
        $data = base64_decode($encryptedData); // Decode base64
        $iv = substr($data, 0, 16); // Extract the IV
        $encryptedData = substr($data, 16); // Extract the encrypted data
        $decryptedData = openssl_decrypt($encryptedData, $cipher, $key, 0, $iv);
        return json_decode($decryptedData, true); // Decode JSON to array
    }
}
