@extends('admin.layouts.app')

@section('content')

<div class="container-fluid px-0">

    <!-- Back Link -->
    <a href="{{ route('admin.partners.index') }}" class="btn btn-sm btn-outline-secondary mb-4">
        <i class="bi bi-arrow-left me-1"></i> Back to Partner Requests
    </a>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Shop Details Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 px-4">
            <h4 class="fw-bold mb-0">{{ $barberShop->name }}</h4>
            <span class="badge rounded-pill px-3 py-2 fw-medium
                {{ $barberShop->status === 'approved' ? 'bg-success' : '' }}
                {{ $barberShop->status === 'pending'  ? 'bg-warning text-dark' : '' }}
                {{ $barberShop->status === 'rejected' ? 'bg-danger' : '' }}">
                {{ ucfirst($barberShop->status) }}
            </span>
        </div>

        <div class="card-body p-4">
            <div class="row g-4">

                <!-- Owner Info -->
                <div class="col-12">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-3">Owner Information</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Full Name</small>
                            <span class="fw-medium">{{ $barberShop->owner_name }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Email</small>
                            <span class="fw-medium">{{ $barberShop->email }}</span>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Phone</small>
                            <span class="fw-medium">{{ $barberShop->phone }}</span>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Shop Info -->
                <div class="col-12">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-3">Shop Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Address</small>
                            <span class="fw-medium">{{ $barberShop->address }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">District</small>
                            <span class="fw-medium">{{ $barberShop->district ?? '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Shop Phone</small>
                            <span class="fw-medium">{{ $barberShop->phone ?? '—' }}</span>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Business Info -->
                <div class="col-12">
                    <h6 class="text-muted text-uppercase small fw-semibold mb-3">Business Information</h6>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <small class="text-muted d-block">Number of Barbers</small>
                            <span class="fw-medium">{{ $barberShop->number_of_barbers }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Number of Chairs</small>
                            <span class="fw-medium">{{ $barberShop->number_of_chairs ?? '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">PAN Number</small>
                            <span class="fw-medium">{{ $barberShop->pan_number ?? '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Business License No.</small>
                            <span class="fw-medium">{{ $barberShop->business_license_number ?? '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Registration Date</small>
                            <span class="fw-medium">{{ $barberShop->business_registration_date ? \Carbon\Carbon::parse($barberShop->business_registration_date)->format('d M Y') : '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Shop Area</small>
                            <span class="fw-medium">{{ $barberShop->shop_area_sqft ? $barberShop->shop_area_sqft . ' sq ft' : '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Years of Experience</small>
                            <span class="fw-medium">{{ $barberShop->years_of_experience ?? '—' }}</span>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted d-block">Submitted On</small>
                            <span class="fw-medium">{{ $barberShop->created_at->format('d M Y, h:i A') }}</span>
                        </div>
                        @if($barberShop->emergency_contact_name || $barberShop->emergency_contact_phone)
                        <div class="col-md-6">
                            <small class="text-muted d-block">Emergency Contact</small>
                            <span class="fw-medium">{{ $barberShop->emergency_contact_name ?? '—' }} / {{ $barberShop->emergency_contact_phone ?? '—' }}</span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <small class="text-muted d-block">Services Offered</small>
                        <span>{{ $barberShop->services_offered ?? '—' }}</span>
                    </div>

                    @if($barberShop->description)
                        <div class="mt-3">
                            <small class="text-muted d-block">Why They Want to Join</small>
                            <span>{{ $barberShop->description }}</span>
                        </div>
                    @endif
                </div>

                <!-- Shop Image -->
                @if($barberShop->shop_image)
                    <hr>
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase small fw-semibold mb-3">Shop Photo</h6>
                        <img src="{{ asset('storage/' . $barberShop->shop_image) }}"
                             alt="{{ $barberShop->name }}"
                             class="rounded shadow-sm"
                             style="max-height: 250px; object-fit: cover;">
                    </div>
                @endif

                <!-- Shop Documents -->
                @if($barberShop->shop_license || $barberShop->registration_document || $barberShop->tax_clearance_document)
                    <hr>
                    <div class="col-12">
                        <h6 class="text-muted text-uppercase small fw-semibold mb-3">Uploaded Documents</h6>
                        <div class="row g-3">
                            @if($barberShop->shop_license)
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center bg-light">
                                        <i class="bi bi-shield-lock text-success fs-1 mb-2 d-block"></i>
                                        <span class="d-block fw-semibold mb-2">Shop License</span>
                                        <a href="{{ asset('storage/' . $barberShop->shop_license) }}" target="_blank" class="btn btn-sm btn-outline-success w-100">View Document</a>
                                    </div>
                                </div>
                            @endif

                            @if($barberShop->registration_document)
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center bg-light">
                                        <i class="bi bi-file-earmark-check text-primary fs-1 mb-2 d-block"></i>
                                        <span class="d-block fw-semibold mb-2">Registration</span>
                                        <a href="{{ asset('storage/' . $barberShop->registration_document) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">View Document</a>
                                    </div>
                                </div>
                            @endif

                            @if($barberShop->tax_clearance_document)
                                <div class="col-md-4">
                                    <div class="border rounded p-3 text-center bg-light">
                                        <i class="bi bi-file-earmark-ruled text-danger fs-1 mb-2 d-block"></i>
                                        <span class="d-block fw-semibold mb-2">Tax Clearance</span>
                                        <a href="{{ asset('storage/' . $barberShop->tax_clearance_document) }}" target="_blank" class="btn btn-sm btn-outline-danger w-100">View Document</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Admin Remarks (if already reviewed) -->
                @if($barberShop->admin_remarks)
                    <hr>
                    <div class="col-12">
                        <div class="bg-light rounded-3 p-3">
                            <small class="text-muted d-block mb-1">Admin Remarks</small>
                            <span>{{ $barberShop->admin_remarks }}</span>
                            @if($barberShop->reviewed_at)
                                <small class="text-muted d-block mt-2">
                                    Reviewed on {{ $barberShop->reviewed_at->format('d M Y, h:i A') }}
                                </small>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Approve / Reject Actions (only for pending) -->
    @if($barberShop->status === 'pending')
        <div class="row g-4">

            <!-- Approve -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 border-start border-success border-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold text-success mb-2">Approve Partner</h5>
                        <p class="text-muted small mb-3">
                            This will create a Barber Shop account for <strong>{{ $barberShop->owner_name }}</strong>
                            and send login credentials (a random 4-digit PIN) to their email.
                            You will not see the PIN.
                        </p>
                        <form action="{{ route('admin.partners.approve', $barberShop) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="approve_remarks" class="form-label small fw-medium">Remarks (Optional)</label>
                                <textarea name="admin_remarks" id="approve_remarks" rows="2"
                                          class="form-control"
                                          placeholder="Any notes about this approval..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100"
                                    onclick="return confirm('Are you sure? Login credentials will be emailed immediately.')">
                                <i class="bi bi-check-circle me-1"></i> Approve & Send Credentials
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Reject -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 border-start border-danger border-4">
                    <div class="card-body p-4">
                        <h5 class="fw-semibold text-danger mb-2">Reject Partner</h5>
                        <p class="text-muted small mb-3">
                            This will reject the request and notify <strong>{{ $barberShop->owner_name }}</strong>
                            via email with the reason.
                        </p>
                        <form action="{{ route('admin.partners.reject', $barberShop) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="reject_remarks" class="form-label small fw-medium">
                                    Reason for Rejection <span class="text-danger">*</span>
                                </label>
                                <textarea name="admin_remarks" id="reject_remarks" rows="2"
                                          class="form-control"
                                          placeholder="Please provide a reason..." required></textarea>
                                @error('admin_remarks')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Are you sure you want to reject this request?')">
                                <i class="bi bi-x-circle me-1"></i> Reject Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>

@endsection
