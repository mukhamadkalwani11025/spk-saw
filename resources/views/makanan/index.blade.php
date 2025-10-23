@extends('layouts.app')

@section('title', 'Data Makanan')

@section('content_header')
    <h1>Data Makanan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Makanan
        </button>
    </div>
    <div class="card-body">
        <table id="tabelMakanan" class="table table-bordered table-striped text-center">
            <thead class="bg-primary text-white">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($makanan as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $m->nama }}</td>
                        <td>{{ $m->deskripsi }}</td>
                        <td>
                            @if($m->foto)
                                <img src="{{ asset('storage/'.$m->foto) }}" width="60" height="60" class="rounded">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $m->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $m->id }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="formTambah">
            @csrf
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Tambah Makanan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Foto</label>
                    <input type="file" name="foto" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Simpan</button>
            </div>
        </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="formEdit">
            @csrf
            <input type="hidden" id="edit_id" name="id">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Makanan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label>Foto Baru</label>
                    <input type="file" name="foto" class="form-control">
                    <div class="mt-2">
                        <img id="previewFoto" src="" width="80" class="rounded d-none">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Update</button>
            </div>
        </form>
    </div>
  </div>
</div>
@stop

@section('js')
<script>
$(function () {
    $('#tabelMakanan').DataTable();

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    // Tambah Data
    $('#formTambah').on('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("makanan.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#modalTambah').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Gagal menyimpan data');
            }
        });
    });

    // Edit Data
    $(document).on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $.get('/makanan/' + id, function(data) {
            $('#edit_id').val(data.id);
            $('#edit_nama').val(data.nama);
            $('#edit_deskripsi').val(data.deskripsi);
            if (data.foto) {
                $('#previewFoto').attr('src', '/storage/' + data.foto).removeClass('d-none');
            } else {
                $('#previewFoto').addClass('d-none');
            }
            $('#modalEdit').modal('show');
        });
    });

    // Update Data
    $('#formEdit').on('submit', function(e) {
        e.preventDefault();
        let id = $('#edit_id').val();
        let formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: '/makanan/' + id,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#modalEdit').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Gagal mengupdate data');
            }
        });
    });

    // Hapus Data
    $(document).on('click', '.delete-btn', function() {
        if (!confirm('Yakin ingin menghapus data ini?')) return;
        let id = $(this).data('id');

        $.ajax({
            url: '/makanan/' + id,
            type: 'POST',
            data: { _method: 'DELETE' },
            success: function() {
                location.reload();
            },
            error: function() {
                alert('Gagal menghapus data');
            }
        });
    });
});
</script>
@stop
