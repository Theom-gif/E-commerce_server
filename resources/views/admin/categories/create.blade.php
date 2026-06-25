@extends('admin.layout')

@section('title', 'New Category')

@push('styles')
<style>
  /* Wrapper and Layout */
  .category-create-wrap {
    max-width: 650px;
    margin: 8rem auto;
    padding: 0 15px;
  }

  .category-create-card {
    background: #ffffff;
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid #f1f5f9;
    box-shadow:
      0 20px 40px rgba(244, 63, 94, 0.05),
      0 10px 20px rgba(0, 0, 0, 0.02);
  }

  /* Vibrant Header */
  .category-card-header {
    background: linear-gradient(135deg, rgba(4, 132, 56, 0.9) 0%, #0ae394 100%);
    padding: 35px 40px;
    color: white;
  }

  .category-card-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    letter-spacing: -0.5px;
  }

  .category-card-header p {
    margin: 8px 0 0 0;
    opacity: 0.85;
    font-size: 14px;
    font-weight: 400;
  }

  /* Form Body & Content */
  .category-card-body {
    padding: 40px;
  }

  .category-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 28px;
  }

  .category-field label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
    letter-spacing: 0.3px;
  }

  /* Input Field & Animations */
  .category-field input {
    width: 100%;
    height: 54px;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    padding: 0 18px;
    font-size: 15px;
    color: #0f172a;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    background: #f8fafc;
  }

  .category-field input::placeholder {
    color: #94a3b8;
  }

  .category-field input:hover {
    border-color: #cbd5e1;
  }

  .category-field input:focus {
    outline: none;
    background: #ffffff;
    border-color: rgb(0, 153, 255);
    box-shadow: 0 0 0 4px rgba(225, 29, 72, 0.12);
  }

  /* Laravel Validation Error Styles */
  .category-field input.is-invalid {
    border-color: blue;
    background-color: #fef2f2;
  }
  
  .category-field input.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
  }

  .error-message {
    color: #ef4444;
    font-size: 13px;
    font-weight: 500;
    margin-top: 4px;
  }

  /* Modern Action Buttons */
  .category-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 14px;
    margin-top: 10px;
  }

  .btn {
    border: none;
    cursor: pointer;
    text-decoration: none;
    padding: 14px 28px;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 600;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .btn-secondary {
    background: #f1f5f9;
    color: #64748b;
  }

  .btn-secondary:hover {
    background: #e2e8f0;
    color: #334155;
    transform: translateY(-1px);
  }

  .btn-primary {
    background: linear-gradient(135deg, rgba(4, 132, 56, 0.9) 0%, #05d682 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(225, 29, 72, 0.2);
  }

  .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 30px rgba(21, 146, 228, 0.888);
    opacity: 0.95;
  }

  .btn:active {
    transform: translateY(1px);
  }

  /* Mobile Optimization */
  @media (max-width: 640px) {
    .category-card-header {
      padding: 25px 24px;
    }

    .category-card-body {
      padding: 24px;
    }

    .category-actions {
      flex-direction: column-reverse;
      gap: 10px;
    }

    .category-actions .btn {
      width: 100%;
    }
  }
</style>
@endpush

@section('content')
  <div class="category-create-wrap">
    <div class="category-create-card">
      
      <div class="category-card-header">
        <h2>Create New Category</h2>
        <p>Introduce a new group segment to classify your store products.</p>
      </div>

      <div class="category-card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}">
          @csrf
          
          <div class="category-field">
            <label for="name">Category Name</label>
            <input 
              id="name" 
              type="text" 
              name="name" 
              class="@error('name') is-invalid @enderror"
              value="{{ old('name') }}" 
              placeholder="e.g., Summer Collection, Electronics" 
              required 
              autofocus
              autocomplete="off"
            >
            @error('name')
              <span class="error-message">{{ $message }}</span>
            @enderror
          </div>

          <div class="category-actions">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Category</button>
          </div>
        </form>
      </div>

    </div>
  </div>
@endsection