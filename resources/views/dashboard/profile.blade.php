@extends('layouts.dashboard')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold m-0">Account Profile</h3>
    <small class="text-muted">Manage your credentials and information</small>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 bg-white text-center">
            
            <!-- Profile Avatar Placeholder Display -->
            <div class="d-flex justify-content-center mb-4">
                <div class="position-relative d-flex align-items-center justify-content-center border-0 rounded-circle shadow-sm" 
                     style="width: 130px; height: 130px; background-color: #e2e8f0; color: #94a3b8;">
                    <i class="fa-solid fa-user" style="font-size: 4.5rem;"></i>
                </div>
            </div>

            <!-- Profile Form -->
            <form action="{{ route('profile.update') }}" method="POST" class="text-start">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">USERNAME</label>
                    <input type="text" name="name" class="form-control bg-light" value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">EMAIL ADDRESS</label>
                    <input type="email" name="email" class="form-control bg-light" value="{{ old('email', $user->email) }}" required>
                </div>
                
                <hr class="my-4 text-muted opacity-25">
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">NEW PASSWORD (LEAVE BLANK TO KEEP CURRENT)</label>
                    <input type="password" name="password" class="form-control bg-light">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold text-muted small">CONFIRM NEW PASSWORD</label>
                    <input type="password" name="password_confirmation" class="form-control bg-light">
                </div>
                
                <button type="submit" class="btn text-white w-100 py-2.5 mt-3 border-0 shadow-sm" style="background-color: #3b3db1;">
                    Save Profile Changes
                </button>
            </form>

        </div>
    </div>
</div>
@endsection