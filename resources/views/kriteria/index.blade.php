@extends('layouts.app')

@section('title', 'Data Kriteria')

@section('content_header')
    <h1>Data Kriteria</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Kriteria
        </button>
    </div>

    <div class="card-body">
        {{-- alert --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="tabelKriteria" class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama</th>
                    <th>Bobot</th>
                    <th>Tipe</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kriteria as $k)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $k->nama }}</td>
                        <td>{{ $k->bobot }}</td>
                        <td>{{ ucfirst($k->tipe) }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="{{ $k->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $k->id }}">
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
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formTambah">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="t_nama">Nama</label>
            <input type="text" name="nama" id="t_nama" class="form-control">
            <div class="invalid-feedback d-none" id="t_nama_err"></div>
          </div>

          <div class="form-group">
            <label for="t_bobot">Bobot</label>
            <input type="number" step="0.01" name="bobot" id="t_bobot" class="form-control">
            <div class="invalid-feedback d-none" id="t_bobot_err"></div>
          </div>

          <div class="form-group">
            <label for="t_tipe">Tipe</label>
            <select name="tipe" id="t_tipe" class="form-control">
              <option value="benefit">Benefit</option>
              <option value="cost">Cost</option>
            </select>
            <div class="invalid-feedback d-none" id="t_tipe_err"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formEdit">
        @csrf
        @method('PUT')
        <input type="hidden" id="e_id" name="id">

        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Edit Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="e_nama">Nama</label>
            <input type="text" name="nama" id="e_nama" class="form-control">
            <div class="invalid-feedback d-none" id="e_nama_err"></div>
          </div>

          <div class="form-group">
            <label for="e_bobot">Bobot</label>
            <input type="number" step="0.01" name="bobot" id="e_bobot" class="form-control">
            <div class="invalid-feedback d-none" id="e_bobot_err"></div>
          </div>

          <div class="form-group">
            <label for="e_tipe">Tipe</label>
            <select name="tipe" id="e_tipe" class="form-control">
              <option value="benefit">Benefit</option>
              <option value="cost">Cost</option>
            </select>
            <div class="invalid-feedback d-none" id="e_tipe_err"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop

@section('js')
<script>
$(function(){
    // setup CSRF
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    // inisialisasi DataTable
    $('#tabelKriteria').DataTable({
        responsive: true,
        autoWidth: false
    });

    // Buka modal tambah -> reset form + clear errors
    $(document).on('click', '[data-target="#modalTambah"]', function(){
        $('#formTambah')[0].reset();
        clearErrorsT();
    });

    // Submit Tambah (AJAX)
    $('#formTambah').on('submit', function(e){
        e.preventDefault();
        clearErrorsT();
        let data = {
            nama: $('#t_nama').val(),
            bobot: $('#t_bobot').val(),
            tipe: $('#t_tipe').val()
        };

        $.post("{{ route('kriteria.store') }}", data)
            .done(function(res){
                $('#modalTambah').modal('hide');
                location.reload(); // mudah: reload untuk refresh table (bisa di-opt jika mau push row via JS)
            })
            .fail(function(xhr){
                if (xhr.status === 422) {
                    showErrorsT(xhr.responseJSON.errors);
                } else {
                    alert('Terjadi kesalahan server.');
                }
            });
    });

    // Delegated: klik Edit -> ambil data lalu tampilkan modal edit
    $(document).on('click', '.edit-btn', function(){
        let id = $(this).data('id');
        // clear previous errors
        clearErrorsE();
        $.get('/kriteria/' + id)
            .done(function(data){
                $('#e_id').val(data.id);
                $('#e_nama').val(data.nama);
                $('#e_bobot').val(data.bobot);
                $('#e_tipe').val(data.tipe);
                $('#modalEdit').modal('show');
            })
            .fail(function(){
                alert('Gagal memuat data kriteria.');
            });
    });

    // Submit Edit (AJAX)
    $('#formEdit').on('submit', function(e){
        e.preventDefault();
        clearErrorsE();
        let id = $('#e_id').val();
        let data = {
            nama: $('#e_nama').val(),
            bobot: $('#e_bobot').val(),
            tipe: $('#e_tipe').val(),
            _method: 'PUT'
        };

        $.ajax({
            url: '/kriteria/' + id,
            method: 'POST',
            data: data
        }).done(function(){
            $('#modalEdit').modal('hide');
            location.reload();
        }).fail(function(xhr){
            if (xhr.status === 422) {
                showErrorsE(xhr.responseJSON.errors);
            } else {
                alert('Terjadi kesalahan saat update.');
            }
        });
    });

    // Delegated Delete (AJAX)
    $(document).on('click', '.delete-btn', function(){
        if (!confirm('Yakin ingin menghapus?')) return;
        let id = $(this).data('id');
        $.ajax({
            url: '/kriteria/' + id,
            method: 'POST',
            data: { _method: 'DELETE' }
        }).done(function(){
            location.reload();
        }).fail(function(){
            alert('Gagal menghapus data.');
        });
    });

    // helper: tampilkan error untuk form Tambah
    function showErrorsT(errors){
        if (errors.nama) { $('#t_nama').addClass('is-invalid'); $('#t_nama_err').text(errors.nama[0]).removeClass('d-none'); }
        if (errors.bobot) { $('#t_bobot').addClass('is-invalid'); $('#t_bobot_err').text(errors.bobot[0]).removeClass('d-none'); }
        if (errors.tipe) { $('#t_tipe').addClass('is-invalid'); $('#t_tipe_err').text(errors.tipe[0]).removeClass('d-none'); }
    }
    function clearErrorsT(){
        $('#t_nama, #t_bobot, #t_tipe').removeClass('is-invalid');
        $('#t_nama_err, #t_bobot_err, #t_tipe_err').text('').addClass('d-none');
    }

    // helper: tampilkan error untuk form Edit
    function showErrorsE(errors){
        if (errors.nama) { $('#e_nama').addClass('is-invalid'); $('#e_nama_err').text(errors.nama[0]).removeClass('d-none'); }
        if (errors.bobot) { $('#e_bobot').addClass('is-invalid'); $('#e_bobot_err').text(errors.bobot[0]).removeClass('d-none'); }
        if (errors.tipe) { $('#e_tipe').addClass('is-invalid'); $('#e_tipe_err').text(errors.tipe[0]).removeClass('d-none'); }
    }
    function clearErrorsE(){
        $('#e_nama, #e_bobot, #e_tipe').removeClass('is-invalid');
        $('#e_nama_err, #e_bobot_err, #e_tipe_err').text('').addClass('d-none');
    }
});
</script>
@stop
