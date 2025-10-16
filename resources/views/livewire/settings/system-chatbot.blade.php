    <div class="settings-flex-row">
        <div class="card shadow-sm p-4 w-100">
            <h5 class="mb-3">📄 Upload Policy Document</h5>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('rag.store') }}" method="POST" enctype="multipart/form-data"
                class="rag-upload-form">
                @csrf
                <div class="mb-3">
                    <label for="id" class="form-label fw-semibold">Document ID</label>
                    <input type="text" name="id" id="id" class="form-control"
                        placeholder="e.g. school_policy_2025" required>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label fw-semibold">Select File</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".txt,.pdf"
                        required>
                    <small class="text-muted">Accepted formats: .txt or .pdf</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    🚀 Upload & Index to Pinecone
                </button>
            </form>
        </div>
    </div>