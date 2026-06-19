<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic page settings for the public home page. -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Showtime Management System</title>
    <style>
        /* Public home page styles: navbar, hero, movie cards, features, footer, and responsive layout. */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #0d1017;
            color: #f8fafc;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .navbar {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(8, 11, 18, 0.88);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
        }

        .nav-inner {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            min-height: 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 800;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: #e11d48;
            color: #fff;
            font-weight: 900;
            box-shadow: 0 10px 28px rgba(225, 29, 72, 0.35);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 10px;
            list-style: none;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 0 14px;
            border-radius: 6px;
            color: #cbd5e1;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .nav-links .login-link {
            background: #e11d48;
            color: #fff;
        }

        .hero {
            min-height: 620px;
            display: grid;
            align-items: center;
            background:
                linear-gradient(90deg, rgba(7, 9, 15, 0.96), rgba(7, 9, 15, 0.68), rgba(7, 9, 15, 0.35)),
                url("https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1800&q=80") center/cover;
        }

        .hero-inner {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 86px 0;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            color: #fecdd3;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 13px;
        }

        .eyebrow::before {
            content: "";
            width: 34px;
            height: 2px;
            background: #e11d48;
            border-radius: 999px;
        }

        .hero h1 {
            max-width: 760px;
            font-size: clamp(40px, 7vw, 74px);
            line-height: 1.02;
            margin-bottom: 20px;
        }

        .hero p {
            max-width: 610px;
            color: #dbe4f0;
            font-size: 19px;
            margin-bottom: 32px;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 20px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-weight: 800;
            transition: 0.2s ease;
        }

        .btn-primary {
            background: #e11d48;
            border-color: #e11d48;
            color: #fff;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.25);
        }

        .section {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 70px 0;
        }

        .section-header {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 28px;
        }

        .section-header h2 {
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.1;
        }

        .section-header p {
            max-width: 520px;
            color: #a7b0c0;
        }

        .search-box {
            margin-bottom: 26px;
            display: flex;
            gap: 12px;
            padding: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: #151a24;
        }

        .search-box input {
            width: 100%;
            border: 0;
            outline: 0;
            border-radius: 6px;
            padding: 13px 14px;
            background: #0c111a;
            color: #fff;
            font: inherit;
        }

        .search-box button {
            border: 0;
            cursor: pointer;
            white-space: nowrap;
        }

        .movie-chips {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            margin: 0 0 20px;
            padding: 2px 0;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .movie-chips a {
            flex: 0 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 999px;
            padding: 8px 14px;
            background: #151a24;
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 800;
            transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .movie-chips a:hover,
        .movie-chips a:focus-visible,
        .movie-chips a.is-active {
            border-color: #e11d48;
            background: #e11d48;
            color: #fff;
            outline: none;
            transform: translateY(-2px);
        }

        .slider-section {
            margin: 12px 0 38px;
        }

        .slider-heading {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 3px;
            margin-bottom: 14px;
            text-align: center;
        }

        .slider-heading h3 {
            font-size: clamp(28px, 3vw, 34px);
            line-height: 1.15;
        }

        .slider-heading p {
            color: #a7b0c0;
            font-size: 14px;
        }

        .slider-shell {
            position: relative;
            height: 490px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            background:
                radial-gradient(circle at 50% 38%, rgba(225, 29, 72, 0.18), transparent 34%),
                linear-gradient(145deg, #111722, #090d14);
            perspective: 1200px;
        }

        .slider-track {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
        }

        .slide-card {
            --translate-x: 0px;
            --translate-z: 0px;
            --rotation: 0deg;
            --card-scale: 1;
            --card-opacity: 1;
            --card-layer: 20;
            position: absolute;
            top: 50%;
            left: 50%;
            width: clamp(205px, 21vw, 225px);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 12px;
            background: #151a24;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.42);
            backface-visibility: hidden;
            opacity: var(--card-opacity);
            transform:
                translate3d(calc(-50% + var(--translate-x)), -50%, var(--translate-z))
                rotateY(var(--rotation))
                scale(var(--card-scale));
            transition: transform 0.45s ease, opacity 0.45s ease, border-color 0.3s ease;
            will-change: transform, opacity;
            z-index: var(--card-layer);
            pointer-events: none;
        }

        .slide-card.is-active {
            border-color: rgba(225, 29, 72, 0.75);
            opacity: 1;
            pointer-events: auto;
        }

        .slide-card.is-hidden {
            opacity: 0;
            visibility: hidden;
        }

        .slide-image {
            width: 100%;
            aspect-ratio: 2 / 3;
            overflow: hidden;
            background: #0c111a;
        }

        .slide-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: contain;
            object-position: center;
        }

        .slide-info {
            height: 104px;
            padding: 14px 16px 16px;
        }

        .slide-info span {
            display: block;
            overflow: hidden;
            margin-bottom: 3px;
            color: #fda4af;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .slide-info h3 {
            overflow: hidden;
            margin-bottom: 4px;
            font-size: 21px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .slide-info p {
            overflow: hidden;
            color: #a7b0c0;
            font-size: 13px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .slider-button {
            position: absolute;
            top: 50%;
            z-index: 40;
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 50%;
            background: rgba(8, 11, 18, 0.88);
            color: #fff;
            cursor: pointer;
            font-size: 34px;
            line-height: 1;
            transform: translateY(-50%);
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .slider-button:hover,
        .slider-button:focus-visible {
            background: #e11d48;
            outline: none;
            transform: translateY(-50%) scale(1.06);
        }

        .previous-button {
            left: 18px;
        }

        .next-button {
            right: 18px;
        }

        .slider-dots {
            min-height: 14px;
            display: flex;
            justify-content: center;
            gap: 7px;
            margin-top: 14px;
        }

        .slider-dots button {
            width: 8px;
            height: 8px;
            border: 0;
            border-radius: 999px;
            background: #4b5563;
            cursor: pointer;
            transition: width 0.25s ease, background 0.25s ease;
        }

        .slider-dots button.is-active {
            width: 26px;
            background: #e11d48;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px;
        }

        .movie-card {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            background: #151a24;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.22);
            transition: 0.2s ease;
        }

        .movie-card:hover {
            transform: translateY(-6px);
            border-color: rgba(225, 29, 72, 0.55);
        }

        .poster {
            height: 330px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .poster::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 45%, rgba(0, 0, 0, 0.78));
        }

        .movie-info {
            padding: 18px;
        }

        .movie-info h3 {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .genre {
            color: #fda4af;
            font-weight: 800;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .time {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 7px 11px;
            background: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
            font-size: 14px;
            font-weight: 700;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-top: 34px;
        }

        .feature {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 22px;
            background: #151a24;
        }

        .feature strong {
            display: block;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .feature p {
            color: #a7b0c0;
        }

        .footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background: #07090f;
            padding: 28px 0;
        }

        .footer-inner {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            color: #a7b0c0;
        }

        .socials {
            display: flex;
            gap: 10px;
        }

        .socials a {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: #151a24;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            transition: 0.2s ease;
        }

        .socials a:hover {
            background: #e11d48;
            border-color: #e11d48;
        }

        .socials svg {
            width: 19px;
            height: 19px;
            fill: currentColor;
        }

        @media (max-width: 900px) {
            .nav-inner,
            .section-header,
            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
            }

            .nav-links {
                width: 100%;
                overflow-x: auto;
                padding-bottom: 8px;
            }

            .hero {
                min-height: 560px;
            }

            .movie-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .features {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 560px) {
            .brand {
                font-size: 17px;
            }

            .search-box {
                flex-direction: column;
            }

            .slider-heading {
                align-items: center;
                gap: 3px;
            }

            .movie-chips {
                justify-content: flex-start;
            }

            .slider-shell,
            .slider-track {
                height: 450px;
            }

            .slide-card {
                width: min(200px, calc(100% - 76px));
            }

            .slider-button {
                width: 38px;
                height: 38px;
                font-size: 28px;
            }

            .previous-button {
                left: 8px;
            }

            .next-button {
                right: 8px;
            }

            .movie-grid {
                grid-template-columns: 1fr;
            }

            .poster {
                height: 390px;
            }
        }
    </style>
</head>
<body>
    <!-- Top navigation for the public landing page. -->
    <header class="navbar">
        <nav class="nav-inner" aria-label="Main navigation">
            <!-- Brand link scrolls back to the home section. -->
            <a class="brand" href="#home">
                <span class="brand-mark">CS</span>
                <span>Cinema Showtime</span>
            </a>

            <!-- Navigation links scroll to page sections, while Login opens the Laravel login route. -->
            <ul class="nav-links">
                <li><a class="active" href="#home">Home</a></li>
                <li><a href="#movies">Movies</a></li>
                <li><a href="#showtimes">Showtimes</a></li>
                <li><a class="login-link" href="/login">Login</a></li>
            </ul>
        </nav>
    </header>

    <main id="home">
        <!-- Hero section introduces the project and gives the main actions. -->
        <section class="hero">
            <div class="hero-inner">
                <span class="eyebrow">University CRUD Project</span>
                <h1>Cinema Showtime Management System</h1>
                <p>
                    A smart cinema management system for organizing movie showtimes, halls, and ticket availability.
                </p>
                <div class="hero-actions">
                    <a class="btn btn-primary" href="/login">Login</a>
                    <a class="btn btn-primary" href="#movies">View Movies</a>
                    <a class="btn" href="#showtimes">Check Showtimes</a>
                </div>
            </div>
        </section>

        <!-- Static movie preview section for the public homepage. -->
        <section class="section" id="movies">
            <div class="section-header">
                <div>
                    <h2>Now Showing</h2>
                    <p>Browse the latest movies and available showtimes.</p>
                </div>
            </div>

            <!-- Searching refreshes the page with matching database movies in the slider. -->
            <form class="search-box" action="/#movies" method="get">
                <input type="search" name="search" value="<?php echo htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search movies by title or genre">
                <button class="btn btn-primary" type="submit">Search</button>
                <?php if (! empty($search)): ?>
                    <a class="btn" href="/#movies">Clear</a>
                <?php endif; ?>
            </form>

            <section class="slider-section" aria-label="Latest movies">
                <div class="slider-heading">
                    <h3><?php echo empty($search) ? 'Latest Movies' : 'Search Results'; ?></h3>
                    <p>Use the arrows or dots to browse movie showtimes.</p>
                </div>

                <nav class="movie-chips" aria-label="Filter movies by genre">
                    <?php
                        $genres = isset($movieGenres) && count($movieGenres) > 0
                            ? $movieGenres
                            : ['Drama', 'Action', 'Romance', 'Animation'];
                    ?>
                    <?php foreach ($genres as $genre): ?>
                        <?php $isActiveGenre = isset($search) && strcasecmp($search, $genre) === 0; ?>
                        <a class="<?php echo $isActiveGenre ? 'is-active' : ''; ?>"
                           href="/?search=<?php echo rawurlencode($genre); ?>#movies"
                           <?php echo $isActiveGenre ? 'aria-current="true"' : ''; ?>>
                            <?php echo htmlspecialchars($genre, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <div class="slider-shell coverflow-shell" data-slider>
                    <button class="slider-button previous-button" type="button" data-slider-prev aria-label="Previous movie">&#8249;</button>
                    <div class="slider-track coverflow-track" data-slider-track>
                        <?php if (isset($latestMovies) && $latestMovies->isNotEmpty()): ?>
                            <?php foreach ($latestMovies as $movie): ?>
                                <?php
                                    $movieTitle = htmlspecialchars($movie->movie_title, ENT_QUOTES, 'UTF-8');
                                    $movieGenre = htmlspecialchars($movie->genre, ENT_QUOTES, 'UTF-8');
                                    $imageUrl = $movie->image ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=900&q=80';

                                    if ($movie->image && ! str_starts_with($movie->image, 'http')) {
                                        $imageUrl = asset($movie->image);
                                    }

                                    $imageUrl = htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8');
                                    $showDate = $movie->show_date->format('M j, Y');
                                    $showTime = date('g:i A', strtotime($movie->start_time));
                                ?>
                                <article class="slide-card">
                                    <div class="slide-image">
                                        <img src="<?php echo $imageUrl; ?>" alt="<?php echo $movieTitle; ?> poster" loading="lazy">
                                    </div>
                                    <div class="slide-info">
                                        <span><?php echo $movieGenre; ?> · <?php echo htmlspecialchars($movie->movie_status, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <h3><?php echo $movieTitle; ?></h3>
                                        <p>Hall <?php echo (int) $movie->hall_number; ?> · <?php echo $showDate; ?> · <?php echo $showTime; ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        <?php elseif (! empty($search)): ?>
                            <article class="slide-card">
                                <div class="slide-image">
                                    <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=900&q=80" alt="Empty cinema hall">
                                </div>
                                <div class="slide-info">
                                    <span>No Results</span>
                                    <h3>No Matching Movies</h3>
                                    <p>Try another movie title or genre.</p>
                                </div>
                            </article>
                        <?php else: ?>
                            <article class="slide-card">
                                <div class="slide-image">
                                    <img src="https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=900&q=80" alt="Cinema hall">
                                </div>
                                <div class="slide-info">
                                    <span>Coming Soon</span>
                                    <h3>Add Your First Movie</h3>
                                    <p>Login to add posters, dates, seats, and ticket prices.</p>
                                </div>
                            </article>
                            <article class="slide-card">
                                <div class="slide-image">
                                    <img src="https://images.unsplash.com/photo-1440404653325-ab127d49abc1?auto=format&fit=crop&w=900&q=80" alt="Movie camera">
                                </div>
                                <div class="slide-info">
                                    <span>Dashboard Ready</span>
                                    <h3>Manage Cinema Releases</h3>
                                    <p>Create movie records and they will appear here automatically.</p>
                                </div>
                            </article>
                        <?php endif; ?>
                    </div>
                    <button class="slider-button next-button" type="button" data-slider-next aria-label="Next movie">&#8250;</button>
                </div>
                <div class="slider-dots" data-slider-dots aria-label="Choose movie"></div>
            </section>

            <!-- Feature boxes explain what the CRUD system manages. -->
            <div class="features" id="showtimes">
                <div class="feature">
                    <strong>Movie Records</strong>
                    <p>Display movie titles, genres, poster images, and basic show information.</p>
                </div>
                <div class="feature">
                    <strong>Showtime Details</strong>
                    <p>Present example dates and times in a clean format suitable for CRUD pages.</p>
                </div>
                <div class="feature">
                    <strong>Admin Entry</strong>
                    <p>Keep the homepage simple while linking users to the login page.</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer with dynamic PHP year and simple social media icon links. -->
    <footer class="footer">
        <div class="footer-inner">
            <!-- date('Y') prints the current year automatically. -->
            <p>&copy; <?php echo date('Y'); ?> Cinema Showtime Management System.</p>
            <div class="socials" aria-label="Social media links">
                <!-- SVG icons are drawn directly in the HTML, and the links are placeholders. -->
                <a href="#" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V8.8c0-.9.3-1.5 1.6-1.5h1.7V4.5c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.4H7.3V14h2.8v8h3.4z"/></svg>
                </a>
                <a href="#" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 2h8.4A5.8 5.8 0 0 1 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8A5.8 5.8 0 0 1 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2zm0 2A3.8 3.8 0 0 0 4 7.8v8.4A3.8 3.8 0 0 0 7.8 20h8.4a3.8 3.8 0 0 0 3.8-3.8V7.8A3.8 3.8 0 0 0 16.2 4H7.8zm8.7 2.3a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4zM12 7.2a4.8 4.8 0 1 1 0 9.6 4.8 4.8 0 0 1 0-9.6zm0 2a2.8 2.8 0 1 0 0 5.6 2.8 2.8 0 0 0 0-5.6z"/></svg>
                </a>
                <a href="#" aria-label="X">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2h3.1l-6.8 7.8 8 12.2h-6.2l-4.9-7.3L6.5 22H3.4l7.3-8.4L3 2h6.4l4.4 6.6L18.9 2zm-1.1 17.9h1.7L8.4 4H6.6l11.2 15.9z"/></svg>
                </a>
            </div>
        </div>
    </footer>

    <script>
        const slider = document.querySelector('[data-slider]');

        if (slider) {
            const track = slider.querySelector('[data-slider-track]');
            const slides = Array.from(track.querySelectorAll('.slide-card'));
            const previousButton = slider.querySelector('[data-slider-prev]');
            const nextButton = slider.querySelector('[data-slider-next]');
            const dotsWrap = slider.parentElement.querySelector('[data-slider-dots]');
            let activeIndex = Math.floor(slides.length / 2);
            let touchStartX = null;

            const dots = slides.map((slide, index) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.setAttribute('aria-label', `Show movie ${index + 1}`);
                dot.addEventListener('click', () => {
                    activeIndex = index;
                    updateSlider();
                });
                dotsWrap.appendChild(dot);

                return dot;
            });

            function updateSlider() {
                const cardSpacing = slider.clientWidth <= 560
                    ? 155
                    : Math.min(215, Math.max(140, slider.clientWidth * 0.2));

                slides.forEach((slide, index) => {
                    let offset = index - activeIndex;
                    const halfwayPoint = Math.floor(slides.length / 2);

                    if (offset > halfwayPoint) {
                        offset -= slides.length;
                    } else if (offset < -halfwayPoint) {
                        offset += slides.length;
                    }

                    const absoluteOffset = Math.abs(offset);
                    const scale = Math.max(0.78, 1 - (absoluteOffset * 0.09));
                    const opacity = absoluteOffset === 0 ? 1 : (absoluteOffset === 1 ? 0.72 : 0.42);

                    slide.classList.toggle('is-active', index === activeIndex);
                    slide.classList.toggle('is-hidden', absoluteOffset > 2);
                    slide.style.setProperty('--translate-x', `${offset * cardSpacing}px`);
                    slide.style.setProperty('--translate-z', `${absoluteOffset * -95}px`);
                    slide.style.setProperty('--rotation', `${offset * -9}deg`);
                    slide.style.setProperty('--card-scale', scale);
                    slide.style.setProperty('--card-opacity', opacity);
                    slide.style.setProperty('--card-layer', 20 - absoluteOffset);
                    slide.setAttribute('aria-hidden', index === activeIndex ? 'false' : 'true');
                });

                dots.forEach((dot, index) => {
                    const isActive = index === activeIndex;
                    dot.classList.toggle('is-active', isActive);
                    dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                });
            }

            function showPreviousMovie() {
                activeIndex = activeIndex === 0 ? slides.length - 1 : activeIndex - 1;
                updateSlider();
            }

            function showNextMovie() {
                activeIndex = activeIndex === slides.length - 1 ? 0 : activeIndex + 1;
                updateSlider();
            }

            previousButton.addEventListener('click', showPreviousMovie);
            nextButton.addEventListener('click', showNextMovie);

            slider.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowLeft') {
                    showPreviousMovie();
                } else if (event.key === 'ArrowRight') {
                    showNextMovie();
                }
            });

            slider.addEventListener('touchstart', (event) => {
                touchStartX = event.changedTouches[0].clientX;
            }, { passive: true });

            slider.addEventListener('touchend', (event) => {
                if (touchStartX === null) {
                    return;
                }

                const distance = event.changedTouches[0].clientX - touchStartX;

                if (Math.abs(distance) > 45) {
                    distance > 0 ? showPreviousMovie() : showNextMovie();
                }

                touchStartX = null;
            }, { passive: true });

            if (slides.length < 2) {
                previousButton.hidden = true;
                nextButton.hidden = true;
                dotsWrap.hidden = true;
            }

            window.addEventListener('resize', updateSlider);
            updateSlider();
        }
    </script>
</body>
</html>
