{{-- This shared form is used for both adding and editing showtimes. --}}
<x-admin::layouts.app :title="$showtime->exists ? 'Edit Showtime' : 'Add Showtime'" :username="$username">
    <main class="container">
        <section class="panel">
            {{-- The heading changes depending on whether the model already exists. --}}
            <div class="toolbar">
                <div>
                    <h1>{{ $showtime->exists ? 'Edit Showtime' : 'Add Showtime' }}</h1>
                    <p class="muted">Enter the screening schedule details.</p>
                </div>
                {{-- Return to the dashboard list. --}}
                <a class="button secondary" href="{{ route('showtimes.index') }}">Back</a>
            </div>

            <div class="movie-lookup">
                <div class="field">
                    <label for="movie_lookup">Find Movie</label>
                    <div class="lookup-row">
                        <input id="movie_lookup" type="search" placeholder="Search OMDb by title">
                        <button class="button secondary" id="movie_lookup_button" type="button">Find</button>
                    </div>
                    <div class="muted lookup-message" id="movie_lookup_message"></div>
                </div>
                <div class="movie-results" id="movie_results"></div>
            </div>

            {{-- Existing records submit to update; new records submit to store. --}}
            <form action="{{ $showtime->exists ? route('showtimes.update', $showtime) : route('showtimes.store') }}" method="POST" enctype="multipart/form-data">
                {{-- CSRF protects the form submission. --}}
                @csrf
                {{-- Laravel uses PUT for update because HTML forms only support GET and POST. --}}
                @if ($showtime->exists)
                    @method('PUT')
                @endif

                {{-- Basic movie information fields. --}}
                <div class="row">
                    <div class="field">
                        <label for="movie_title">Movie Title</label>
                        {{-- old() keeps the user's input if validation fails. --}}
                        <input id="movie_title" name="movie_title" maxlength="100" value="{{ old('movie_title', $showtime->movie_title) }}">
                        @error('movie_title') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="genre">Genre</label>
                        <input id="genre" name="genre" maxlength="50" value="{{ old('genre', $showtime->genre) }}">
                        @error('genre') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="field">
                    <label for="image_file">Movie Image</label>
                    <input id="image_file" type="file" name="image_file" accept="image/*">
                    {{-- OMDb's Poster URL is placed here by JavaScript and saved in the same image column. --}}
                    <input type="hidden" name="image" id="image" value="{{ old('image', $showtime->image) }}">
                    <input type="hidden" name="image_changed" id="image_changed" value="0">
                    <div class="image-preview-wrap" id="image_preview_wrap" @style(['display: none' => ! old('image', $showtime->image)])>
                        <img class="image-preview" id="image_preview" src="{{ old('image', $showtime->image) }}" alt="Movie poster preview">
                    </div>
                    <div class="muted image-help">Upload JPG, PNG, WebP, or GIF up to 5 MB. Selecting an OMDb movie uses its poster automatically.</div>
                    @error('image') <div class="error">{{ $message }}</div> @enderror
                    @error('image_file') <div class="error">{{ $message }}</div> @enderror
                </div>

                {{-- Hall number and show date fields. --}}
                <div class="row">
                    <div class="field">
                        <label for="hall_number">Hall Number</label>
                        <input id="hall_number" name="hall_number" type="number" min="1" value="{{ old('hall_number', $showtime->hall_number) }}">
                        @error('hall_number') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="show_date">Show Date</label>
                        {{-- optional() avoids errors when show_date is empty in create mode. --}}
                        <input id="show_date" name="show_date" type="date" value="{{ old('show_date', optional($showtime->show_date)->format('Y-m-d')) }}">
                        @error('show_date') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Start and end time fields. --}}
                <div class="row">
                    <div class="field">
                        <label for="start_time">Start Time</label>
                        {{-- substr keeps only HH:MM for the HTML time input. --}}
                        <input id="start_time" name="start_time" type="time" value="{{ old('start_time', $showtime->start_time ? substr($showtime->start_time, 0, 5) : '') }}">
                        @error('start_time') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="end_time">End Time</label>
                        {{-- The controller validates that end time is after start time. --}}
                        <input id="end_time" name="end_time" type="time" value="{{ old('end_time', $showtime->end_time ? substr($showtime->end_time, 0, 5) : '') }}">
                        @error('end_time') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Seats and price fields. --}}
                <div class="row">
                    <div class="field">
                        <label for="available_seats">Available Seats</label>
                        <input id="available_seats" name="available_seats" type="number" min="0" value="{{ old('available_seats', $showtime->available_seats) }}">
                        @error('available_seats') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="ticket_price">Ticket Price</label>
                        <input id="ticket_price" name="ticket_price" type="number" min="0.01" max="9999.99" step="0.01" value="{{ old('ticket_price', $showtime->ticket_price) }}">
                        @error('ticket_price') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Movie status must match one of the allowed validation values. --}}
                <div class="field">
                    <label for="movie_status">Movie Status</label>
                    <select id="movie_status" name="movie_status">
                        <option value="">Select status</option>
                        {{-- @selected keeps the correct option selected in edit mode or after validation errors. --}}
                        <option value="Showing" @selected(old('movie_status', $showtime->movie_status) === 'Showing')>Showing</option>
                        <option value="Coming Soon" @selected(old('movie_status', $showtime->movie_status) === 'Coming Soon')>Coming Soon</option>
                    </select>
                    @error('movie_status') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div class="actions">
                    {{-- Button text changes between create and update mode. --}}
                    <button class="button primary" type="submit">{{ $showtime->exists ? 'Update Showtime' : 'Create Showtime' }}</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        const lookupInput = document.getElementById('movie_lookup');
        const lookupButton = document.getElementById('movie_lookup_button');
        const lookupMessage = document.getElementById('movie_lookup_message');
        const movieResults = document.getElementById('movie_results');
        const movieTitleInput = document.getElementById('movie_title');
        const genreInput = document.getElementById('genre');
        const imageInput = document.getElementById('image');
        const imageFileInput = document.getElementById('image_file');
        const imageChangedInput = document.getElementById('image_changed');
        const imagePreview = document.getElementById('image_preview');
        const imagePreviewWrap = document.getElementById('image_preview_wrap');
        let previewObjectUrl = null;

        function updateImagePreview(imageUrl = imageInput.value.trim()) {
            imagePreview.src = imageUrl;
            imagePreviewWrap.style.display = imageUrl ? 'block' : 'none';
        }

        function escapeHtml(value) {
            return String(value || '').replace(/[&<>"']/g, (character) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[character]));
        }

        async function searchMovies() {
            const title = lookupInput.value.trim();

            movieResults.innerHTML = '';
            lookupMessage.textContent = '';

            if (title.length < 2) {
                lookupMessage.textContent = 'Enter at least 2 characters.';
                return;
            }

            lookupButton.disabled = true;
            lookupMessage.textContent = 'Searching...';

            try {
                const response = await fetch(`/api/movies/search?title=${encodeURIComponent(title)}`);
                const payload = await response.json();

                if (!response.ok) {
                    lookupMessage.textContent = payload.message || 'No movies found.';
                    return;
                }

                lookupMessage.textContent = `${payload.total_results} result(s) found.`;
                movieResults.innerHTML = payload.data.map((movie) => `
                    <button class="movie-result" type="button" data-imdb-id="${escapeHtml(movie.imdb_id)}">
                        ${movie.poster ? `<img src="${escapeHtml(movie.poster)}" alt="">` : '<span class="poster-empty">No poster</span>'}
                        <span>
                            <strong>${escapeHtml(movie.title)}</strong>
                            <small>${escapeHtml(movie.year)}</small>
                        </span>
                    </button>
                `).join('');
            } catch (error) {
                lookupMessage.textContent = 'Could not search OMDb right now.';
            } finally {
                lookupButton.disabled = false;
            }
        }

        async function selectMovie(imdbId) {
            lookupMessage.textContent = 'Loading movie details...';

            try {
                const response = await fetch(`/api/movies/${imdbId}`);
                const payload = await response.json();

                if (!response.ok) {
                    lookupMessage.textContent = payload.message || 'Movie details unavailable.';
                    return;
                }

                movieTitleInput.value = payload.data.title || movieTitleInput.value;
                genreInput.value = payload.data.genre ? payload.data.genre.split(',')[0].trim() : genreInput.value;
                // OMDb calls this field Poster; the API endpoint normalizes "N/A" to null.
                imageFileInput.value = '';
                imageInput.value = payload.data.poster && payload.data.poster !== 'N/A' ? payload.data.poster : '';
                imageChangedInput.value = '1';
                updateImagePreview();
                lookupMessage.textContent = 'Movie details added to the form.';
            } catch (error) {
                lookupMessage.textContent = 'Could not load movie details.';
            }
        }

        lookupButton.addEventListener('click', searchMovies);
        imageFileInput.addEventListener('change', () => {
            if (previewObjectUrl) {
                URL.revokeObjectURL(previewObjectUrl);
                previewObjectUrl = null;
            }

            const imageFile = imageFileInput.files[0];
            imageInput.value = '';
            imageChangedInput.value = '1';

            if (imageFile) {
                previewObjectUrl = URL.createObjectURL(imageFile);
                updateImagePreview(previewObjectUrl);
            } else {
                updateImagePreview('');
            }
        });
        lookupInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchMovies();
            }
        });
        movieResults.addEventListener('click', (event) => {
            const result = event.target.closest('[data-imdb-id]');

            if (result) {
                selectMovie(result.dataset.imdbId);
            }
        });
    </script>
</x-admin::layouts.app>
