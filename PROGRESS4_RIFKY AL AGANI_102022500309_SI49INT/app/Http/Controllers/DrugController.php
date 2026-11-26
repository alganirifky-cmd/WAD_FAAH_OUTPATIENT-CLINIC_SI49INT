<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DrugController extends Controller
{
    // READ: daftar + pencarian
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $drugs = Drug::query()
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('drug_name', 'like', "%{$search}%")
                      ->orWhere('drug_code', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('drugs.index', compact('drugs', 'search'));
    }

    // CREATE: tampilkan form kosong
    public function create()
    {
        $drug = new Drug(['preparation_form' => 'Tablet']);
        return view('drugs.form', compact('drug'));
    }

    // CREATE: simpan data
    public function store(Request $request)
    {
        Drug::create($this->validated($request));

        return redirect()->route('drugs.index')->with('success', 'Data berhasil disimpan.');
    }

    // UPDATE: tampilkan form terisi
    public function edit(Drug $drug)
    {
        return view('drugs.form', compact('drug'));
    }

    // UPDATE: simpan perubahan
    public function update(Request $request, Drug $drug)
    {
        $drug->update($this->validated($request, $drug));

        return redirect()->route('drugs.index')->with('success', 'Data berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Drug $drug)
    {
        $drug->delete();

        return redirect()->route('drugs.index')->with('success', 'Data berhasil dihapus.');
    }

    private function validated(Request $request, ?Drug $drug = null): array
    {
        return $request->validate([
            'drug_code'        => ['required', 'string', 'max:50',
                                   Rule::unique('drugs', 'drug_code')->ignore($drug?->id)],
            'drug_name'        => ['required', 'string', 'max:150'],
            'dosage'           => ['required', 'string', 'max:50'],
            'category'         => ['required', 'string', 'max:100'],
            'preparation_form' => ['required', Rule::in(Drug::FORMS)],
            'manufacturer'     => ['required', 'string', 'max:150'],
            'bpom_number'      => ['nullable', 'string', 'max:50'],
            'stock'            => ['required', 'integer', 'min:0'],
            'unit'             => ['required', 'string', 'max:30'],
            'price'            => ['required', 'numeric', 'min:0'],
            'expiry_date'      => ['required', 'date'],
        ]);
    }
}
