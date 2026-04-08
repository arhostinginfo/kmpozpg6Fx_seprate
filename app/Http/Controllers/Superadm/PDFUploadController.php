<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\PDFUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PDFUploadController extends Controller
{
    public function list()
    {
        $gallaries = PDFUpload::where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->get();
        return view('superadm.pdfupload.list', compact('gallaries'));
    }

    public function add()
    {
        return view('superadm.pdfupload.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_attachment' => 'required|max:255',
            'attachment' => 'nullable|mimes:pdf|max:102400',
            'attachment_link' => 'nullable|url|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'type_attachment' => $request->type_attachment,
            'attachment_link' => $request->attachment_link,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('pdfupload', 'public');
        }

        PDFUpload::create($data);

        return redirect()->route('pdfupload.list')->with('success', 'PDF added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $gallaries = PDFUpload::where('id', $id)->first();
        return view('superadm.pdfupload.edit', compact('gallaries','encodedId'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'encodedId' => 'required',
            'name' => 'required|string|max:255',
            'type_attachment' => 'required|max:255',
        ]);

        $id = base64_decode($request->encodedId);
        $data = [
            'name' => $request->name,
            'type_attachment' => $request->type_attachment,
            'is_active' => $request->is_active ?? 1,
        ];

        $pdf = PDFUpload::where('id', $id)->first();
        if ($request->hasFile('attachment')) {
            if ($pdf->attachment && Storage::disk('public')->exists($pdf->attachment)) {
                Storage::disk('public')->delete($pdf->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('pdfupload', 'public');
        }

        $pdf->update($data);

        return redirect()->route('pdfupload.list')->with('success', 'PDF updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required', 'is_active' => 'required|in:0,1']);
        $id = base64_decode($request->id);
        PDFUpload::where('id', $id)->update(['is_active' => $request->is_active]);
        return redirect()->route('pdfupload.list')->with('success', 'PDF status updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $pdf = PDFUpload::findOrFail($id);
        if ($pdf->attachment && Storage::disk('public')->exists($pdf->attachment)) {
            Storage::disk('public')->delete($pdf->attachment);
        }
        PDFUpload::where('id', $id)->update(['is_deleted' => 1]);
        return redirect()->route('pdfupload.list')->with('success', 'PDF deleted successfully.');
    }
}
