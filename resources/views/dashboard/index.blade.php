@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    {{-- Total Makanan --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $totalMakanan }}</h3>
                <p>Data Makanan</p>
            </div>
            <div class="icon">
                <i class="bi bi-egg-fried"></i>
            </div>
        </div>
    </div>

    {{-- Total Kriteria --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalKriteria }}</h3>
                <p>Data Kriteria</p>
            </div>
            <div class="icon">
                <i class="bi bi-list-check"></i>
            </div>
        </div>
    </div>

    {{-- Total Makanan Kriteria --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalMakananKriteria }}</h3>
                <p>Data Makanan - Kriteria</p>
            </div>
            <div class="icon">
                <i class="bi bi-diagram-3"></i>
            </div>
        </div>
    </div>

    {{-- Total Rekomendasi --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $totalRekomendasi }}</h3>
                <p>Data Rekomendasi</p>
            </div>
            <div class="icon">
                <i class="bi bi-star-fill"></i>
            </div>
        </div>
    </div>

    {{-- Total User --}}
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $totalUser }}</h3>
                <p>Data User</p>
            </div>
            <div class="icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
</div>
@stop

@section('footer')
    <div class="text-center text-md-left">
        Copyright © 2014 - 2025 | All rights reserved. <br>
    </div>
@stop
