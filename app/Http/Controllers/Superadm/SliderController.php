<?php

namespace App\Http\Controllers\Superadm;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function list()
    {
        $slider = Slider::where('is_deleted',0)
                ->orderBy('id', 'desc')
                ->get();
        return view('superadm.slider.list', compact('slider'));
    }

    public function add()
    {
        return view('superadm.slider.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:3072' // max 3MB
        ]);

        $data = [
            'name' => $request->name,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('slider', 'public');
        }

        Slider::create($data);

        return redirect()->route('slider.list')->with('success', 'Officer added successfully.');
    }

    public function edit($encodedId)
    {
        $id = base64_decode($encodedId);
        $slider = Slider::where('id', $id)->first();
        return view('superadm.slider.edit', compact('slider','encodedId'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'encodedId' => 'required',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:3072'
        ]);


            $id = base64_decode($request->encodedId);
            $data = [
                'name' => $request->name,
                'is_active' => $request->is_active ?? 1,
            ];

        $officer = Slider::where('id', $id)->first();
        if ($request->hasFile('photo')) {
            if ($officer->photo && Storage::disk('public')->exists($officer->photo)) {
                Storage::disk('public')->delete($officer->photo);
            }
            $data['photo'] = $request->file('photo')->store('slider', 'public');
        }

        $officer->update($data);

        return redirect()->route('slider.list')->with('success', 'Officer updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $request->validate(['id' => 'required', 'is_active' => 'required|in:0,1']);
        $id = base64_decode($request->id);
        Slider::where('id', $id)->update(['is_active' => $request->is_active]);
        return redirect()->route('slider.list')->with('success', 'Status updated successfully.');
    }

    public function delete(Request $request)
    {
        $id = base64_decode($request->encodedId);
        $officer = Slider::findOrFail($id);
        // delete file from storage if present
        if ($officer->photo && Storage::disk('public')->exists($officer->photo)) {
            Storage::disk('public')->delete($officer->photo);
        }

        $officer = Slider::where ('id', $id)->update(['is_deleted' => 1]);

        return redirect()->route('slider.list')->with('success', 'Officer deleted successfully.');
    }
}
