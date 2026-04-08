@extends('superadm.layout.master')

@section('title', 'Website Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Website Details</h3>
                        <a href="{{ route('navbar.add') }}" class="btn btn-sm btn-outline-primary" >Add Website Details</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatables">
                            <thead>
                                <tr>
                                    <th>क्र. नं.</th>
                                    <th>नाव</th>
                                    <th>फोटो</th>
                                    <th>स्थिती</th>
                                    <th>क्रिया</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($navbar as $i => $data)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>
                                            @if ($data->logo)
                                                <img src="{{ asset('storage/' . $data->logo) }}"
                                                    alt="{{ $data->name }}"
                                                    class="img-thumb"
                                                    onclick="openImgModal('{{ asset('storage/' . $data->logo) }}')"
                                                    title="Click to preview">
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('navbar.updatestatus') }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <label class="switch">
                                                    <input type="checkbox" class="toggle-status"
                                                        data-id="{{ base64_encode($data->id) }}"
                                                        {{ $data->is_active == 1 ? 'checked' : '' }}>
                                                    <span class="slider"></span>
                                                </label>
                                                <input type="hidden" name="id" value="{{ base64_encode($data->id) }}">
                                            </form>
                                        </td>
                                        <td>
                                            <a href="{{ route('navbar.edit', base64_encode($data->id)) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>

                                            <form action="{{ route('navbar.delete') }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                <input type="hidden" name="encodedId"
                                                    value="{{ base64_encode($data->id) }}">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
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
            let checkbox = $(this);
            let form = checkbox.closest("form");
            let is_active = checkbox.is(":checked") ? 1 : 0;

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to change the status?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!",
                cancelButtonText: "No, cancel"
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#sliderTable').DataTable({
                responsive: true,
                paging: true,
                searching: false,
                lengthChange: false,
                pageLength: 10,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/mr.json"
                }
            });

            // simple delete confirm
            $('.delete-form').on('submit', function(e) {
                if (!confirm('तुम्हाला हा अधिकारी नक्की हटवायचा आहे का?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
