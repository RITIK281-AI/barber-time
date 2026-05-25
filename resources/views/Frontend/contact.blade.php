@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')

{{-- hero --}}
<section class="tt-cta-section py-5">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold text-white mt-4 mb-3">Get In Touch</h1>
        <p class="lead text-white-50 mx-auto" style="max-width: 520px;">
            Have questions or need help? Send us a message and we'll get back to you within 24 hours.
        </p>
    </div>
</section>

{{-- main content --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5">

            {{-- left: contact info --}}
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 90px;">
                    <h4 class="fw-bold mb-4">Contact Information</h4>

                    <div class="d-flex gap-3 mb-4">
                        <div class="tt-contact-icon flex-shrink-0">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Address</h6>
                            <p class="text-muted mb-0">Informatics College Pokhara<br>Pokhara, Gandaki Province, Nepal</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="tt-contact-icon flex-shrink-0">
                            <i class="bi bi-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">WhatsApp Support</h6>
                            <a href="https://wa.me/977?text=Hello%20BarberTime" target="_blank" class="text-trim-blue text-decoration-none fw-semibold">
                                Message us on WhatsApp
                            </a>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="tt-contact-icon flex-shrink-0">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Phone</h6>
                            <p class="text-muted mb-0">+977 61 123456</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="tt-contact-icon flex-shrink-0">
                            <i class="bi bi-envelope-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Email</h6>
                            <p class="text-muted mb-0">trimtime66@gmail.com</p>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mb-4">
                        <div class="tt-contact-icon flex-shrink-0">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-semibold mb-1">Support Hours</h6>
                            <p class="text-muted mb-0">Sun – Fri: 9:00 AM – 6:00 PM</p>
                            <p class="text-muted mb-0">Saturday: Closed</p>
                        </div>
                    </div>

                    {{-- social links --}}
                    <h6 class="fw-semibold mb-3 mt-4">Follow Us</h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="tt-social-icon"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="tt-social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="tt-social-icon"><i class="bi bi-twitter-x"></i></a>
                    </div>

                    {{-- FAQ Link --}}
                    <div class="mt-5 pt-3 border-top">
                        <p class="text-muted mb-2">Have general questions?</p>
                        <a href="{{ route('faq') }}" class="btn btn-outline-trim-blue btn-sm">
                            <i class="bi bi-question-circle me-1"></i> Visit FAQ Page
                        </a>
                    </div>
                </div>
            </div>

            {{-- right: form --}}
            <div class="col-lg-8">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill text-success fs-5"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong><i class="bi bi-exclamation-circle me-2"></i>Please fix the following:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <h4 class="fw-bold mb-1">Send Us a Message</h4>
                        <p class="text-muted mb-4">We read every message and reply personally.</p>

                        <form method="POST" action="{{ route('contact.store') }}" novalidate>
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', auth()->user()?->name) }}"
                                        placeholder="Your full name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', auth()->user()?->email) }}"
                                        placeholder="your@email.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="subject" class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <input type="text" id="subject" name="subject"
                                    class="form-control @error('subject') is-invalid @enderror"
                                    value="{{ old('subject') }}"
                                    placeholder="e.g. Booking issue, Partnership inquiry..." required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea id="message" name="message" rows="6"
                                    class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Tell us more about your inquiry...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn tt-btn-primary btn-lg w-100 py-3">
                                <i class="bi bi-send me-2"></i> Send Message
                            </button>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
