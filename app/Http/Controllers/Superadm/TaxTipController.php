<?php

namespace App\Http\Controllers\Superadm;

use App\Http\Controllers\Controller;
use App\Models\TaxTip;
use Illuminate\Http\Request;
use Exception;

class TaxTipController extends Controller
{
    public function index()
    {
        try {
            $tips = TaxTip::where('is_deleted', 0)
                ->orderBy('id', 'desc')
                ->get();
            return view('superadm.tax-tip.list', compact('tips'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('superadm.tax-tip.create');
    }

    public function save(Request $req)
    {
        $req->validate([
            'tip_text' => 'required|string|max:1000',
        ]);

        try {
            TaxTip::create([
                'tip_text'  => $req->tip_text,
                'is_active' => 1,
            ]);
            return redirect()->route('tax-tip.list')->with('success', 'कर टीप यशस्वीरित्या जोडली.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($encodedId)
    {
        try {
            $id   = base64_decode($encodedId);
            $data = TaxTip::findOrFail($id);
            return view('superadm.tax-tip.edit', compact('data', 'encodedId'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $req)
    {
        $req->validate([
            'id'        => 'required',
            'tip_text'  => 'required|string|max:1000',
            'is_active' => 'required|in:0,1',
        ]);

        try {
            $id = base64_decode($req->id);
            TaxTip::where('id', $id)->update([
                'tip_text'  => $req->tip_text,
                'is_active' => $req->is_active,
            ]);
            return redirect()->route('tax-tip.list')->with('success', 'कर टीप अपडेट झाली.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function delete(Request $req)
    {
        try {
            $req->validate(['id' => 'required']);
            $id = base64_decode($req->id);
            TaxTip::where('id', $id)->update(['is_deleted' => 1]);
            return redirect()->route('tax-tip.list')->with('success', 'कर टीप हटवली.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $req)
    {
        try {
            $id = base64_decode($req->id);
            TaxTip::where('id', $id)->update(['is_active' => $req->is_active]);
            return redirect()->route('tax-tip.list')->with('success', 'Status updated.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}
