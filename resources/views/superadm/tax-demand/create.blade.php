@extends('superadm.layout.master')

@section('title', 'Add Tax Demand')

@section('content')
<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4 class="mb-3">नवीन कर मागणी जोडा</h4>

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('tax-demand.save') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>कराचा प्रकार <span class="text-danger">*</span></label>
                        <select name="tax_type" class="form-control" required>
                            <option value="">-- निवडा --</option>
                            <option value="ghar_patti"  {{ old('tax_type') == 'ghar_patti'  ? 'selected' : '' }}>घरपट्टी कर</option>
                            <option value="paani_patti" {{ old('tax_type') == 'paani_patti' ? 'selected' : '' }}>पाणीपट्टी कर</option>
                            <option value="other"       {{ old('tax_type') == 'other'       ? 'selected' : '' }}>गाळाभाडे / व्यवसायकर / इतर</option>
                        </select>
                        @error('tax_type')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>वर्ष प्रकार <span class="text-danger">*</span></label>
                        <select name="year_type" class="form-control" required>
                            <option value="">-- निवडा --</option>
                            <option value="chalu" {{ old('year_type') == 'chalu' ? 'selected' : '' }}>चालू वर्ष</option>
                            <option value="magil" {{ old('year_type') == 'magil' ? 'selected' : '' }}>मागील वर्ष</option>
                        </select>
                        @error('year_type')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>मागणी रक्कम (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="demand_amount" step="0.01" min="0"
                            class="form-control" value="{{ old('demand_amount', 0) }}" required>
                        @error('demand_amount')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group mb-3">
                        <label>वसूल रक्कम (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="collected_amount" step="0.01" min="0"
                            class="form-control" value="{{ old('collected_amount', 0) }}" required>
                        @error('collected_amount')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>

                    <div class="alert alert-info py-2 mb-3" style="font-size:0.85rem;">
                        <i class="mdi mdi-information-outline"></i>
                        टक्केवारी आपोआप गणली जाते: (वसूल / मागणी) × 100
                    </div>

                    <div class="form-group d-flex justify-content-end gap-2">
                        <a href="{{ route('tax-demand.list') }}" class="btn btn-secondary">रद्द करा</a>
                        <button type="submit" class="btn btn-primary">जतन करा</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
