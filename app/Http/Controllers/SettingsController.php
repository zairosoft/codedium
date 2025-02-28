<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Models\CompanyLang;
use App\Models\Province;


class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'lock']);
        $this->middleware('permission:setting view', ['only' => ['general', 'company', 'system']]);
        $this->middleware('permission:setting create', ['only' => ['create', 'store']]);
        $this->middleware('permission:setting update', ['only' => ['update', 'edit']]);
        $this->middleware('permission:setting delete', ['only' => ['destroy']]);
    }

    public function general()
    {
        return view('settings.general', []);
    }

    public function updateGeneral(Request $request)
    {
        if (!empty($request->image)) {
            $imageName = 'logo.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/assets/images'), $imageName);
            return response()->json(['message' => 'Image uploaded successfully']);
        }

        if (!empty($request->favicon)) {
            $imageName = 'favicon.' . $request->favicon->getClientOriginalExtension();
            $request->favicon->move(public_path('/'), $imageName);
            return response()->json(['message' => 'Image uploaded successfully']);
        }

        Cache::forget('key');
    }

    public function company()
    {
        $companies = Cache::remember('company', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Company::join('company_langs', 'company_langs.company_id', '=', 'companies.id')
                ->offset(0)
                ->limit(10)
                ->get();
        });
        return view('settings.company.index', ['companies' => $companies]);
    }

    public function companyCreate()
    {
        $provinces = Cache::remember('province', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Province::get();
        });
        return view('settings.company.create', ['provinces' => $provinces]);
    }

    public function companyStore(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
            ],
            [
                'email' => trans('validation.required'),
                'name' => trans('validation.required'),
            ]
        );

        $data = ['email' => $request->email];
        if (!empty($request->tel))
            $data += ['tel' => $request->tel];
        if (!empty($request->mobile))
            $data += ['mobile' => $request->mobile];
        if (!empty($request->website))
            $data += ['website' => $request->website];
        if (!empty($request->tax_id))
            $data += ['tax_id' => $request->tax_id];
        if (!empty($request->company_id))
            $data += ['company_id' => $request->company_id];
        if (!empty($request->image)) {
            $imageName = Auth::user()->id . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/assets/images/companies'), $imageName);
            $data += ['img' => $imageName];
        }

        $company = Company::create($data);
        $companyLang = [
            'company_id' => $company->id,
            'code' => 'th',
            'name' => $request->name,
        ];
        if (!empty($request->address))
            $companyLang += ['address' => $request->address];
        if (!empty($request->district))
            $companyLang += ['district' => $request->district];
        if (!empty($request->province))
            $companyLang += ['province' => $request->province];
        if (!empty($request->zip))
            $companyLang += ['zip' => $request->zip];
        CompanyLang::create($companyLang);
        Cache::forget('company');
        return redirect('settings/company')->withSuccess('บันทึกสำเร็จ');
    }

    public function companyEdit($id)
    {
        $company = Company::where('id', (int) $id)->first();
        $lang = CompanyLang::where('company_id', (int) $id)->first();
        $provinces = Cache::remember('province', now()->addMinutes((int)env('CACHE_EXPIRE')), function () {
            return Province::get();
        });
        return view('settings.company.edit', [
            'provinces' => $provinces,
            'company' => $company,
            'lang' => $lang
        ]);
    }

    public function companyUpdate(Request $request)
    {
        $request->validate(
            [
                'comid' => 'required',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
            ],
            [
                'email' => trans('validation.required'),
                'name' => trans('validation.required'),
            ]
        );
        $data = ['email' => $request->email];
        if (!empty($request->tel))
            $data += ['tel' => $request->tel];
        if (!empty($request->mobile))
            $data += ['mobile' => $request->mobile];
        if (!empty($request->website))
            $data += ['website' => $request->website];
        if (!empty($request->tax_id))
            $data += ['tax_id' => $request->tax_id];
        if (!empty($request->company_id))
            $data += ['company_id' => $request->company_id];
        if (!empty($request->image)) {
            if ($request->img != null) {
                @unlink(public_path('/assets/images/companies/') . $request->img);
            }
            $imageName = Auth::user()->id . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('/assets/images/companies'), $imageName);
            $data += ['img' => $imageName];
        }
        Company::where('id', $request->comid)->update($data);
        $companyLang = [
            'name' => $request->name,
        ];
        if (!empty($request->address))
            $companyLang += ['address' => $request->address];
        if (!empty($request->district))
            $companyLang += ['district' => $request->district];
        if (!empty($request->province))
            $companyLang += ['province' => $request->province];
        if (!empty($request->zip))
            $companyLang += ['zip' => $request->zip];
        CompanyLang::where([
            "company_id" => $request->comid,
            "code" => "th"
        ])->update($companyLang);
        Cache::forget('company');
        return redirect('settings/company')->withSuccess('บันทึกสำเร็จ');
    }

    public function companyView($id)
    {
        $company = Company::where('id', (int) $id)->first();
        $lang = CompanyLang::where('company_id', (int) $id)->first();
        return view('settings.company.view', [
            'company' => $company,
            'lang' => $lang
        ]);
    }

    public function companyDestroy(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        if ($request->id != 1) {
            $company = Company::find($request->id);
            if ($company->img != null) {
                @unlink(public_path('/assets/images/companies/') . $company->img);
            }
            $company->delete();

            $lang = CompanyLang::where('company_id',$request->id);
            $lang->delete();
            Cache::forget('company');
            echo json_encode(["message" => "success"]);
        } else {
            echo json_encode(["message" => "errors"]);
        }
    }
}
