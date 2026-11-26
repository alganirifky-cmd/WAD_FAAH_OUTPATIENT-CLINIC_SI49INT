@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div style="max-width:420px;margin:40px auto;">
  <h1 class="page-title">Login</h1>
  <div class="card">
    @if ($errors->any())
      <ul style="margin-bottom:20px;padding:12px 12px 12px 28px;background:#fee2e2;color:#b91c1c;border-radius:8px;font-size:13px;">
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" required>
      </div>
      <div class="form-actions" style="margin-top:20px;">
        <button type="submit" class="btn-submit">Login</button>
      </div>
    </form>
  </div>
</div>
@endsection
