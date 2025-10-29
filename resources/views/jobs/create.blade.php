@extends('layouts.app')

@section('title', 'Post Job - People Of Data')

@section('content')

    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Title -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Post New Job</h1>
            <p class="text-slate-600">
                Create and publish a job listing to attract top talent in data science and analytics
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
            <form id="job-posting-form" action="{{ route('jobs.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Job Title -->
                <x-forms.input 
                    name="title"
                    label="Job Title"
                    placeholder="e.g. Senior Data Scientist"
                    required />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <x-forms.input 
                        name="company_name"
                        label="Company Name"
                        placeholder="e.g. Microsoft, Google, etc."
                        required />

                    <!-- Experience Level -->
                    <x-forms.select
                        id="experience-level"
                        name="experience_level"
                        label="Experience Level"
                        placeholder="Select experience level"
                        :options="collect($experienceLevels)->map(fn($label, $value) => ['value' => $value, 'label' => $label])->values()->toArray()"
                        :required="true" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Location Type -->
                    <x-forms.select
                        id="location-type"
                        name="location_type"
                        label="Location Type"
                        placeholder="Select location type"
                        :options="collect($locationTypes)->map(fn($label, $value) => ['value' => $value, 'label' => $label])->values()->toArray()"
                        :required="true" />

                    <!-- Location (conditional) -->
                    <div id="location-field" style="display: none;">
                        <x-forms.input 
                            name="location"
                            label="Location"
                            placeholder="e.g. Dubai, UAE"
                            required />
                    </div>
                </div>

                <!-- Company Description -->
                <x-forms.textarea 
                    name="company_description"
                    label="Company Description (Optional)"
                    placeholder="Tell us about your company..."
                    rows="3" />

                <!-- Job Description -->
                <x-forms.textarea 
                    name="description"
                    label="Job Description"
                    placeholder="Describe the role, responsibilities, and what makes this opportunity exciting..."
                    rows="6"
                    help="Minimum 100 characters"
                    required />

                <!-- Required Skills -->
                <x-forms.skills-input 
                    name="required_skills" 
                    label="Required Skills" 
                    placeholder="Add a skill (e.g. Python, Machine Learning, SQL)"
                    help="Add at least one skill requirement"
                    required="true" />

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Salary Range -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Salary Range (Optional)</label>
                        <div class="grid grid-cols-2 gap-3">
                            <x-forms.input 
                                type="number"
                                name="salary_min"
                                placeholder="Min salary" />
                            <x-forms.input 
                                type="number"
                                name="salary_max"
                                placeholder="Max salary" />
                        </div>
                    </div>


                    <!-- Application Deadline -->
                    <div>
                        <x-forms.input 
                            type="date"
                            name="application_deadline"
                            label="Application Deadline *"
                            required />
                    </div>
                </div>

            </form>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('jobs.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-lg text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to Jobs
            </a>
            
            <div class="flex space-x-4">
                <button type="submit" 
                        form="job-posting-form"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="ri-send-plane-line mr-2"></i>
                    Publish Job
                </button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <x-modal id="success-modal" max-width="max-w-md" :closeable="false">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-check-line text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Job Posted Successfully!</h3>
            <p class="text-slate-600 mb-6">
                Your job listing has been published and is now visible to candidates.
            </p>
            <div class="flex space-x-3">
                <a href="{{ route('jobs.index') }}"
                   class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-center">
                    View Job Listings
                </a>
                <button
                    class="flex-1 border border-slate-200 text-slate-600 px-4 py-2 rounded-lg hover:bg-slate-50 transition-colors"
                    id="post-another">
                    Post Another Job
                </button>
            </div>
        </div>
    </x-modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('job-posting-form');
            const locationTypeSelect = document.getElementById('location-type');
            const locationField = document.getElementById('location-field');
            const locationInput = locationField.querySelector('input[name="location"]');
            
            
            // Show/hide location field based on location type
            function toggleLocationField() {
                const locationType = locationTypeSelect.value;
                if (locationType === 'remote') {
                    locationField.style.display = 'none';
                    locationInput.removeAttribute('required');
                } else {
                    locationField.style.display = 'block';
                    locationInput.setAttribute('required', 'required');
                }
            }
            
            locationTypeSelect.addEventListener('change', toggleLocationField);
            
            // Form validation
            const requiredFields = ['title', 'company_name', 'description', 'experience_level', 'location_type', 'required_skills[]', 'category_id', 'application_deadline'];
            
            function validateForm() {
                let isValid = true;
                
                // Check required text fields
                ['title', 'company_name', 'description', 'application_deadline'].forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field && !field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500');
                    } else if (field) {
                        field.classList.remove('border-red-500');
                    }
                });
                
                // Check location field if not remote
                if (locationTypeSelect.value !== 'remote') {
                    if (!locationInput.value.trim()) {
                        isValid = false;
                        locationInput.classList.add('border-red-500');
                    } else {
                        locationInput.classList.remove('border-red-500');
                    }
                }
                
                // Check required skills
                const skillsInputs = form.querySelectorAll('input[name="required_skills[]"]');
                if (skillsInputs.length === 0) {
                    isValid = false;
                    alert('Please add at least one required skill');
                    return false;
                }
                
                return isValid;
            }
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (validateForm()) {
                    // Submit the form
                    form.submit();
                } else {
                    alert('Please fill in all required fields');
                }
            });
            
            // Post another job
            document.getElementById('post-another').addEventListener('click', function() {
                hideModal('success-modal');
                form.reset();
                // Reset Alpine.js data if needed
                alert('Form cleared. You can now post another job.');
            });
        });
    </script>
    @endpush
@endsection