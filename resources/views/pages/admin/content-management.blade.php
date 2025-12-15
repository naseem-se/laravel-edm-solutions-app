@extends('layouts.auth')
@section('title')
    Content Management
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.6/quill.snow.css" rel="stylesheet">


@section('content')
    <main class="flex-1 p-4 lg:p-6 overflow-auto bg-gradient-to-br from-gray-50 to-gray-100">
        <div class="pb-8">
            <h1 class="text-3xl font-bold text-black mb-1">Settings & Admin Controls</h1>
            <p class="text-gray-500 text-base">Configure system rules, roles, and track admin activity</p>
        </div>

        <!-- Tabs -->
        <div class="mb-6">
            <div class="flex items-center gap-2 border-b border-gray-200">
                <button onclick="window.location.href=`{{ route('admin.setting.index') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Profile & Account
                </button>
                <button onclick="window.location.href=`{{ route('admin.setting.roles.index') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Roles & Permissions
                </button>
                <button onclick="window.location.href=`{{ route('admin.setting.platform.config') }}`"
                    class="px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-t-lg transition-colors">
                    Platform Config
                </button>
                <button onclick="window.location.href=`{{ route('admin.setting.content.management') }}`"
                    class="px-4 py-3 text-sm font-semibold text-white bg-black rounded-t-lg">
                    Content Management
                </button>
            </div>
        </div>

        <div class="card mt-6 bg-white p-8" style="border: 1px solid #d9c8c8b8;border-radius: 10px;">
            <div class="card-heading flex flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path
                        d="M192 112L304 112L304 200C304 239.8 336.2 272 376 272L464 272L464 512C464 520.8 456.8 528 448 528L192 528C183.2 528 176 520.8 176 512L176 128C176 119.2 183.2 112 192 112zM352 131.9L444.1 224L376 224C362.7 224 352 213.3 352 200L352 131.9zM192 64C156.7 64 128 92.7 128 128L128 512C128 547.3 156.7 576 192 576L448 576C483.3 576 512 547.3 512 512L512 250.5C512 233.5 505.3 217.2 493.3 205.2L370.7 82.7C358.7 70.7 342.5 64 325.5 64L192 64zM248 320C234.7 320 224 330.7 224 344C224 357.3 234.7 368 248 368L392 368C405.3 368 416 357.3 416 344C416 330.7 405.3 320 392 320L248 320zM248 416C234.7 416 224 426.7 224 440C224 453.3 234.7 464 248 464L392 464C405.3 464 416 453.3 416 440C416 426.7 405.3 416 392 416L248 416z" />
                </svg>
                <p class="ml-2">Content Management</p>
            </div>

            <div class="container mx-auto p-6">
                <div class="card-body mt-6">
                    <!-- Content Items -->
                    <div id="contentList"></div>
                </div>
            </div>

            <!-- Modal -->
            <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl max-h-[90vh] overflow-auto">
                    <!-- Modal Header -->
                    <div class="sticky top-0 bg-white border-b border-gray-200 p-6 flex items-center justify-between">
                        <h2 id="modalTitle" class="text-xl font-semibold text-gray-900">Edit Content</h2>
                        <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6">
                        <div id="editorContainer" style="height: 400px;"></div>
                        <input type="hidden" id="currentField" value="">
                    </div>

                    <!-- Modal Footer -->
                    <div
                        class="sticky bottom-0 bg-gray-50 border-t border-gray-200 p-6 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="button" onclick="saveContent()"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Save
                        </button>
                    </div>
                </div>
            </div>


        </div>

    </main>

    <script>
        const contentItems = [{
                title: "FAQs",
                description: "Frequently asked questions",
                field: "faqs"
            },
            {
                title: "Terms & Condition",
                description: "Platform terms of service",
                field: "terms_and_conditions"
            },
            {
                title: "Privacy Policy",
                description: "Data privacy and protection policy",
                field: "privacy_policy"
            },
            {
                title: "Help Center",
                description: "User guides and tutorials",
                field: "help_center"
            }
        ];

        let quillEditor;
        let contentData = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initializeQuill();
            loadContent();
            renderContentItems();
        });

        function initializeQuill() {
            quillEditor = new Quill('#editorContainer', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{
                            'header': 1
                        }, {
                            'header': 2
                        }],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });
        }

        async function loadContent() {
            try {
                const response = await fetch('/api/contents');
                const data = await response.json();
                if (data.data) {
                    contentData = data.data;
                }
            } catch (error) {
                console.log('No existing content');
            }
        }

        function renderContentItems() {
            const contentList = document.getElementById('contentList');
            contentList.innerHTML = contentItems.map((item) => `
                <div class="bg-white rounded-lg p-6 mb-4 border border-gray-200 flex items-center justify-between hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4 flex-1">
                        <div>
                            <h3 class="font-semibold text-gray-900">${item.title}</h3>
                            <p class="text-sm text-gray-500">${item.description}</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 flex items-center gap-2">
                        <button type="button" onclick="openModal('${item.field}', '${item.title}')"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer font-medium text-sm">
                            Edit
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function openModal(field, title) {
            document.getElementById('modalTitle').textContent = `Edit ${title}`;
            document.getElementById('currentField').value = field;

            // Set editor content
            const content = contentData[field] || '';
            quillEditor.root.innerHTML = content;

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
            quillEditor.root.innerHTML = '';
        }

        async function saveContent() {
            const field = document.getElementById('currentField').value;
            const content = quillEditor.root.innerHTML;

            try {
                const response = await fetch('/api/contents', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        [field]: content
                    })
                });

                const data = await response.json();
                if (data.success || response.ok) {
                    contentData[field] = content;
                    closeModal();
                    alert('Content saved successfully!');
                } else {
                    alert('Error saving content');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error saving content');
            }
        }
    </script>
@endsection
