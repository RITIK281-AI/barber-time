<footer class="tt-footer">
    <div class="container">
        <div class="row g-4 py-5">

            <!-- brand col -->
            <div class="col-lg-4 col-md-6">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <h4 class="trimtime-brand mb-3">
                        <i class="bi bi-scissors me-1"></i>TrimTime
                    </h4>
                </a>
                <p class="text small lh-lg">
                    Premium barber booking platform for Nepal.<br>
                    Book your grooming session easily and confidently.
                </p>
                <!-- social icons -->
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="tt-social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="tt-social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="tt-social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="tt-social-icon"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <!-- quick links -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="tt-footer-heading">Quick Links</h6>
                <ul class="list-unstyled tt-footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('frontend.shops.index') }}">Find Barbers</a></li>
                    <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
                    @auth
                    <li><a href="{{ route('frontend.bookings.index') }}">My Bookings</a></li>
                    @endauth
                </ul>
            </div>

            <!-- for barbers -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="tt-footer-heading">For Barbers</h6>
                <ul class="list-unstyled tt-footer-links">
                    <li><a href="{{ route('frontend.shops.partner.create') }}">Partner With Us</a></li>
                    <li><a href="{{ route('login') }}">Barber Login</a></li>
                </ul>
            </div>

            <!-- support + contact info -->
            <div class="col-lg-4 col-md-6">
                <h6 class="tt-footer-heading">Support</h6>
                <ul class="list-unstyled tt-footer-links mb-3">
                    <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
                <div class="tt-footer-contact">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-envelope-fill"></i>
                        <span>support@trimtime.com.np</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Pokhara, Gandaki Province, Nepal</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- bottom bar -->
        <div class="tt-footer-bottom d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
            <p class="mb-0 small">
                © {{ date('Y') }} TrimTime &mdash; Final Year Project, Informatics College Pokhara
            </p>
            <p class="mb-0 small mt-2 mt-md-0 text-muted">
                Built with Laravel &amp; Bootstrap &bull; Made in Nepal
            </p>
        </div>

    </div>
</footer>
