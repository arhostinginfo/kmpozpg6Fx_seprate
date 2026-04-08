<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\Gallary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GallaryController extends Controller
{
    public function list()
    {
        $gallaries = Gallary::where('is_deleted',0)
                ->orderBy('id', 'desc')
                ->get();
        return view('superadm.gallary.list', compact('gallaries'));
    }

    public function add()
    {
        return view('superadm.gallary.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type_attachment' => 'required|max:255',
            'attachment' => 'nullable|mimes:jpeg,jpg,png,gif,mp4,mov,avi,mkv,webm|max:102400',
        ]);

        $data = [
            'name' => $request->name,
            'type_attachment' => $request->type_attachment,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('gallay', 'public');
        }

        Gallary::create($data);

        return redirect()->route('gallary.list')->with('success', 'Gallery item added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $gallaries = Gallary::where('id', $id)->first();
        return view('superadm.gallary.edit', compact('gallaries','encodedId'));
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

        $gallary = Gallary::where('id', $id)->first();
        if ($request->hasFile('attachment')) {
            if ($gallary->attachment && Storage::disk('public')->exists($gallary->attachment)) {
                Storage::disk('public')->delete($gallary->attachment);
            }
            $data['attachment'] = $request->file('attachment')->store('gallay', 'public');
        }

        $gallary->update($data);

        return redirect()->route('gallary.list')->with('success', 'Gallery item updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required', 'is_active' => 'required|in:0,1']);
        $id = base64_decode($request->id);
        Gallary::where('id', $id)->update(['is_active' => $request->is_active]);
        return redirect()->route('gallary.list')->with('success', 'Gallery status updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $gallary = Gallary::findOrFail($id);
        if ($gallary->attachment && Storage::disk('public')->exists($gallary->attachment)) {
            Storage::disk('public')->delete($gallary->attachment);
        }
        Gallary::where('id', $id)->update(['is_deleted' => 1]);
        return redirect()->route('gallary.list')->with('success', 'Gallery item deleted successfully.');
    }
}
