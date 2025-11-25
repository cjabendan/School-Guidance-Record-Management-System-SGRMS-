    <div class="settings-flex-row">
        <div class="settings-form">
            <div class="settings-form-header">
                <p class="settings-form-heading">Upload Policy Document</p>
                <p class="settings-form-subheading">
                    Upload policy files to be indexed into the system. Supported formats: TXT and PDF.
                </p>
            </div>

            {{-- ================= MAIN SECTION ================= --}}
            <div style="display:flex; flex-direction:column; gap:1.5rem; width: 15%;">


                {{-- Alerts --}}
                @if (session('success'))
                    <p class="settings-form-subheading" style="font-size:14px; color:green;">
                        {{ session('success') }}
                    </p>
                @endif

                @if ($errors->any())
                    <div style="font-size:14px; color:red;">
                        <ul style="margin:0; padding-left:1.2rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('rag.store') }}" method="POST" enctype="multipart/form-data"
                    class="rag-upload-form" style="display:flex; flex-direction:column; gap:1rem;">
                    @csrf

                    {{-- Document ID --}}
                    <div style="display:flex; flex-direction:column; gap:0.3rem;">
                        <label class="settings-form-subheading fw-semibold">Document ID</label>
                        <input type="text" name="id" id="id" class="form-control"
                            placeholder="e.g. school_policy_2025" required>
                    </div>

                    {{-- File Input --}}
                    <div style="display:flex; flex-direction:column; gap:0.3rem;">
                        <label class="settings-form-subheading fw-semibold">Select File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".txt,.pdf"
                            required>
                        <span class="settings-form-subheading" style="font-size:14px; color:#555;">
                            Accepted formats: .txt or .pdf
                        </span>
                    </div>

                    {{-- Upload Button --}}
                    <button type="submit" class="settings-form-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="icons">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Upload document
                    </button>
                </form>

            </div>
        </div>

    </div>
