@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">

    <div class="mb-4">
        <h2 class="admin-title mb-1">My Profile</h2>
        <p class="admin-text-muted mb-0">Manage your account details and password.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- left column --}}
        <div class="col-lg-3 col-md-4">

            {{-- photo card --}}
            <div class="admin-card mb-4">
                <div class="admin-card-body text-center">

                    @if($admin->profile_photo)
                        <img src="{{ Storage::url($admin->profile_photo) }}"
                             alt="Profile Photo"
                             class="profile-photo-img mx-auto d-block mb-3">
                    @else
                        <div class="profile-initial mx-auto mb-3">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </div>
                    @endif

                    <h6 class="mb-0 fw-bold" style="color:var(--text-primary);">{{ $admin->name }}</h6>
                    <span class="badge mt-1 mb-2" style="background:var(--primary); color:#fff; font-size:0.75rem;">
                        Administrator
                    </span>
                    <p class="admin-text-muted small mb-3">{{ $admin->email }}</p>

                    <form action="{{ route('admin.profile.photo') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <label for="photoInput" class="btn-upload">
                            <i class="bi bi-camera me-1"></i> Change Photo
                        </label>
                        <input type="file" id="photoInput" name="profile_photo"
                               class="d-none" accept="image/*"
                               onchange="this.form.submit()">
                        @error('profile_photo')
                            <p class="text-danger small mt-2 mb-0">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>

            {{-- account info card --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-info-circle me-2 text-primary"></i>Account Info</h5>
                </div>
                <div class="admin-card-body">
                    <div class="profile-info-row">
                        <span class="profile-info-label">Role</span>
                        <span class="profile-info-value">Administrator</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Phone</span>
                        <span class="profile-info-value">{{ $admin->phone ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Address</span>
                        <span class="profile-info-value">{{ $admin->address ?? '—' }}</span>
                    </div>
                    <div class="profile-info-row">
                        <span class="profile-info-label">Member Since</span>
                        <span class="profile-info-value">{{ $admin->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- right column --}}
        <div class="col-lg-9 col-md-8">

            {{-- profile info form --}}
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <h5><i class="bi bi-person me-2 text-primary"></i>Profile Information</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="admin-label">Full Name</label>
                                <input type="text" name="name"
                                       class="admin-input @error('name') is-invalid @enderror"
                                       value="{{ old('name', $admin->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="admin-label">Email Address</label>
                                <input type="email" name="email"
                                       class="admin-input @error('email') is-invalid @enderror"
                                       value="{{ old('email', $admin->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="admin-label">Phone Number</label>
                                <input type="text" name="phone"
                                       class="admin-input @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $admin->phone) }}"
                                       placeholder="e.g. 9800000000">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="admin-label">Address</label>
                                <input type="text" name="address"
                                       class="admin-input @error('address') is-invalid @enderror"
                                       value="{{ old('address', $admin->address) }}"
                                       placeholder="e.g. Kathmandu, Nepal">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4 pt-2" style="border-top:1px solid var(--border);">
                            <button type="submit" class="btn-admin-primary px-4">
                                <i class="bi bi-check2 me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- change password form --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="bi bi-shield-lock me-2 text-primary"></i>Change Password</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        @if($errors->has('current_password') || $errors->has('new_password') || $errors->has('new_password_confirmation'))
                            <div class="alert alert-danger py-2 mb-3">
                                <ul class="mb-0 ps-3">
                                    @error('current_password')<li>{{ $message }}</li>@enderror
                                    @error('new_password')<li>{{ $message }}</li>@enderror
                                    @error('new_password_confirmation')<li>{{ $message }}</li>@enderror
                                </ul>
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="admin-label">Current Password</label>
                                <div class="position-relative">
                                    <input type="password" id="admin_current_password" name="current_password"
                                         class="admin-input pe-5 @error('current_password') is-invalid @enderror"
                                         autocomplete="off"
                                         data-clear-on-load="true">
                                    <button type="button"
                                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                                            data-password-target="admin_current_password"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                            style="z-index:3;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="admin-label">New Password</label>
                                <div class="position-relative">
                                    <input type="password" id="admin_new_password" name="new_password"
                                         class="admin-input pe-5 @error('new_password') is-invalid @enderror"
                                         autocomplete="new-password"
                                         data-clear-on-load="true">
                                    <button type="button"
                                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                                            data-password-target="admin_new_password"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                            style="z-index:3;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <p class="password-hint">Minimum 9 chars, with uppercase, number, and symbol.</p>
                                @error('new_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="admin-label">Confirm New Password</label>
                                <div class="position-relative">
                                    <input type="password" id="admin_new_password_confirmation" name="new_password_confirmation"
                                         class="admin-input pe-5"
                                         autocomplete="new-password"
                                         data-clear-on-load="true">
                                    <button type="button"
                                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                                            data-password-target="admin_new_password_confirmation"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                            style="z-index:3;">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-2" style="border-top:1px solid var(--border);">
                            <button type="submit" class="btn btn-outline-primary px-4">
                                <i class="bi bi-lock me-1"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.password-toggle-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-password-target');
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('bi-eye', !isHidden);
                icon.classList.toggle('bi-eye-slash', isHidden);
            }

            button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });

    document.querySelectorAll('input[type="password"][data-clear-on-load="true"]').forEach(function (input) {
        input.value = '';
    });
});
</script>
@endsection
