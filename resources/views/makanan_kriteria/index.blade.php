@extends('layouts.app')

@section('title', 'Data Makanan Kriteria')

@section('content_header')
    <h1>Data Makanan Kriteria</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Nilai
        </button>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="tabelMakananKriteria" class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Makanan</th>
                    <th>Kriteria</th>
                    <th>Nilai</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($makanan_kriteria as $mk)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $mk->makanan->nama ?? '-' }}</td>
                        <td>{{ $mk->kriteria->nama ?? '-' }}</td>
                        <td>{{ $mk->nilai }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="{{ $mk->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="{{ $mk->id }}">
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
          <h5 class="modal-title">Tambah Nilai Makanan - Kriteria</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="t_makanan_id">Makanan</label>
            <select name="makanan_id" id="t_makanan_id" class="form-control">
              <option value="">-- Pilih Makanan --</option>
              @foreach($makanan as $m)
                  <option value="{{ $m->id }}">{{ $m->nama }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback d-none" id="t_makanan_id_err"></div>
          </div>

          <div class="form-group">
            <label for="t_kriteria_id">Kriteria</label>
            <select name="kriteria_id" id="t_kriteria_id" class="form-control">
              <option value="">-- Pilih Kriteria --</option>
              @foreach($kriteria as $k)
                  <option value="{{ $k->id }}">{{ $k->nama }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback d-none" id="t_kriteria_id_err"></div>
          </div>

          <div class="form-group">
            <label for="t_nilai">Nilai</label>
            <input type="number" step="0.01" name="nilai" id="t_nilai" class="form-control">
            <div class="invalid-feedback d-none" id="t_nilai_err"></div>
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
          <h5 class="modal-title">Edit Nilai</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="e_makanan_id">Makanan</label>
            <select name="makanan_id" id="e_makanan_id" class="form-control">
              <option value="">-- Pilih Makanan --</option>
              @foreach($makanan as $m)
                  <option value="{{ $m->id }}">{{ $m->nama }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback d-none" id="e_makanan_id_err"></div>
          </div>

          <div class="form-group">
            <label for="e_kriteria_id">Kriteria</label>
            <select name="kriteria_id" id="e_kriteria_id" class="form-control">
              <option value="">-- Pilih Kriteria --</option>
              @foreach($kriteria as $k)
                  <option value="{{ $k->id }}">{{ $k->nama }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback d-none" id="e_kriteria_id_err"></div>
          </div>

          <div class="form-group">
            <label for="e_nilai">Nilai</label>
            <input type="number" step="0.01" name="nilai" id="e_nilai" class="form-control">
            <div class="invalid-feedback d-none" id="e_nilai_err"></div>
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
    // CSRF untuk AJAX
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });

    // DataTable
    $('#tabelMakananKriteria').DataTable({
        responsive: true,
        autoWidth: false
    });

    // reset modal tambah
    $(document).on('click', '[data-target="#modalTambah"]', function(){
        $('#formTambah')[0].reset();
        clearErrorsT();
    });

    // Tambah (AJAX). NOTE: mengirim kedua nama key agar cocok dengan schema manapun
    $('#formTambah').on('submit', function(e){
        e.preventDefault();
        clearErrorsT();

        let makananVal = $('#t_makanan_id').val();
        let kriteriaVal = $('#t_kriteria_id').val();
        let nilaiVal = $('#t_nilai').val();

        let payload = {
            makanan_id: makananVal,
            kriteria_id: kriteriaVal,
            makanans_id: makananVal,    // fallback key (if your col named makanans_id)
            kriterias_id: kriteriaVal,  // fallback key (if your col named kriterias_id)
            nilai: nilaiVal
        };

        $.post("{{ route('makanan_kriteria.store') }}", payload)
            .done(() => { $('#modalTambah').modal('hide'); location.reload(); })
            .fail(xhr => {
                if (xhr.status === 422) showErrorsT(xhr.responseJSON.errors);
                else alert('Gagal menambah data.');
            });
    });

    // Edit - tampilkan modal (delegated)
    $(document).on('click', '.edit-btn', function(){
        clearErrorsE();
        let id = $(this).data('id');

        $.get('/makanan_kriteria/' + id)
            .done(data => {
                $('#e_id').val(data.id);

                // handle berbagai nama field di response
                let mId = data.makanan_id ?? data.makanans_id ?? data.makanansId ?? data.makananId;
                let kId = data.kriteria_id ?? data.kriterias_id ?? data.kriteriaId ?? data.kriteriasId;

                $('#e_makanan_id').val(mId);
                $('#e_kriteria_id').val(kId);
                $('#e_nilai').val(data.nilai);
                $('#modalEdit').modal('show');
            })
            .fail(() => alert('Gagal memuat data.'));
    });

    // Edit - submit
    $('#formEdit').on('submit', function(e){
        e.preventDefault();
        clearErrorsE();

        let id = $('#e_id').val();
        let makananVal = $('#e_makanan_id').val();
        let kriteriaVal = $('#e_kriteria_id').val();
        let nilaiVal = $('#e_nilai').val();

        let payload = {
            makanan_id: makananVal,
            kriteria_id: kriteriaVal,
            makanans_id: makananVal,
            kriterias_id: kriteriaVal,
            nilai: nilaiVal,
            _method: 'PUT'
        };

        $.ajax({
            url: '/makanan_kriteria/' + id,
            method: 'POST',
            data: payload
        }).done(() => { $('#modalEdit').modal('hide'); location.reload(); })
          .fail(xhr => {
              if (xhr.status === 422) showErrorsE(xhr.responseJSON.errors);
              else alert('Gagal update data.');
          });
    });

    // Delete
    $(document).on('click', '.delete-btn', function(){
        if (!confirm('Yakin ingin menghapus?')) return;
        let id = $(this).data('id');

        $.ajax({
            url: '/makanan_kriteria/' + id,
            method: 'POST',
            data: { _method: 'DELETE' }
        }).done(() => location.reload())
          .fail(() => alert('Gagal menghapus data.'));
    });

    /* ===== helpers untuk menampilkan error validasi ===== */
    function showErrorsT(errors){
        if (errors.makanan_id || errors.makanans_id) {
            $('#t_makanan_id').addClass('is-invalid');
            $('#t_makanan_id_err').text((errors.makanan_id||errors.makanans_id)[0]).removeClass('d-none');
        }
        if (errors.kriteria_id || errors.kriterias_id) {
            $('#t_kriteria_id').addClass('is-invalid');
            $('#t_kriteria_id_err').text((errors.kriteria_id||errors.kriterias_id)[0]).removeClass('d-none');
        }
        if (errors.nilai) {
            $('#t_nilai').addClass('is-invalid');
            $('#t_nilai_err').text(errors.nilai[0]).removeClass('d-none');
        }
    }
    function clearErrorsT(){
        $('#t_makanan_id, #t_kriteria_id, #t_nilai').removeClass('is-invalid');
        $('#t_makanan_id_err, #t_kriteria_id_err, #t_nilai_err').text('').addClass('d-none');
    }

    function showErrorsE(errors){
        if (errors.makanan_id || errors.makanans_id) {
            $('#e_makanan_id').addClass('is-invalid');
            $('#e_makanan_id_err').text((errors.makanan_id||errors.makanans_id)[0]).removeClass('d-none');
        }
        if (errors.kriteria_id || errors.kriterias_id) {
            $('#e_kriteria_id').addClass('is-invalid');
            $('#e_kriteria_id_err').text((errors.kriteria_id||errors.kriterias_id)[0]).removeClass('d-none');
        }
        if (errors.nilai) {
            $('#e_nilai').addClass('is-invalid');
            $('#e_nilai_err').text(errors.nilai[0]).removeClass('d-none');
        }
    }
    function clearErrorsE(){
        $('#e_makanan_id, #e_kriteria_id, #e_nilai').removeClass('is-invalid');
        $('#e_makanan_id_err, #e_kriteria_id_err, #e_nilai_err').text('').addClass('d-none');
    }
});
</script>
@stop
