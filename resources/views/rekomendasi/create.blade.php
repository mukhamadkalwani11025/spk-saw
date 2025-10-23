@extends('layouts.app')

@section('title', 'Tambah Rekomendasi')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Tambah Rekomendasi</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('rekomendasi.store') }}" method="POST">
            @csrf

            {{-- Pilih User --}}
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Pilih Makanan --}}
            <div class="mb-3">
                <label for="makanan_id" class="form-label">Makanan</label>
                <select name="makanan_id" id="makanan_id" class="form-select @error('makanan_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Makanan --</option>
                    @foreach($makanans as $makanan)
                        <option value="{{ $makanan->id }}" {{ old('makanan_id') == $makanan->id ? 'selected' : '' }}>
                            {{ $makanan->nama }}
                        </option>
                    @endforeach
                </select>
                @error('makanan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Input Skor --}}
            <div class="mb-3">
                <label for="skor" class="form-label">Skor</label>
                <input type="number" step="0.0001" name="skor" id="skor" class="form-control @error('skor') is-invalid @enderror" value="{{ old('skor') }}" required>
                @error('skor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-end">
                <a href="{{ route('rekomendasi.index') }}" class="btn btn-secondary me-2">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
