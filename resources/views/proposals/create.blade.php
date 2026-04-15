<x-faculty-layout header="Submit Activity Proposal">
    <div class="m-6 md:m-8 bg-white rounded-lg shadow-lg p-6 md:p-8">
        <!-- Header -->
        <div class="flex items-start gap-4 mb-8 pb-6 border-b">
            <a href="{{ route('proposals.index') }}" class="text-lnu-blue hover:text-lnu-blue/80 transition flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Submit Activity Proposal</h1>
                <p class="text-sm text-gray-600 mt-2">Provide your proposal title, description, and attach supporting documents</p>
            </div>
        </div>

        <form class="space-y-8" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Proposal Title -->
            <div>
                <label for="title" class="block text-lg font-semibold text-gray-900 mb-3">Proposal Title *</label>
                <input type="text" id="title" name="title" required placeholder="Enter the title of your activity proposal"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <p class="text-xs text-gray-500 mt-2">A clear and concise title for your proposal</p>
            </div>

            <!-- Proposal Description -->
            <div>
                <label for="description" class="block text-lg font-semibold text-gray-900 mb-3">Proposal Description *</label>
                <textarea id="description" name="description" required rows="6" placeholder="Provide a detailed description of your activity proposal including objectives, activities, target beneficiaries, and expected outcomes..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                <p class="text-xs text-gray-500 mt-2">Include objectives, activities, beneficiaries, timeline, and expected impact</p>
            </div>

            <!-- File Upload Section -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.5 13a3 3 0 01-.369-5.98 4 4 0 117.753-1.3A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Proposal Documents *</h3>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-blue-900">
                        Attach up to <strong>3 files (PDF or DOCX)</strong> with supporting documents, detailed plans, budgets, or other relevant materials for your proposal.
                    </p>
                    <p class="text-sm text-blue-900 mt-2">
                        <strong>Max 10MB per file</strong>
                    </p>
                </div>

                <!-- File Upload Dropzone -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition"
                    onclick="document.getElementById('file-input').click()">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-base font-semibold text-gray-900">Click to upload or drag and drop</p>
                    <p class="text-sm text-gray-600 mt-1">PDF or DOCX • Up to 3 files • Max 10MB each</p>
                    <input id="file-input" type="file" name="attachments[]" multiple accept=".pdf,.docx" class="hidden" />
                </div>

                <!-- File List Preview -->
                <div id="file-list" class="mt-4 space-y-2 hidden">
                    <div id="file-item-template" class="hidden flex items-center justify-between gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <svg class="w-5 h-5 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 012-2h4.586A2 2 0 0113 2.586L15.414 5A2 2 0 0116 6.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                            </svg>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate file-name">proposal.pdf</p>
                                <p class="text-xs text-gray-500">0 MB</p>
                            </div>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-700 flex-shrink-0 remove-file">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <p class="text-xs text-gray-600 mt-3">
                    <span class="font-medium" id="file-count">0</span> file(s) selected (Max 3)
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex gap-3 pt-8 border-t">
                <a href="{{ route('proposals.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                    Submit Proposal
                </button>
            </div>
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('file-input');
        const fileList = document.getElementById('file-list');
        const fileItemTemplate = document.getElementById('file-item-template');
        const fileCount = document.getElementById('file-count');

        fileInput.addEventListener('change', function() {
            fileList.innerHTML = '';
            const files = Array.from(this.files);
            
            if (files.length > 3) {
                alert('Maximum 3 files allowed');
                this.value = '';
                fileCount.textContent = '0';
                fileList.classList.add('hidden');
                return;
            }

            if (files.length > 0) {
                fileList.classList.remove('hidden');
                files.forEach((file, index) => {
                    const item = fileItemTemplate.cloneNode(true);
                    item.classList.remove('hidden');
                    item.querySelector('.file-name').textContent = file.name;
                    item.querySelector('p:nth-of-type(2)').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                    item.querySelector('.remove-file').addEventListener('click', function(e) {
                        e.preventDefault();
                        const newFiles = files.filter((_, i) => i !== index);
                        const dt = new DataTransfer();
                        newFiles.forEach(f => dt.items.add(f));
                        fileInput.files = dt.files;
                        fileInput.dispatchEvent(new Event('change'));
                    });
                    fileList.appendChild(item);
                });
                fileCount.textContent = files.length;
            } else {
                fileCount.textContent = '0';
                fileList.classList.add('hidden');
            }
        });
    </script>
</x-faculty-layout>
