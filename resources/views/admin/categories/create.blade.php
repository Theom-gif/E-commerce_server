@extends('admin.layout')

@section('title', 'New Category')

@section('header')
  <h1>New Category</h1>
  <p>Create a new category to organize products.</p>
@endsection

@section('content')
  <div class="card" style="max-width: 520px;">
    <form method="POST" action="{{ route('admin.categories.store') }}">
      @csrf
      <div class="field">
        <label class="label" for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required>
      </div>

      <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Create Category</button>
      </div>
    </form>
  </div>
@endsection
