@extends('layouts.app')

@section('title', 'Drug Catalogue')

@section('content')
@php $isAdmin = auth()->user()->role === 'admin'; @endphp
<div class="card">
  <div class="card-header">
    <h1 class="card-title">Drug Catalogue</h1>
    <form method="GET" action="{{ route('drugs.index') }}" class="header-actions">
      <input type="text" name="search" value="{{ $search }}" class="search-input" placeholder="Search drug name or code...">
      @if ($isAdmin)
        <a href="{{ route('drugs.create') }}" class="btn-add">+ Add Drug</a>
      @endif
    </form>
  </div>

  @if (session('success'))
    <p style="margin-bottom:16px;color:#15803d;font-weight:600;">{{ session('success') }}</p>
  @endif

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>No</th><th>Drug Code</th><th>Drug Name</th><th>Dosage / Strength</th>
          <th>Category</th><th>Form</th><th>Manufacturer</th><th>Stock</th>
          <th>Price</th><th>Expiry Date</th>@if ($isAdmin)<th style="text-align:center;">Action</th>@endif
        </tr>
      </thead>
      <tbody>
        @forelse ($drugs as $d)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><span class="badge-code">{{ $d->drug_code }}</span></td>
            <td><strong>{{ $d->drug_name }}</strong></td>
            <td>{{ $d->dosage }}</td>
            <td>{{ $d->category }}</td>
            <td>{{ $d->preparation_form }}</td>
            <td>{{ $d->manufacturer }}</td>
            <td>{{ $d->stock }} {{ $d->unit }}</td>
            <td>Rp {{ number_format($d->price, 0, ',', '.') }}</td>
            <td>{{ $d->expiry_date->format('d/m/Y') }}</td>
            @if ($isAdmin)
            <td>
              <div class="action-group">
                <a href="{{ route('drugs.edit', $d) }}" class="btn-action btn-edit">Edit</a>
                <form method="POST" action="{{ route('drugs.destroy', $d) }}"
                      onsubmit="return confirm('Hapus obat ini?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-action btn-delete">Delete</button>
                </form>
              </div>
            </td>
            @endif
          </tr>
        @empty
          <tr><td colspan="11" style="text-align:center;">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
