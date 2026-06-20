<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book {{ $movie->movie_title }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: #07080d; color: #f8fafc; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: inherit; text-decoration: none; }
        .shell { min-height: 100vh; display: grid; place-items: center; padding: 28px; background: radial-gradient(circle at 20% 0%, rgba(220, 38, 38, .24), transparent 34%), #07080d; }
        .panel { width: min(860px, 100%); display: grid; grid-template-columns: minmax(190px, 280px) 1fr; gap: 24px; background: #11131b; border: 1px solid rgba(255,255,255,.1); border-radius: 8px; overflow: hidden; box-shadow: 0 24px 70px rgba(0,0,0,.42); }
        .poster { min-height: 420px; background: #1f2937; }
        .poster img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .poster-empty { height: 100%; display: grid; place-items: center; color: #94a3b8; font-weight: 800; padding: 18px; text-align: center; }
        .content { padding: 30px; display: flex; flex-direction: column; justify-content: center; }
        .eyebrow { color: #fca5a5; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; font-size: 13px; margin-bottom: 12px; }
        h1 { margin: 0 0 14px; font-size: clamp(32px, 6vw, 58px); line-height: 1; letter-spacing: 0; }
        .details { display: grid; gap: 10px; color: #cbd5e1; margin: 18px 0 28px; }
        .actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .button { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; border-radius: 999px; padding: 0 18px; font-weight: 900; }
        .primary { background: #dc2626; color: #fff; box-shadow: 0 18px 38px rgba(220, 38, 38, .3); }
        .secondary { background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16); }
        @media (max-width: 720px) {
            .panel { grid-template-columns: 1fr; }
            .poster { min-height: auto; aspect-ratio: 2 / 3; }
            .content { padding: 24px; }
        }
    </style>
</head>
<body>
    @php
        $imageUrl = $movie->image && str_starts_with($movie->image, 'http')
            ? $movie->image
            : ($movie->image ? asset($movie->image) : null);
    @endphp

    <main class="shell">
        <section class="panel">
            <div class="poster">
                @if ($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $movie->movie_title }} poster">
                @else
                    <div class="poster-empty">No poster available</div>
                @endif
            </div>

            <div class="content">
                <span class="eyebrow">Booking</span>
                <h1>{{ $movie->movie_title }}</h1>
                <div class="details">
                    <span>Genre: {{ $movie->genre }}</span>
                    <span>Date: {{ $movie->show_date->format('M j, Y') }}</span>
                    <span>Time: {{ substr($movie->start_time, 0, 5) }} - {{ substr($movie->end_time, 0, 5) }}</span>
                    <span>Available seats: {{ $movie->available_seats }}</span>
                    <span>Ticket price: ${{ number_format($movie->ticket_price, 2) }}</span>
                </div>
                <div class="actions">
                    <a class="button primary" href="#">Continue Booking</a>
                    <a class="button secondary" href="{{ route('user.home') }}">Back to Movies</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
