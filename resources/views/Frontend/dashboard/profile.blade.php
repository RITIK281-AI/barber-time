<div class="dashboard-panel">
    <div class="panel-title">My Profile</div>
    <div class="panel-subtitle">Update your personal information</div>

    @if($errors->profileErrors->any())
        <div class="alert alert-danger rounded-3">
            <ul class="mb-0 ps-3">
                @foreach($errors->profileErrors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- profile photo --}}
        <div class="mb-4 d-flex align-items-center gap-3">
            <div class="position-relative d-inline-block">
                @if(auth()->user()->profile_photo)
                    <img id="photoPreview"
                         src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                         alt="Photo"
                         style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--trim-blue-border);">
                @else
                    <div id="photoPlaceholder"
                         style="width:100px;height:100px;border-radius:50%;background:var(--trim-blue-light);border:3px solid var(--trim-blue-border);display:flex;align-items:center;justify-content:center;font-size:2rem;color:var(--trim-blue);font-weight:700;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <img id="photoPreview" src="" alt="Photo"
                         style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:3px solid var(--trim-blue-border);display:none;">
                @endif

                {{-- edit icon button --}}
                <label for="profile_photo"
                       style="position:absolute;bottom:2px;right:2px;width:28px;height:28px;background:var(--trim-blue);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;cursor:pointer;border:2px solid #fff;"
                       title="Change photo">
                    <i class="bi bi-pencil-fill"></i>
                </label>
                <input type="file" id="profile_photo" name="profile_photo"
                       class="d-none" accept="image/*">
            </div>
            <div>
                <div class="fw-semibold">Profile Photo</div>
                <div class="text-muted small">JPG or PNG, max 2MB</div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control @error('name', 'profileErrors') is-invalid @enderror"
                       value="{{ old('name', auth()->user()->name) }}" required>
                @error('name', 'profileErrors')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control @error('email', 'profileErrors') is-invalid @enderror"
                       value="{{ old('email', auth()->user()->email) }}" required>
                @error('email', 'profileErrors')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Phone Number</label>
                <input type="text" name="phone" class="form-control @error('phone', 'profileErrors') is-invalid @enderror"
                       placeholder="98XXXXXXXX"
                       value="{{ old('phone', auth()->user()->phone) }}">
                @error('phone', 'profileErrors')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Address</label>
                <input type="text" name="address" class="form-control @error('address', 'profileErrors') is-invalid @enderror"
                       placeholder="e.g. Pokhara, Nepal"
                       value="{{ old('address', auth()->user()->address) }}">
                @error('address', 'profileErrors')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="my-4">

        {{-- change password --}}
        <div class="fw-bold mb-1">Change Password</div>
        <div class="text-muted small mb-3">Leave blank to keep your current password</div>

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Current Password</label>
                <div class="position-relative">
                    <input type="password" id="user_current_password" name="current_password"
                           class="form-control pe-5 @error('current_password', 'profileErrors') is-invalid @enderror"
                              placeholder="••••••••" autocomplete="off" data-clear-on-load="true">
                    <button type="button"
                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                            data-password-target="user_current_password"
                            aria-label="Show password"
                            aria-pressed="false"
                            style="z-index:3;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('current_password', 'profileErrors')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">New Password</label>
                <div class="position-relative">
                    <input type="password" id="user_new_password" name="password"
                           class="form-control pe-5 @error('password', 'profileErrors') is-invalid @enderror"
                              placeholder="••••••••" autocomplete="new-password" data-clear-on-load="true">
                    <button type="button"
                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                            data-password-target="user_new_password"
                            aria-label="Show password"
                            aria-pressed="false"
                            style="z-index:3;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password', 'profileErrors')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Confirm New Password</label>
                <div class="position-relative">
                    <input type="password" id="user_password_confirmation" name="password_confirmation" class="form-control pe-5"
                              placeholder="••••••••" autocomplete="new-password" data-clear-on-load="true">
                    <button type="button"
                            class="password-toggle-btn btn btn-link position-absolute top-50 end-0 translate-middle-y border-0 text-muted"
                            data-password-target="user_password_confirmation"
                            aria-label="Show password"
                            aria-pressed="false"
                            style="z-index:3;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-trim-blue px-4">
                <i class="bi bi-check-lg me-1"></i>Save Changes
            </button>
            <a href="{{ route('dashboard') }}?tab=profile"
               class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>

    <hr class="my-4">

    <div class="text-uppercase text-muted fw-semibold mb-3"
         style="font-size:0.72rem;letter-spacing:0.08em">
        Danger Zone
    </div>

    <div class="d-flex justify-content-between align-items-center py-3 border rounded-3 px-3"
         style="border-color:#ffcdd2 !important;background:#fff8f8;">
        <div>
            <div class="fw-semibold text-danger">Delete Account</div>
            <div class="text-muted small">Permanently delete your account and all data. Cannot be undone.</div>
        </div>
        <button type="button" class="btn btn-outline-danger btn-sm"
                data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Delete
        </button>
    </div>
</div>

{{-- delete account modal --}}
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">Delete Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">
                    This will permanently delete your account, all bookings, and personal data.
                    This action <strong>cannot be undone</strong>.
                </p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <label class="form-label fw-semibold">Enter your password to confirm</label>
                    <input type="password" name="password" class="form-control"
                           placeholder="........" required>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="deleteAccountForm" class="btn btn-danger px-4">
                    Yes, Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // show image preview before uploading
    document.getElementById('profile_photo')?.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            const preview = document.getElementById('photoPreview');
            const placeholder = document.getElementById('photoPlaceholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

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

    // Clear any browser-injected password values on load.
    document.querySelectorAll('input[type="password"][data-clear-on-load="true"]').forEach(function (input) {
        input.value = '';
    });
</script>
@endpush
