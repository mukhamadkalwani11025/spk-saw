@extends('layouts.app')

@section('title', 'Data Rekomendasi')

@section('content_header')
    <h1>Data Rekomendasi Makanan (Metode SAW)</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <form action="{{ route('rekomendasi.hitung') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="bi bi-calculator"></i> Hitung Ulang SAW
            </button>
        </form>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="tabelRekomendasi" class="table table-bordered table-striped">
            <thead class="bg-primary text-white">
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama User</th>
                    <th>Nama Makanan</th>
                    <th>Skor SAW</th>
                    <th>Peringkat</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekomendasi as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->user->name ?? '-' }}</td>
                        <td>{{ $r->makanan->nama ?? '-' }}</td>
                        <td class="text-right">{{ number_format($r->skor, 4) }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $loop->iteration == 1 ? 'success' : 'secondary' }}">
                                {{ $loop->iteration }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $r->id }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script>
$(function(){
    // Inisialisasi DataTables
    $('#tabelRekomendasi').DataTable({
        responsive: true,
        autoWidth: false,
        order: [[3, 'desc']],
        columnDefs: [
            { targets: 3, className: 'text-right' },
            { targets: [4,5], className: 'text-center' }
        ]
    });

    // Setup CSRF untuk AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    });

    // Tombol hapus
    $(document).on('click', '.delete-btn', function(){
        let id = $(this).data('id');
        if(!confirm('Yakin ingin menghapus data rekomendasi ini?')) return;

        $.ajax({
            url: '/rekomendasi/' + id,
            method: 'POST',
            data: { _method: 'DELETE' },
            success: function(res){
                alert(res.message || 'Data berhasil dihapus.');
                location.reload();
            },
            error: function(){
                alert('Terjadi kesalahan saat menghapus data.');
            }
        });
    });
});
</script>
@stop
