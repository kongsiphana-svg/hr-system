@extends('layouts.app')

@section('content')
<style>
    .register-container {
        max-width: 450px;
        margin: 40px auto 0 auto;
    }
    .btn-custom-purple {
        background-color: #3b3db1 !important;
        border-color: #3b3db1 !important;
        color: white !important;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    .btn-custom-purple:hover {
        background-color: #2f3192 !important;
        border-color: #2f3192 !important;
    }
    .form-label-custom {
        font-size: 0.75rem;
        font-weight: 700;
        color: #8c8f94;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .input-group-custom {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background-color: #fff;
    }
    .input-group-custom .form-control {
        border: none;
        box-shadow: none;
        font-size: 0.95rem;
    }
    .input-group-custom .input-group-text {
        background: transparent;
        border: none;
        color: #adb5bd;
    }
    .back-btn {
        color: #3b3db1;
        transition: opacity 0.2s;
    }
    .back-btn:hover {
        opacity: 0.7;
    }
    .social-btn {
        background-color: #ffffff;
        border: 1px solid #e2e8f0; /* Crisp, light border */
        border-radius: 8px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease-in-out;
        text-decoration: none;
        width: 100%;
    }
    /* Dynamic hover lift effect */
    .social-btn:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .social-btn svg {
        transition: transform 0.2s ease-in-out;
    }
    .social-btn:hover svg {
        transform: scale(1.05); /* Subtle icon pop on hover */
    }
</style>

<div class="container">
    <div class="register-container">
        
        <a href="{{ route('login') }}" class="back-btn d-inline-block mb-4 text-decoration-none">
            <i class="fa-solid fa-arrow-left fs-4"></i>
        </a>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <div class="card-body">
                
                <div class="text-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center rounded-3 mb-3" style="width: 50px; height: 50px; background-color: rgba(59, 61, 177, 0.1) !important; color: #3b3db1 !important;">
                        <i class="fa-solid fa-shield-halved fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark m-0">Admin Create Account</h5>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label form-label-custom mb-1">Username</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="form-control rounded-3 py-2 border-secondary-subtle @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-custom mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="form-control rounded-3 py-2 border-secondary-subtle @error('email') is-invalid @enderror">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-label-custom mb-1">Password</label>
                        <div class="input-group input-group-custom d-flex align-items-center">
                            <span class="input-group-text pe-0"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" id="password" placeholder="••••••••" required
                                class="form-control py-2 @error('password') is-invalid @enderror"
                                style="border: none !important; box-shadow: none !important; background: transparent;">
                            <button type="button" id="togglePassword" class="input-group-text ps-0 border-0 bg-transparent" style="cursor: pointer; color: #adb5bd;">
                                <i class="fa-solid fa-eye-slash" id="eyeIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label form-label-custom mb-1">Confirm Password</label>
                        <div class="input-group input-group-custom d-flex align-items-center">
                            <span class="input-group-text pe-0"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••" required
                                class="form-control py-2"
                                style="border: none !important; box-shadow: none !important; background: transparent;">
                            <button type="button" id="toggleConfirmPassword" class="input-group-text ps-0 border-0 bg-transparent" style="cursor: pointer; color: #adb5bd;">
                                <i class="fa-solid fa-eye-slash" id="confirmEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Primary Sign Up Button -->
                    <button type="submit" class="btn text-white w-100 py-2.5 mb-4 border-0 shadow-sm" style="background-color: #3b3db1; border-radius: 8px; font-weight: 600;">
                        Create Account
                    </button>

                    <!-- Social Divider -->
                    <div class="d-flex align-items-center my-4">
                        <hr class="flex-grow-1 text-muted opacity-25">
                        <span class="mx-3 text-muted small fw-bold" style="letter-spacing: 0.8px; font-size: 0.7rem;">OR CONTINUE WITH</span>
                        <hr class="flex-grow-1 text-muted opacity-25">
                    </div>

                    <!-- Premium Dual Social Media Grid -->
                    <div class="row g-3">
                        <!-- Authentic Google Button -->
                        <div class="col-6">
                            <a href="#" class="social-btn">
                                <!-- Official Multi-color Google Vector -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" class="me-1">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285f4"/>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34a853"/>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#fbbc05"/>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#ea4335"/>
                                </svg>
                            </a>
                        </div>
                        <!-- Authentic Facebook Button -->
                        <div class="col-6">
                            <a href="#" class="social-btn">
                                <!-- Official Facebook Vector -->
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22" fill="#1877f2">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="text-center">
                        <span class="text-muted small">Already have an account? </span>
                        <a href="{{ route('login') }}" class="text-decoration-none small fw-bold" style="color: #3b3db1;">Sign In</a>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle Password Field
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });

        // Toggle Confirm Password Field
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const toggleConfirmPasswordButton = document.getElementById('toggleConfirmPassword');
        const confirmEyeIcon = document.getElementById('confirmEyeIcon');

        toggleConfirmPasswordButton.addEventListener('click', function () {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            if (type === 'text') {
                confirmEyeIcon.classList.remove('fa-eye-slash');
                confirmEyeIcon.classList.add('fa-eye');
            } else {
                confirmEyeIcon.classList.remove('fa-eye');
                confirmEyeIcon.classList.add('fa-eye-slash');
            }
        });
    });
</script>
@endsection