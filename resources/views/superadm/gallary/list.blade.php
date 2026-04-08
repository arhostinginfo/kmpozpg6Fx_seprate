@extends('superadm.layout.master')

@section('title', 'Gallery')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>Gallery</h3>
                        <a href="{{ route('gallary.add') }}" class="btn btn-sm btn-outline-primary">Add Gallery</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatables">
                            <thead>
                                <tr>
                                    <th>Sr. No.</th>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>Attachment</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gallaries as $i => $data)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $data->type_attachment }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>
                                            @if ($data->type_attachment == 'Image')
                                                <img src="{{ asset('storage/' . ($data->attachment ?? 'default.jpg')) }}"
                                                    alt="{{ $data->name }}"
                                                    class="img-thumb"
                                                    onclick="openImgModal('{{ asset('storage/' . ($data->attachment ?? 'default.jpg')) }}')"
                                                    title="Click to preview">
                                            @elseif($data->type_attachment == 'Video')
                                                <video style="height:80px;width:120px;" controls>
                                                    <source src="{{ asset('storage/' . ($data->attachment ?? '')) }}" type="video/mp4">
                                                </video>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('gallary.updatestatus') }}" method="POST" class="d-inline-block">
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
                                            <a href="{{ route('gallary.edit', base64_encode($data->id)) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form action="{{ route('gallary.delete') }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                <input type="hidden" name="encodedId" value="{{ base64_encode($data->id) }}">
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
                title: "Are you sure?", text: "Do you want to change the status?", icon: "warning",
                showCancelButton: true, confirmButtonColor: "#28a745", cancelButtonColor: "#d33",
                confirmButtonText: "Yes, change it!", cancelButtonText: "No, cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.find("input[name='is_active']").length
                        ? form.find("input[name='is_active']").val(is_active)
                        : form.append(`<input type="hidden" name="is_active" value="${is_active}">`);
                    form.submit();
                } else { checkbox.prop("checked", !checkbox.is(":checked")); }
            });
        });
    </script>
@endsection
