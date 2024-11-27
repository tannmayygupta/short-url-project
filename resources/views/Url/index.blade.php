<x-app-layout>
    <div class="container my-5">
        <h1 class="mb-4">URL Shortener</h1>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- URL Form -->
        @auth
        <!-- Show URL Shortener Form -->
        <form method="POST" action="{{ route('Url.store') }}">
            @csrf
            <div class="mb-3">
                <label for="original_url" class="form-label">Enter URL</label>
                <input type="url" class="form-control" id="original_url" name="original_url" placeholder="https://example.com" required>
            </div>
            <button type="submit" class="btn btn-primary">Shorten</button>
        </form>
        @endauth

        <!-- List of Shortened URLs -->
        <h2 class="mt-5">Shortened URLs</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Original URL</th>
                    <th>Short URL</th>
                    <th>Copy Count</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($urls as $url)
                    <tr>
                        <td>{{ $url->original_url }}</td>
                        <td>
                            <a href="{{ $url->original_url }}" target="_blank">
                                {{ url($url->short_url) }}
                            </a>
                        </td>
                        <td>{{ $url->copy_count }}</td>
                        <td>
                            <button class="btn btn-success" onclick="copyToClipboard('{{ url($url->short_url) }}', {{ $url->id }})">
                                Copy
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No URLs have been shortened yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function copyToClipboard(shortUrl, id) {
            // Copy short URL to clipboard
            const tempInput = document.createElement('input');
            tempInput.value = shortUrl;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('Short URL copied to clipboard!');
    
            // Send AJAX request to increment copy count
            fetch(`/urls/${id}/increment-copy-count`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the copy count in the UI
                    document.querySelector(`#copy-count-${id}`).textContent = data.copy_count;
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    
</x-app-layout>
