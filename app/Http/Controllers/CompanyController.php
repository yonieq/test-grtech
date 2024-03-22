<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data['title'] = 'Company';
        $this->data['breadcrumb'] = 'Index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $this->data['companies'] = Company::all();

        // dd($this->data['companies']);

        return Inertia::render('Company/Index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        //
        DB::beginTransaction();

        try {

            // Handle image upload
            if ($request->hasFile('logo')) {
                // Get the uploaded file
                $image = $request->file('logo');
                // Generate a unique filename
                $filename = uniqid() . '_' . $image->getClientOriginalName();
                // Store the image in the storage/images/users directory
                $image->storeAs('public/image', $filename);
                // Set the image filename in the request data
                $data['logo'] = $filename;
            }

            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['website'] = $request->website;

            Company::create($data);

            DB::commit();

            return redirect()->route('company.index')->with('success', 'Successfully Create New Company');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('company.index')->with('error', 'Something error, please contact administrator!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        // dd($request->all());

        try {
            // Dekripsi ID yang diterima
            $id = Crypt::decrypt($id);

            // Temukan data perusahaan berdasarkan ID
            $company = Company::findOrFail($id);

            // Ekstrak nama file gambar lama
            $filename = basename($company->logo);

            if ($request->hasFile('logo')) {
                // Jika ada file logo yang diunggah, hapus gambar lama jika ada
                if ($filename && Storage::disk('public')->exists("image/{$filename}")) {
                    Storage::disk('public')->delete("image/{$filename}");
                }

                // Dapatkan file yang diunggah
                $logo = $request->file('logo');
                // Generate nama file unik
                $filename = uniqid() . '_' . $logo->getClientOriginalName();
                // Simpan gambar di direktori penyimpanan
                $logo->storeAs('public/image', $filename);
            }

            // Perbarui data perusahaan dengan input yang diberikan
            $company->update([
                'name' => $request->name ?? $request->name,
                'email' => $request->email ?? $request->email,
                'website' => $request->website ?? $request->website,
                'logo' => $filename ?? $company->logo,
            ]);

            // Commit transaksi database
            DB::commit();

            // Redirect ke halaman index perusahaan dengan pesan sukses
            return redirect()->route('company.index')->with('success', 'Successfully Update Company');
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();

            // Redirect ke halaman index perusahaan dengan pesan kesalahan
            return redirect()->route('company.index')->with('error', 'Something error, please contact administrator!');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        DB::beginTransaction();

        try {
            $id = Crypt::decrypt($id);
            $data = Company::findOrFail($id);

            // Extract nama file dari URL logo
            $logoFileName = basename($data->logo);

            // Hapus gambar terlebih dahulu jika ada
            if ($logoFileName && Storage::disk('public')->exists("image/{$logoFileName}")) {
                Storage::disk('public')->delete("image/{$logoFileName}");
            }
            $data->delete();

            DB::commit();

            return redirect()->route('company.index')->with('success', 'Successfully Delete Company');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('company.index')->with('error', 'Something error, please contact administrator!');
        }
    }
}