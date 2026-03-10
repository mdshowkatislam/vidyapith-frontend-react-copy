<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\Upazilla;

class UpazillaController extends Controller
{
    public function index(Request $request)
    {
        $upazilla_data = '';
        $districts = District::on('db_read')->get();
        $upazillaList = Upazilla::on('db_read')->whereNull('updated_at')->get();
        return view('admin.upazila.edit', compact('upazillaList', 'upazilla_data', 'districts'));
    }
    public function edit(Request $request, $id)
    {
        $upazilla_data = Upazilla::on('db_read')->where('uid', $id)->first();
        $districts = District::on('db_read')->get();
        $upazillaList = Upazilla::on('db_read')->get();
        return view('admin.upazila.edit', compact('upazillaList', 'upazilla_data', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $upazilla = Upazilla::where('uid', $id)->first();
        $upazilla->upazila_name_bn = $request->upazila_name_bn;
        $upazilla->district_id = $request->district_id;
        $upazilla->save();
        return redirect()->route('upazila-index');
    }
}
