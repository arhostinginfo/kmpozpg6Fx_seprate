@extends('superadm.layout.master')

@section('title', 'Tax Demand')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">कर मागणी व वसुली</h4>
                    <a href="{{ route('tax-demand.create') }}" class="btn btn-sm btn-outline-primary">+ नवीन नोंद जोडा</a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatables">
                        <thead>
                            <tr>
                                <th>अ.नं.</th>
                                <th>कराचा प्रकार</th>
                                <th>वर्ष प्रकार</th>
                                <th>मागणी रक्कम (₹)</th>
                                <th>वसूल रक्कम (₹)</th>
                                <th>टक्केवारी %</th>
                                <th>स्थिती</th>
                                <th>कृती</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demands as $key => $demand)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if($demand->tax_type === 'ghar_patti')
                                            <span class="badge bg-primary">घरपट्टी कर</span>
                                        @elseif($demand->tax_type === 'paani_patti')
                                            <span class="badge bg-info">पाणीपट्टी कर</span>
                                        @else
                                            <span class="badge bg-warning text-dark">गाळाभाडे / व्यवसायकर / इतर</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($demand->year_type === 'chalu')
                                            <span class="badge bg-success">चालू वर्ष</span>
                                        @else
                                            <span class="badge bg-secondary">मागील वर्ष</span>
                                        @endif
                                    </td>
                                    <td>₹ {{ number_format($demand->demand_amount, 2) }}</td>
                                    <td>₹ {{ number_format($demand->collected_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $demand->percentage >= 75 ? 'bg-success' : ($demand->percentage >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ $demand->percentage }}%
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('tax-demand.updatestatus') }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <label class="switch">
                                                <input type="checkbox" class="toggle-status"
                                                    data-id="{{ base64_encode($demand->id) }}"
                                                    {{ $demand->is_active == 1 ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>
                                            <input type="hidden" name="id" value="{{ base64_encode($demand->id) }}">
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('tax-demand.edit', base64_encode($demand->id)) }}"
                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('tax-demand.delete') }}" method="POST" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ base64_encode($demand->id) }}">
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    $(document).on("change", ".toggle-status", function (e) {
        e.preventDefault();
        let checkbox  = $(this);
        let form      = checkbox.closest("form");
        let is_active = checkbox.is(":checked") ? 1 : 0;
        Swal.fire({
            title: "Are you sure?", text: "Do you want to change the status?", icon: "warning",
            showCancelButton: true, confirmButtonColor: "#28a745", cancelButtonColor: "#d33",
            confirmButtonText: "Yes", cancelButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                if (form.find("input[name='is_active']").length) {
                    form.find("input[name='is_active']").val(is_active);
                } else {
                    form.append(`<input type="hidden" name="is_active" value="${is_active}">`);
                }
                form.submit();
            } else {
                checkbox.prop("checked", !checkbox.is(":checked"));
            }
        });
    });
</script>
@endsection
