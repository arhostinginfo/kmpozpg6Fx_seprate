<?php

namespace App\Http\Controllers\Superadm;

use App\Http\Controllers\Controller;
use App\Models\TaxDemand;
use Illuminate\Http\Request;
use Exception;

class TaxDemandController extends Controller
{
    public function index()
    {
        try {
            $demands = TaxDemand::where('is_deleted', 0)
                ->orderByRaw("FIELD(tax_type,'ghar_patti','paani_patti','other')")
                ->orderByRaw("FIELD(year_type,'chalu','magil')")
                ->get();
            return view('superadm.tax-demand.list', compact('demands'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('superadm.tax-demand.create');
    }

    public function save(Request $req)
    {
        $req->validate([
            'tax_type'         => 'required|in:ghar_patti,paani_patti,other',
            'year_type'        => 'required|in:magil,chalu',
            'demand_amount'    => 'required|numeric|min:0',
            'collected_amount' => 'required|numeric|min:0',
        ]);

        try {
            $percentage = $req->demand_amount > 0
                ? round(($req->collected_amount / $req->demand_amount) * 100, 2)
                : 0;

            TaxDemand::create([
                'tax_type'         => $req->tax_type,
                'year_type'        => $req->year_type,
                'demand_amount'    => $req->demand_amount,
                'collected_amount' => $req->collected_amount,
                'percentage'       => $percentage,
            ]);

            return redirect()->route('tax-demand.list')->with('success', 'कर मागणी यशस्वीरित्या जोडली.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function edit($encodedId)
    {
        try {
            $id   = base64_decode($encodedId);
            $data = TaxDemand::findOrFail($id);
            return view('superadm.tax-demand.edit', compact('data', 'encodedId'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function update(Request $req)
    {
        $req->validate([
            'id'               => 'required',
            'tax_type'         => 'required|in:ghar_patti,paani_patti,other',
            'year_type'        => 'required|in:magil,chalu',
            'demand_amount'    => 'required|numeric|min:0',
            'collected_amount' => 'required|numeric|min:0',
            'is_active'        => 'required|in:0,1',
        ]);

        try {
            $id = base64_decode($req->id);

            $percentage = $req->demand_amount > 0
                ? round(($req->collected_amount / $req->demand_amount) * 100, 2)
                : 0;

            TaxDemand::where('id', $id)->update([
                'tax_type'         => $req->tax_type,
                'year_type'        => $req->year_type,
                'demand_amount'    => $req->demand_amount,
                'collected_amount' => $req->collected_amount,
                'percentage'       => $percentage,
                'is_active'        => $req->is_active,
            ]);

            return redirect()->route('tax-demand.list')->with('success', 'कर मागणी अपडेट झाली.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function delete(Request $req)
    {
        try {
            $req->validate(['id' => 'required']);
            $id = base64_decode($req->id);
            TaxDemand::where('id', $id)->update(['is_deleted' => 1]);
            return redirect()->route('tax-demand.list')->with('success', 'कर मागणी हटवली.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $req)
    {
        try {
            $id = base64_decode($req->id);
            TaxDemand::where('id', $id)->update(['is_active' => $req->is_active]);
            return redirect()->route('tax-demand.list')->with('success', 'Status updated.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}
