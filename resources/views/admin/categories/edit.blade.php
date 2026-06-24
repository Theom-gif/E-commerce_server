@extends('admin.layout')

@section('title', 'Edit Category')

@section('header')
  <h1>Edit Category</h1>
  <p>Update category details.</p>
@endsection

@section('content')
  <div class="card" style="max-width: 520px;">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
      @csrf
      @method('PUT')
      <div class="field">
        <label class="label" for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" required>
      </div>

      <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Update Category</button>
      </div>
    </form>
  </div>
@endsection
