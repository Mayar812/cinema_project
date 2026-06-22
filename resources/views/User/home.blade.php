<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Home</title>
    <style>
        body { margin: 0; background: #07080d; color: #f8fafc; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        .page-shell { min-height: 100vh; background: radial-gradient(circle at 20% 0%, rgba(220, 38, 38, .2), transparent 34%), #07080d; }
        .nav { height: 72px; padding: 0 clamp(18px, 4vw, 56px); display: flex; align-items: center; justify-content: space-between; background: rgba(7, 8, 13, .82); border-bottom: 1px solid rgba(255,255,255,.08); position: sticky; top: 0; z-index: 20; backdrop-filter: blur(16px); }
        .brand { font-size: clamp(20px, 3vw, 28px); font-weight: 900; letter-spacing: 0; }
        .brand span { color: #ef4444; }
        .nav-actions { display: flex; align-items: center; gap: 14px; color: #cbd5e1; font-size: 14px; }
        .logout { border: 1px solid rgba(255,255,255,.18); background: rgba(255,255,255,.06); color: #fff; border-radius: 999px; padding: 9px 14px; cursor: pointer; font-weight: 700; }
        .hero { position: relative; min-height: min(680px, calc(100vh - 72px)); overflow: hidden; }
        .carousel { height: min(680px, calc(100vh - 72px)); min-height: 520px; position: relative; }
        .slide { position: absolute; inset: 0; opacity: 0; pointer-events: none; transition: opacity .7s ease; }
        .slide.is-active { opacity: 1; pointer-events: auto; }
        .slide-image { position: absolute; inset: 0; display: flex; align-items: center; justify-content: flex-end; padding: clamp(24px, 6vw, 72px); background: radial-gradient(circle at 74% 50%, rgba(239, 68, 68, .2), transparent 34%), #07080d; }
        .slide-image img { width: min(34vw, 360px); max-width: calc(100% - 36px); height: min(78%, 520px); object-fit: contain; object-position: center; border-radius: 8px; filter: saturate(1.05) drop-shadow(0 28px 56px rgba(0,0,0,.55)); }
        .slide-image::after { content: ""; position: absolute; inset: 0; background: linear-gradient(90deg, rgba(7,8,13,.97) 0%, rgba(7,8,13,.82) 46%, rgba(7,8,13,.24) 100%), linear-gradient(0deg, #07080d 0%, transparent 34%); }
        .slide-content { position: relative; z-index: 2; width: min(900px, calc(100% - 36px)); margin: 0 auto; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: flex-start; padding: 56px 0 96px; }
        .eyebrow { color: #fca5a5; font-weight: 800; text-transform: uppercase; font-size: 13px; letter-spacing: .12em; margin-bottom: 14px; }
        .slide h1 { font-size: clamp(42px, 8vw, 86px); line-height: .95; max-width: 760px; margin: 0 0 18px; letter-spacing: 0; }
        .slide p { max-width: 620px; color: #d1d5db; font-size: clamp(16px, 2vw, 20px); line-height: 1.65; margin: 0 0 24px; }
        .meta { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 28px; }
        .meta span { border: 1px solid rgba(255,255,255,.16); background: rgba(255,255,255,.08); color: #e5e7eb; border-radius: 999px; padding: 8px 12px; font-size: 14px; font-weight: 700; }
        .hero-button, .book-button { display: inline-flex; align-items: center; justify-content: center; border-radius: 999px; background: #dc2626; color: #fff; min-height: 44px; padding: 0 18px; font-weight: 900; box-shadow: 0 18px 38px rgba(220, 38, 38, .3); transition: transform .18s ease, filter .18s ease; }
        .hero-button:hover, .book-button:hover { transform: translateY(-2px); filter: brightness(1.08); }
        .carousel-control { position: absolute; top: 50%; z-index: 4; transform: translateY(-50%); width: 46px; height: 46px; border-radius: 50%; border: 1px solid rgba(255,255,255,.22); background: rgba(7,8,13,.62); color: #fff; cursor: pointer; font-size: 30px; display: grid; place-items: center; }
        .carousel-control:hover { background: #dc2626; }
        .carousel-control.prev { left: 18px; }
        .carousel-control.next { right: 18px; }
        .dots { position: absolute; z-index: 5; left: 50%; bottom: 32px; transform: translateX(-50%); display: flex; gap: 8px; }
        .dots button { width: 34px; height: 5px; border: 0; border-radius: 999px; background: rgba(255,255,255,.32); cursor: pointer; padding: 0; }
        .dots button.is-active { background: #ef4444; }
        .section { width: min(1180px, calc(100% - 36px)); margin: 0 auto; padding: 64px 0 82px; }
        .section-header { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 24px; }
        .section-header h2 { margin: 0 0 8px; font-size: clamp(28px, 4vw, 44px); letter-spacing: 0; }
        .section-header p { margin: 0; color: #94a3b8; }
        .movie-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 22px; }
        .movie-card { background: #11131b; border: 1px solid rgba(255,255,255,.1); border-radius: 8px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,.32); transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
        .movie-card:hover { transform: translateY(-7px); border-color: rgba(239,68,68,.72); box-shadow: 0 26px 58px rgba(0,0,0,.48); }
        .poster { aspect-ratio: 2 / 3; background: #1f2937; overflow: hidden; }
        .poster img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
        .movie-card:hover .poster img { transform: scale(1.05); }
        .poster-empty { height: 100%; display: grid; place-items: center; color: #94a3b8; font-weight: 800; text-align: center; padding: 16px; }
        .movie-info { padding: 16px; }
        .movie-info h3 { margin: 0 0 10px; font-size: 20px; letter-spacing: 0; }
        .movie-details { display: grid; gap: 7px; color: #cbd5e1; font-size: 14px; margin-bottom: 16px; }
        .booking-status-list { display: grid; gap: 12px; margin-bottom: 34px; }
        .booking-status-card { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 14px; align-items: center; border: 1px solid rgba(255,255,255,.1); background: rgba(17,19,27,.78); border-radius: 8px; padding: 14px 16px; }
        .booking-status-card strong, .booking-status-card span { display: block; }
        .booking-status-card strong { font-size: 18px; }
        .booking-status-card span { margin-top: 4px; color: #cbd5e1; font-size: 14px; }
        .booking-status-pill { border-radius: 999px; padding: 7px 11px; font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .booking-status-pending { border: 1px solid rgba(245,158,11,.42); background: rgba(245,158,11,.12); color: #fde68a; }
        .booking-status-accepted { border: 1px solid rgba(16,185,129,.42); background: rgba(16,185,129,.12); color: #a7f3d0; }
        .booking-status-rejected { border: 1px solid rgba(239,68,68,.42); background: rgba(239,68,68,.12); color: #fecaca; }
        .empty-state { border: 1px dashed rgba(255,255,255,.18); border-radius: 8px; padding: 36px; color: #cbd5e1; background: rgba(255,255,255,.04); }
        @media (max-width: 760px) {
            .nav { height: auto; min-height: 68px; align-items: flex-start; flex-direction: column; padding-top: 14px; padding-bottom: 14px; }
            .carousel, .hero { min-height: 620px; height: auto; }
            .slide-image { justify-content: center; align-items: flex-start; padding-top: 18px; }
            .slide-image img { width: min(54vw, 240px); height: min(42%, 280px); opacity: .6; }
            .slide-content { padding-top: 92px; }
            .carousel-control { top: auto; bottom: 22px; }
            .carousel-control.prev { left: 18px; }
            .carousel-control.next { right: 18px; }
            .dots { bottom: 42px; }
            .section-header { align-items: flex-start; flex-direction: column; }
            .booking-status-card { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page-shell">
        <header class="nav">
            <a class="brand" href="{{ route('user.home') }}">Cinema<span>Booking</span></a>
            <div class="nav-actions">
                @isset($username)
                    <span>Welcome, {{ $username }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="logout" type="submit">Logout</button>
                    </form>
                @else
                    <a class="logout" href="{{ route('login') }}">Login</a>
                @endisset
            </div>
        </header>

        <main>
            <section class="hero" aria-label="Coming soon movies">
                @if ($comingSoonMovies->isNotEmpty())
                    <div class="carousel" data-user-carousel>
                        @foreach ($comingSoonMovies as $movie)
                            @php
                                $imageUrl = $movie->image && str_starts_with($movie->image, 'http')
                                    ? $movie->image
                                    : ($movie->image ? asset($movie->image) : null);
                            @endphp

                            <article class="slide @if ($loop->first) is-active @endif" data-carousel-slide>
                                <div class="slide-image">
                                    @if ($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $movie->movie_title }} poster">
                                    @endif
                                </div>
                                <div class="slide-content">
                                    <span class="eyebrow">Coming Soon</span>
                                    <h1>{{ $movie->movie_title }}</h1>
                                    <p>{{ $movie->description ?? 'Release details and synopsis will be announced soon.' }}</p>
                                    <div class="meta">
                                        <span>{{ $movie->genre }}</span>
                                        <span>Release {{ $movie->show_date->format('M j, Y') }}</span>
                                        <span>Hall {{ $movie->hall_number }}</span>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        <button class="carousel-control prev" type="button" data-carousel-prev aria-label="Previous movie">&#8249;</button>
                        <button class="carousel-control next" type="button" data-carousel-next aria-label="Next movie">&#8250;</button>
                        <div class="dots" data-carousel-dots aria-label="Carousel pagination"></div>
                    </div>
                @else
                    <div class="carousel">
                        <article class="slide is-active">
                            <div class="slide-content">
                                <span class="eyebrow">Coming Soon</span>
                                <h1>No upcoming movies yet</h1>
                                <p>Movies marked as Coming Soon in the database will appear here automatically.</p>
                            </div>
                        </article>
                    </div>
                @endif
            </section>

            <section class="section" aria-labelledby="available-movies">
                @if ($userBookings->isNotEmpty())
                    <div class="section-header">
                        <div>
                            <h2>My Booking Status</h2>
                            <p>Admin approval status for your latest movie bookings.</p>
                        </div>
                    </div>

                    <div class="booking-status-list">
                        @foreach ($userBookings as $booking)
                            <article class="booking-status-card">
                                <div>
                                    <strong>{{ $booking->showtime?->movie_title ?? 'Deleted movie' }}</strong>
                                    <span>
                                        Seat {{ $booking->seat_numbers }} /
                                        {{ $booking->chair_type }} /
                                        {{ $booking->snacks ?: 'No snacks' }} /
                                        {{ ucfirst($booking->payment_status) }}{{ $booking->payment_method ? ' by '.$booking->payment_method : '' }}
                                    </span>
                                </div>
                                <span class="booking-status-pill booking-status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                            </article>
                        @endforeach
                    </div>
                @endif

                <div class="section-header">
                    <div>
                        <h2 id="available-movies">Available Movies</h2>
                        <p>Choose a movie that is showing now and continue to booking.</p>
                    </div>
                </div>

                @if ($availableMovies->isNotEmpty())
                    <div class="movie-grid">
                        @foreach ($availableMovies as $movie)
                            @php
                                $imageUrl = $movie->image && str_starts_with($movie->image, 'http')
                                    ? $movie->image
                                    : ($movie->image ? asset($movie->image) : null);
                            @endphp

                            <article class="movie-card">
                                <a href="{{ route('movies.booking', $movie) }}" aria-label="Book {{ $movie->movie_title }}">
                                    <div class="poster">
                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $movie->movie_title }} poster" loading="lazy">
                                        @else
                                            <div class="poster-empty">No poster available</div>
                                        @endif
                                    </div>
                                </a>
                                <div class="movie-info">
                                    <h3>
                                        <a href="{{ route('movies.booking', $movie) }}">{{ $movie->movie_title }}</a>
                                    </h3>
                                    <div class="movie-details">
                                        <span>Genre: {{ $movie->genre }}</span>
                                        <span>Duration: {{ $movie->duration_in_minutes ? $movie->duration_in_minutes.' min' : 'TBA' }}</span>
                                        <span>Rating: {{ $movie->rating ?? $movie->age_rating ?? 'Not rated' }}</span>
                                    </div>
                                    <a class="book-button" href="{{ route('movies.booking', $movie) }}">Book Now</a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">No movies are currently available for booking.</div>
                @endif
            </section>
        </main>
    </div>

    <script>
        const carousel = document.querySelector('[data-user-carousel]');

        if (carousel) {
            const slides = Array.from(carousel.querySelectorAll('[data-carousel-slide]'));
            const previousButton = carousel.querySelector('[data-carousel-prev]');
            const nextButton = carousel.querySelector('[data-carousel-next]');
            const dotsWrap = carousel.querySelector('[data-carousel-dots]');
            let activeIndex = 0;
            let timer = null;

            slides.forEach((slide, index) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.setAttribute('aria-label', `Show movie ${index + 1}`);
                dot.addEventListener('click', () => showSlide(index));
                dotsWrap.appendChild(dot);
            });

            const dots = Array.from(dotsWrap.children);

            function showSlide(index) {
                activeIndex = (index + slides.length) % slides.length;
                slides.forEach((slide, slideIndex) => slide.classList.toggle('is-active', slideIndex === activeIndex));
                dots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === activeIndex));
                restartTimer();
            }

            function restartTimer() {
                clearInterval(timer);
                timer = setInterval(() => showSlide(activeIndex + 1), 5000);
            }

            previousButton.addEventListener('click', () => showSlide(activeIndex - 1));
            nextButton.addEventListener('click', () => showSlide(activeIndex + 1));
            showSlide(0);
        }
    </script>
</body>
</html>
