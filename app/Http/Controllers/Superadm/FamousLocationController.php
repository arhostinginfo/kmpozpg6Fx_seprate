<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\Famouslocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FamousLocationController extends Controller
{
    public function list()
    {
        $famouslocations = Famouslocations::where('is_deleted',0)
                ->orderBy('id', 'desc')
                ->get();
        return view('superadm.famous-locations.list', compact('famouslocations'));
    }

    public function add()
    {
        return view('superadm.famous-locations.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:5255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:3072'
        ]);

        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('famouslocations', 'public');
        }

        Famouslocations::create($data);

        return redirect()->route('famous-locations.list')->with('success', 'Famous location added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $famouslocations = Famouslocations::where('id', $id)->first();
        return view('superadm.famous-locations.edit', compact('famouslocations','encodedId'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'encodedId' => 'required',
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:5255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:3072'
        ]);

        $id = base64_decode($request->encodedId);
        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'is_active' => $request->is_active ?? 1,
        ];

        $location = Famouslocations::where('id', $id)->first();
        if ($request->hasFile('photo')) {
            if ($location->photo && Storage::disk('public')->exists($location->photo)) {
                Storage::disk('public')->delete($location->photo);
            }
            $data['photo'] = $request->file('photo')->store('famouslocations', 'public');
        }

        $location->update($data);

        return redirect()->route('famous-locations.list')->with('success', 'Famous location updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required', 'is_active' => 'required|in:0,1']);
        $id = base64_decode($request->id);
        Famouslocations::where('id', $id)->update(['is_active' => $request->is_active]);
        return redirect()->route('famous-locations.list')->with('success', 'Status updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $location = Famouslocations::findOrFail($id);
        if ($location->photo && Storage::disk('public')->exists($location->photo)) {
            Storage::disk('public')->delete($location->photo);
        }
        Famouslocations::where('id', $id)->update(['is_deleted' => 1]);
        return redirect()->route('famous-locations.list')->with('success', 'Famous location deleted successfully.');
    }
}
