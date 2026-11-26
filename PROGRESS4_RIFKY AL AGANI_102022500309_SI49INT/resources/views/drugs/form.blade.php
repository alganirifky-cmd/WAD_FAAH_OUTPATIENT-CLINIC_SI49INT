@extends('layouts.app')

@php $isEdit = $drug->exists; @endphp

@section('title', $isEdit ? 'Edit Drug' : 'Pharmaceutical Form')

@section('content')
<h1 class="page-title">{{ $isEdit ? 'Edit Drug' : 'Pharmaceutical Form' }}</h1>

<div class="card">
  @if ($errors->any())
    <ul style="margin-bottom:20px;padding:12px 12px 12px 28px;background:#fee2e2;color:#b91c1c;border-radius:8px;font-size:13px;">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  @endif

  <form action="{{ $isEdit ? route('drugs.update', $drug) : route('drugs.store') }}" method="POST">
    @csrf
    @if ($isEdit) @method('PUT') @endif

    <div class="form-section-title">1. Drug Details</div>
    <div class="form-grid-2">
      <div class="form-group">
        <label for="drug_code">Drug Code <span class="required">*</span></label>
        <input type="text" id="drug_code" name="drug_code" class="form-control" placeholder="e.g., DRG-001" value="{{ old('drug_code', $drug->drug_code) }}" required>
      </div>
      <div class="form-group">
        <label for="drug_name">Drug Name <span class="required">*</span></label>
        <input type="text" id="drug_name" name="drug_name" class="form-control" placeholder="e.g., Paracetamol" value="{{ old('drug_name', $drug->drug_name) }}" required>
      </div>
      <div class="form-group">
        <label for="dosage">Dosage / Strength <span class="required">*</span></label>
        <input type="text" id="dosage" name="dosage" class="form-control" placeholder="e.g., 500mg" value="{{ old('dosage', $drug->dosage) }}" required>
      </div>
      <div class="form-group">
        <label for="category">Category <span class="required">*</span></label>
        <input type="text" id="category" name="category" class="form-control" placeholder="e.g., Analgesic / Antibiotic" value="{{ old('category', $drug->category) }}" required>
      </div>
    </div>

    <div class="form-section-title" style="margin-top: 24px;">2. Dosage Form</div>
    <div class="form-group">
      <div class="radio-group">
        @foreach (\App\Models\Drug::FORMS as $f)
          <label class="radio-item">
            <input type="radio" name="preparation_form" value="{{ $f }}" {{ old('preparation_form', $drug->preparation_form) === $f ? 'checked' : '' }}> {{ $f }}
          </label>
        @endforeach
      </div>
    </div>

    <div class="form-section-title" style="margin-top: 24px;">3. Manufacturer & Legal Info</div>
    <div class="form-grid-2">
      <div class="form-group">
        <label for="manufacturer">Manufacturer <span class="required">*</span></label>
        <input type="text" id="manufacturer" name="manufacturer" class="form-control" placeholder="e.g., PT. ABC Pharma" value="{{ old('manufacturer', $drug->manufacturer) }}" required>
      </div>
      <div class="form-group">
        <label for="bpom_number">Registration Number</label>
        <input type="text" id="bpom_number" name="bpom_number" class="form-control" placeholder="e.g., DBL1234567890A1" value="{{ old('bpom_number', $drug->bpom_number) }}">
      </div>
    </div>

    <div class="form-section-title" style="margin-top: 24px;">4. Inventory & Pricing</div>
    <div class="form-grid-2">
      <div class="form-group">
        <label for="stock">Stock Quantity <span class="required">*</span></label>
        <input type="number" id="stock" name="stock" class="form-control" placeholder="e.g., 100" min="0" value="{{ old('stock', $drug->stock) }}" required>
      </div>
      <div class="form-group">
        <label for="unit">Unit <span class="required">*</span></label>
        <input type="text" id="unit" name="unit" class="form-control" placeholder="e.g., Tablet" value="{{ old('unit', $drug->unit) }}" required>
      </div>
      <div class="form-group">
        <label for="price">Unit Price (Rp) <span class="required">*</span></label>
        <input type="number" step="0.01" id="price" name="price" class="form-control" placeholder="e.g., 10000" min="0" value="{{ old('price', $drug->price) }}" required>
      </div>
      <div class="form-group">
        <label for="expiry_date">Expiry Date <span class="required">*</span></label>
        <input type="date" id="expiry_date" name="expiry_date" class="form-control" value="{{ old('expiry_date', $drug->expiry_date?->format('Y-m-d')) }}" required>
      </div>
    </div>

    <div class="form-actions" style="margin-top: 32px;">
      <button type="submit" class="btn-submit">{{ $isEdit ? 'Update Drug' : 'Save Drug Entry' }}</button>
      <a href="{{ route('drugs.index') }}" class="btn-cancel">Cancel</a>
    </div>
  </form>
</div>
@endsection
