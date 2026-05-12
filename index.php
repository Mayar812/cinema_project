<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Showtime Management System</title>
    <style>
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
    <header class="navbar">
        <nav class="nav-inner" aria-label="Main navigation">
            <a class="brand" href="#home">
                <span class="brand-mark">CS</span>
                <span>Cinema Showtime</span>
            </a>

            <ul class="nav-links">
                <li><a class="active" href="#home">Home</a></li>
                <li><a href="#movies">Movies</a></li>
                <li><a href="#showtimes">Showtimes</a></li>
                <li><a class="login-link" href="/login">Login</a></li>
            </ul>
        </nav>
    </header>

    <main id="home">
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

        <section class="section" id="movies">
            <div class="section-header">
                <div>
                    <h2>Now Showing</h2>
                    <p>Browse the latest movies and available showtimes.</p>
                </div>
            </div>

            <form class="search-box" action="#" method="get">
                <input type="search" name="search" placeholder="Search movies by title or genre">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>

            <div class="movie-grid">
                <article class="movie-card">
                    <div class="poster" style="background-image: url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="movie-info">
                        <h3>The Last Voyage</h3>
                        <p class="genre">Adventure</p>
                        <span class="time">Today - 06:30 PM</span>
                    </div>
                </article>

                <article class="movie-card">
                    <div class="poster" style="background-image: url('https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="movie-info">
                        <h3>Galaxy Mission</h3>
                        <p class="genre">Sci-Fi</p>
                        <span class="time">Today - 08:00 PM</span>
                    </div>
                </article>

                <article class="movie-card">
                    <div class="poster" style="background-image: url('https://images.unsplash.com/photo-1497015289639-54688650d173?auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="movie-info">
                        <h3>Final Act</h3>
                        <p class="genre">Action</p>
                        <span class="time">Tomorrow - 05:15 PM</span>
                    </div>
                </article>

                <article class="movie-card">
                    <div class="poster" style="background-image: url('https://images.unsplash.com/photo-1524985069026-dd778a71c7b4?auto=format&fit=crop&w=700&q=80');"></div>
                    <div class="movie-info">
                        <h3>City Lights</h3>
                        <p class="genre">Comedy</p>
                        <span class="time">Tomorrow - 09:20 PM</span>
                    </div>
                </article>
            </div>

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

    <footer class="footer">
        <div class="footer-inner">
            <p>&copy; <?php echo date('Y'); ?> Cinema Showtime Management System.</p>
            <div class="socials" aria-label="Social media links">
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
</body>
</html>
