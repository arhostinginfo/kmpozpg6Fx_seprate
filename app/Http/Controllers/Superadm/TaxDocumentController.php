<?php

namespace App\Http\Controllers\Superadm;

use App\Http\Controllers\Controller;
use App\Models\TaxDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class TaxDocumentController extends Controller
{
    public function index()
    {
        try {
            $documents = TaxDocument::where('is_deleted', 0)
                ->orderByRaw("FIELD(tax_type,'ghar_patti','paani_patti','other')")
                ->orderByRaw("FIELD(document_type,'view_pdf','payment_qr')")
                ->get();
            return view('superadm.tax-document.list', compact('documents'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('superadm.tax-document.create');
    }

    public function save(Request $req)
    {
        $req->validate([
            'tax_type'      => 'required|in:ghar_patti,paani_patti,other',
            'document_type' => 'required|in:view_pdf,payment_qr',
            'file'          => 'required|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:5120',
        ]);

        try {
            $file          = $req->file('file');
            $originalName  = $file->getClientOriginalName();
            $filePath      = $file->store('tax_documents', 'public');

            TaxDocument::create([
                'tax_type'      => $req->tax_type,
                'document_type' => $req->document_type,
                'file_path'     => $filePath,
                'original_name' => $originalName,
            ]);

            return redirect()->route('tax-document.list')->with('success', 'दस्तऐवज यशस्वीरित्या अपलोड झाला.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($encodedId)
    {
        try {
            $id   = base64_decode($encodedId);
            $data = TaxDocument::findOrFail($id);
            return view('superadm.tax-document.edit', compact('data', 'encodedId'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $req)
    {
        $req->validate([
            'id'            => 'required',
            'tax_type'      => 'required|in:ghar_patti,paani_patti,other',
            'document_type' => 'required|in:view_pdf,payment_qr',
            'is_active'     => 'required|in:0,1',
            'file'          => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:5120',
        ]);

        try {
            $id   = base64_decode($req->id);
            $doc  = TaxDocument::findOrFail($id);

            $updateData = [
                'tax_type'      => $req->tax_type,
                'document_type' => $req->document_type,
                'is_active'     => $req->is_active,
            ];

            if ($req->hasFile('file')) {
                // Delete old file
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }
                $file                       = $req->file('file');
                $updateData['file_path']     = $file->store('tax_documents', 'public');
                $updateData['original_name'] = $file->getClientOriginalName();
            }

            TaxDocument::where('id', $id)->update($updateData);

            return redirect()->route('tax-document.list')->with('success', 'दस्तऐवज अपडेट झाला.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function delete(Request $req)
    {
        try {
            $req->validate(['id' => 'required']);
            $id  = base64_decode($req->id);
            $doc = TaxDocument::find($id);
            if ($doc && $doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }
            TaxDocument::where('id', $id)->update(['is_deleted' => 1]);
            return redirect()->route('tax-document.list')->with('success', 'दस्तऐवज हटवला.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $req)
    {
        try {
            $id = base64_decode($req->id);
            TaxDocument::where('id', $id)->update(['is_active' => $req->is_active]);
            return redirect()->route('tax-document.list')->with('success', 'Status updated.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}
