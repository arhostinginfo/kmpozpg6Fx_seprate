<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\PDFUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PDFUploadController extends Controller
{


    public function __construct()
   {
   }

    public function list()
    {
        $gallaries = PDFUpload::where([
					'is_deleted'=>0,
				])
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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type_attachment' => 'required|max:255', //Sadsya or officer
            'attachment' => 'nullable|mimes:pdf|max:102400',
            'attachment_link'  => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('attachment')) {
            // Save file to storage/app/public/officers

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'type_attachment' => 'required|max:255', //Sadsya or officer
                'attachment' => 'nullable|mimes:pdf|max:102400',
            ]);

            $data['attachment'] = $request->file('attachment')->store('/pdfupload', 'public');
        }
        PDFUpload::create($data);

        return redirect()->route('pdfupload.list')->with('success', 'Officer added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $gallaries = PDFUpload::where('id', $id)->first();
        return view('superadm.pdfupload.edit', compact('gallaries','encodedId'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'encodedId' => 'required',
            'name' => 'required|string|max:255',
            'type_attachment' => 'required|max:255', //Sadsya or officer
        ]);


            $id = base64_decode($request->encodedId);
            $data = [
                'designation' => $request->input('designation'),
                'name' => $request->input('name'),
                'type_attachment' => $request->input('type_attachment'), 
                // 'is_active' => $req->is_active
            ];

        $officer = PDFUpload::where('id', $id)->first();
        if ($request->hasFile('attachment')) {
            // remove old if exists
            if ($officer->attachment && Storage::disk('public')->exists($officer->attachment)) {
                Storage::disk('public')->delete($officer->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('/pdfupload',  'public');
        }

        $officer->update($data);

        return redirect()->route('pdfupload.list')->with('success', 'Officer updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $officer = PDFUpload::findOrFail($id);
        // delete file from storage if present
        if ($officer->attachment && Storage::disk('public')->exists($officer->attachment)) {
            Storage::disk('public')->delete($officer->attachment);
        }

        $officer = PDFUpload::where ('id', $id)->update(['is_deleted' => 1]);
        return redirect()->route('pdfupload.list')->with('success', 'Officer deleted successfully.');
    }
}

