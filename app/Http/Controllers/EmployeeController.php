<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data['title'] = 'Employee';
        $this->data['breadcrumb'] = 'Index';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $this->data['employees'] = Employee::with('company')->get();

        $this->data['companies'] = Company::all();
        // dump($this->data['companies']);

        return Inertia::render('Employee/Index', $this->data);
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
    public function store(EmployeeRequest $request)
    {
        //
        // dd($request->all());
        DB::beginTransaction();

        try {
            Employee::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'company_id' => $request->company_id,
                'phone' => $request->phone
            ]);

            DB::commit();

            return redirect()->route('employee.index')->with('success', 'Successfully Create New Employee');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('employee.index')->with('error', 'Something error, please contact administrator!');
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
    public function update(EmployeeRequest $request, string $id)
    {
        //
        // Mulai transaksi database
        DB::beginTransaction();

        // dd($request->all());

        try {
            // Dekripsi ID yang diterima
            $id = Crypt::decrypt($id);

            // Temukan data perusahaan berdasarkan ID
            $data = Employee::findOrFail($id);

            // Perbarui data perusahaan dengan input yang diberikan
            $data->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'company_id' => $request->company_id,
                'phone' => $request->phone
            ]);

            // Commit transaksi database
            DB::commit();

            // Redirect ke halaman index perusahaan dengan pesan sukses
            return redirect()->route('employee.index')->with('success', 'Successfully Update Employee');
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollBack();

            // Redirect ke halaman index perusahaan dengan pesan kesalahan
            return redirect()->route('employee.index')->with('error', 'Something error, please contact administrator!');
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
            $data = Employee::findOrFail($id);
            $data->delete();

            DB::commit();

            return redirect()->route('employee.index')->with('success', 'Successfully Delete Employee');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('employee.index')->with('error', 'Something error, please contact administrator!');
        }
    }
}