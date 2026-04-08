<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\Yojna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class YojnaController extends Controller
{
    public function list()
    {
        $yojna = Yojna::where('is_deleted',0)
                ->orderBy('id', 'desc')
                ->get();
        return view('superadm.yojna.list', compact('yojna'));
    }

    public function add()
    {
        return view('superadm.yojna.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_attachment' => 'required|max:255',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,pdf,doc,docx,xls,xlsx|max:3072',
            'attachment_link'  => 'nullable|url|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'type_attachment' => $request->type_attachment,
            'attachment_link' => $request->attachment_link,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('yojna', 'public');
        }

        Yojna::create($data);

        return redirect()->route('yojna.list')->with('success', 'Yojna added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $yojna = Yojna::where('id', $id)->first();
        return view('superadm.yojna.edit', compact('yojna','encodedId'));
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
            'attachment_link' => $request->attachment_link,
            'is_active' => $request->is_active ?? 1,
        ];

        $yojna = Yojna::where('id', $id)->first();
        if ($request->hasFile('attachment')) {
            if ($yojna->attachment && Storage::disk('public')->exists($yojna->attachment)) {
                Storage::disk('public')->delete($yojna->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('yojna', 'public');
        }

        $yojna->update($data);

        return redirect()->route('yojna.list')->with('success', 'Yojna updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required', 'is_active' => 'required|in:0,1']);
        $id = base64_decode($request->id);
        Yojna::where('id', $id)->update(['is_active' => $request->is_active]);
        return redirect()->route('yojna.list')->with('success', 'Yojna status updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $yojna = Yojna::findOrFail($id);
        if ($yojna->attachment && Storage::disk('public')->exists($yojna->attachment)) {
            Storage::disk('public')->delete($yojna->attachment);
        }
        Yojna::where('id', $id)->update(['is_deleted' => 1]);
        return redirect()->route('yojna.list')->with('success', 'Yojna deleted successfully.');
    }
}
