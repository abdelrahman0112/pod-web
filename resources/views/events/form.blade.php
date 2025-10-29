@extends('layouts.app')

@section('title', $isEdit ? 'Edit Event - ' . $event->title : 'Create New Event - People Of Data')

@section('content')
    
    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Title -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">
                {{ $isEdit ? 'Edit Event' : 'Create New Event' }}
            </h1>
            <p class="text-slate-600">
                {{ $isEdit ? 'Update your event information and settings' : 'Share your knowledge and connect with the data community' }}
            </p>
        </div>

        <!-- Display Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="ri-check-line mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="ri-error-warning-line mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex">
                    <i class="ri-error-warning-line mr-2 mt-0.5"></i>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form id="event-form" 
              action="{{ $isEdit ? route('events.update', $event) : route('events.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-8">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif
            
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Basic Information</h2>
                <div class="space-y-6">
                    <!-- Event Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Event Title *</label>
                        <input type="text" 
                               id="title"
                               name="title"
                               value="{{ old('title', $isEdit ? $event->title : '') }}"
                               placeholder="Enter your event title"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               required />
                    </div>

                    <!-- Event Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Event Description *</label>
                        <textarea id="description"
                                  name="description"
                                  rows="4"
                                  placeholder="Describe your event, what attendees will learn, and what to expect..."
                                  class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                  required>{{ old('description', $isEdit ? $event->description : '') }}</textarea>
                    </div>

                    <!-- Event Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">Event Category *</label>
                        <select id="category_id"
                                name="category_id"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">Select event category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $isEdit ? $event->category_id : '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Date & Time -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Date & Time</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-slate-700 mb-2">Start Date & Time *</label>
                        <input type="datetime-local" 
                               id="start_date"
                               name="start_date"
                               value="{{ old('start_date', $isEdit ? $event->start_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               required />
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-slate-700 mb-2">End Date & Time</label>
                        <input type="datetime-local" 
                               id="end_date"
                               name="end_date"
                               value="{{ old('end_date', $isEdit && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Location</h2>
                <div class="space-y-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-slate-700 mb-2">Location *</label>
                        <input type="text" 
                               id="location"
                               name="location"
                               value="{{ old('location', $isEdit ? $event->location : '') }}"
                               placeholder="Enter event location (e.g., Cairo, Egypt or Online)"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               required />
                    </div>

                    <!-- Event Format -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-4">Event Format *</label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="format" 
                                       value="online" 
                                       {{ old('format', $isEdit ? $event->format : '') == 'online' ? 'checked' : '' }}
                                       class="sr-only" />
                                <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3 border-slate-300">
                                    <div class="w-2.5 h-2.5 bg-indigo-600 rounded-full hidden"></div>
                                </div>
                                <span class="text-sm text-slate-700">Online</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="format" 
                                       value="in-person" 
                                       {{ old('format', $isEdit ? $event->format : '') == 'in-person' ? 'checked' : '' }}
                                       class="sr-only" />
                                <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3 border-slate-300">
                                    <div class="w-2.5 h-2.5 bg-indigo-600 rounded-full hidden"></div>
                                </div>
                                <span class="text-sm text-slate-700">In-Person</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="format" 
                                       value="hybrid" 
                                       {{ old('format', $isEdit ? $event->format : '') == 'hybrid' ? 'checked' : '' }}
                                       class="sr-only" />
                                <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3 border-slate-300">
                                    <div class="w-2.5 h-2.5 bg-indigo-600 rounded-full hidden"></div>
                                </div>
                                <span class="text-sm text-slate-700">Hybrid</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Event Image -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Event Banner</h2>
                <div class="space-y-4">
                    @if($isEdit && $event->banner_image)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Current Banner</label>
                            <img src="{{ Storage::url($event->banner_image) }}" alt="Current banner" class="w-full h-48 object-cover rounded-lg" />
                        </div>
                    @endif
                    
                    <div class="border-2 border-dashed border-slate-300 rounded-lg p-8 text-center">
                        <i class="ri-image-add-line text-2xl text-slate-400 mb-4"></i>
                        <p class="text-slate-600 mb-2">Click to upload or drag and drop</p>
                        <p class="text-sm text-slate-500">PNG, JPG, GIF up to 2MB</p>
                        <input type="file" 
                               name="banner_image" 
                               accept="image/*" 
                               class="hidden" 
                               id="banner-upload" />
                        <label for="banner-upload" class="cursor-pointer">
                            <div class="mt-4 inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50">
                                <i class="ri-upload-line mr-2"></i>
                                {{ ($isEdit && $event->banner_image) ? 'Change Banner' : 'Choose Banner' }}
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Capacity & Registration -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-6">Capacity & Registration</h2>
                <div class="space-y-6">
                    <div>
                        <label for="max_attendees" class="block text-sm font-medium text-slate-700 mb-2">Maximum Attendees</label>
                        <input type="number" 
                               id="max_attendees"
                               name="max_attendees"
                               value="{{ old('max_attendees', $isEdit ? $event->max_attendees : '') }}"
                               placeholder="Leave empty for unlimited"
                               min="1"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label for="registration_deadline" class="block text-sm font-medium text-slate-700 mb-2">Registration Deadline *</label>
                        <input type="datetime-local" 
                               id="registration_deadline"
                               name="registration_deadline"
                               value="{{ old('registration_deadline', $isEdit ? $event->registration_deadline->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               required />
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="waitlist_enabled" 
                               id="waitlist_enabled"
                               value="1"
                               {{ old('waitlist_enabled', $isEdit ? $event->waitlist_enabled : false) ? 'checked' : '' }}
                               class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" />
                        <label for="waitlist_enabled" class="ml-2 text-sm text-slate-700">
                            Enable waitlist when event is full
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center">
                <a href="{{ $isEdit ? route('events.show', $event) : route('events.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    {{ $isEdit ? 'Back to Event' : 'Back to Events' }}
                </a>
                
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        <i class="{{ $isEdit ? 'ri-save-line' : 'ri-send-plane-line' }} mr-2"></i>
                        {{ $isEdit ? 'Update Event' : 'Create Event' }}
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Radio button functionality
            const radioGroups = document.querySelectorAll('input[type="radio"]');
            radioGroups.forEach(radio => {
                radio.addEventListener('change', function() {
                    const groupName = this.name;
                    const groupRadios = document.querySelectorAll(`input[name="${groupName}"]`);
                    
                    groupRadios.forEach(r => {
                        const container = r.closest('label');
                        const circle = container.querySelector('.w-5.h-5');
                        const dot = container.querySelector('.w-2\\.5.h-2\\.5');
                        
                        if (r.checked) {
                            circle.classList.remove('border-slate-300');
                            circle.classList.add('border-indigo-600');
                            dot.classList.remove('hidden');
                        } else {
                            circle.classList.remove('border-indigo-600');
                            circle.classList.add('border-slate-300');
                            dot.classList.add('hidden');
                        }
                    });
                });
            });

            // File upload preview
            const fileInput = document.getElementById('banner-upload');
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const uploadArea = fileInput.closest('.border-dashed');
                            uploadArea.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" class="w-full h-48 object-cover rounded-lg mb-4" />
                                <button type="button" onclick="resetFileUpload()" class="text-sm text-red-600 hover:text-red-700">
                                    Remove Image
                                </button>
                            `;
                            // Re-add the original file input (hidden)
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'file';
                            hiddenInput.name = 'banner_image';
                            hiddenInput.accept = 'image/*';
                            hiddenInput.className = 'hidden';
                            hiddenInput.id = 'banner-upload-hidden';
                            uploadArea.appendChild(hiddenInput);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Preview button functionality
            const previewBtn = document.getElementById('preview-btn');
            if (previewBtn) {
                previewBtn.addEventListener('click', function() {
                    // Collect form data
                    const formData = new FormData(document.getElementById('event-form'));
                    const eventData = {};
                    
                    for (let [key, value] of formData.entries()) {
                        eventData[key] = value;
                    }
                    
                    // Create preview modal
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                    modal.innerHTML = `
                        <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold">Event Preview</h3>
                                <button onclick="this.closest('.fixed').remove()" class="text-slate-400 hover:text-slate-600">
                                    <i class="ri-close-line text-xl"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-semibold text-slate-800">${eventData.title || 'Event Title'}</h4>
                                    <p class="text-sm text-slate-600">${eventData.format || 'Event Format'}</p>
                                </div>
                                <div>
                                    <p class="text-slate-700">${eventData.description || 'Event description...'}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium">Start:</span> ${eventData.start_date || 'Not set'}
                                    </div>
                                    <div>
                                        <span class="font-medium">End:</span> ${eventData.end_date || 'Not set'}
                                    </div>
                                    <div>
                                        <span class="font-medium">Location:</span> ${eventData.location || 'Not set'}
                                    </div>
                                    <div>
                                        <span class="font-medium">Max Attendees:</span> ${eventData.max_attendees || 'Unlimited'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(modal);
                });
            }

            // Reset file upload function
            window.resetFileUpload = function() {
                const uploadArea = document.querySelector('.border-dashed');
                uploadArea.innerHTML = `
                    <i class="ri-image-add-line text-2xl text-slate-400 mb-4"></i>
                    <p class="text-slate-600 mb-2">Click to upload or drag and drop</p>
                    <p class="text-sm text-slate-500">PNG, JPG, GIF up to 2MB</p>
                    <input type="file" 
                           name="banner_image" 
                           accept="image/*" 
                           class="hidden" 
                           id="banner-upload-reset" />
                    <label for="banner-upload-reset" class="cursor-pointer">
                        <div class="mt-4 inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-sm text-slate-700 hover:bg-slate-50">
                            <i class="ri-upload-line mr-2"></i>
                            Choose File
                        </div>
                    </label>
                `;
                // Re-attach event listener
                const newFileInput = document.getElementById('banner-upload-reset');
                newFileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const uploadArea = newFileInput.closest('.border-dashed');
                            uploadArea.innerHTML = `
                                <img src="${e.target.result}" alt="Preview" class="w-full h-48 object-cover rounded-lg mb-4" />
                                <button type="button" onclick="resetFileUpload()" class="text-sm text-red-600 hover:text-red-700">
                                    Remove Image
                                </button>
                            `;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            };
        });
    </script>
    @endpush
@endsection
