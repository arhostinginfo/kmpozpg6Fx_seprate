<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\Officers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OfficerController extends Controller
{
    public function list()
    {
        $officers = Officers::where('is_deleted',0)
                ->orderBy('id', 'desc')
                ->get();
        return view('superadm.officers.list', compact('officers'));
    }

    public function add()
    {
        return view('superadm.officers.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'designation' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => ['required','string','max:15'],
            'email' => 'required|email|max:255',
            'type' => 'required|max:255',
            'sequence_officer' => 'required|max:255',
            'sequence_general' => 'required|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:3072'
        ]);

        $data = [
            'designation' => $request->designation,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'type' => $request->type,
            'sequence_officer' => $request->sequence_officer,
            'sequence_general' => $request->sequence_general,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('officers', 'public');
        }

        Officers::create($data);

        return redirect()->route('officers.list')->with('success', 'Member added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $officer = Officers::where('id', $id)->first();
        return view('superadm.officers.edit', compact('officer','encodedId'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'encodedId' => 'required',
            'designation' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'mobile' => ['required','string','max:15'],
            'email' => 'required|email|max:255',
            'type' => 'required|max:255',
            'sequence_officer' => 'required|max:255',
            'sequence_general' => 'required|max:255',
        ]);

        $id = base64_decode($request->encodedId);
        $data = [
            'designation' => $request->designation,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'type' => $request->type,
            'sequence_officer' => $request->sequence_officer,
            'sequence_general' => $request->sequence_general,
            'is_active' => $request->is_active ?? 1,
        ];

        $officer = Officers::where('id', $id)->first();
        if ($request->hasFile('photo')) {
            if ($officer->photo && Storage::disk('public')->exists($officer->photo)) {
                Storage::disk('public')->delete($officer->photo);
            }
            $data['photo'] = $request->file('photo')->store('officers', 'public');
        }

        $officer->update($data);

        return redirect()->route('officers.list')->with('success', 'Member updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required', 'is_active' => 'required|in:0,1']);
        $id = base64_decode($request->id);
        Officers::where('id', $id)->update(['is_active' => $request->is_active]);
        return redirect()->route('officers.list')->with('success', 'Member status updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $officer = Officers::findOrFail($id);
        if ($officer->photo && Storage::disk('public')->exists($officer->photo)) {
            Storage::disk('public')->delete($officer->photo);
        }
        Officers::where('id', $id)->update(['is_deleted' => 1]);
        return redirect()->route('officers.list')->with('success', 'Member deleted successfully.');
    }
}
