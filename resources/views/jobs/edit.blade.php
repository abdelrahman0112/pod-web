@extends('layouts.app')

@section('title', 'Page Title - People Of Data')@section('title', 'Page Title - People Of Data')

@section('content')@section('content')    :title="'Edit Job - ' . $job->title . ' - People Of Data'"
    :activeRoute="'jobs'"
    :searchPlaceholder="'Search jobs...'"
    :sidebarMessage="'Update your job posting to attract the best candidates.'">

    <!-- Back to Job Link in Header -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-6 py-4">
            <a href="{{ route('jobs.show', $job) }}" 
               class="flex items-center space-x-2 text-slate-600 hover:text-indigo-600 transition-colors">
                <i class="ri-arrow-left-line"></i>
                <span class="text-sm font-medium">Back to Job</span>
            </a>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Edit Job Posting</h1>
            <p class="text-slate-600">
                Update your job listing to attract the best candidates
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8">
            <form id="job-edit-form" action="{{ route('jobs.update', $job) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Job Title -->
                <x-forms.input 
                    name="title"
                    label="Job Title"
                    placeholder="e.g. Senior Data Scientist"
                    :value="$job->title"
                    required />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <x-forms.input 
                        name="company_name"
                        label="Company Name"
                        placeholder="e.g. Microsoft, Google, etc."
                        :value="$job->company_name"
                        required />

                    <!-- Experience Level -->
                    <x-forms.select
                        id="experience-level"
                        name="experience_level"
                        label="Experience Level"
                        placeholder="Select experience level"
                        :options="collect($experienceLevels)->map(fn($label, $value) => ['value' => $value, 'label' => $label])->values()->toArray()"
                        :value="$job->experience_level"
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
                        :value="$job->location_type"
                        :required="true" />

                    <!-- Location (conditional) -->
                    <div id="location-field" style="display: {{ $job->location_type === 'remote' ? 'none' : 'block' }};">
                        <x-forms.input 
                            name="location"
                            label="Location"
                            placeholder="e.g. Dubai, UAE"
                            :value="$job->location"
                            :required="$job->location_type !== 'remote'" />
                    </div>
                </div>

                <!-- Company Description -->
                <x-forms.textarea 
                    name="company_description"
                    label="Company Description (Optional)"
                    placeholder="Tell us about your company..."
                    :value="$job->company_description"
                    rows="3" />

                <!-- Job Description -->
                <x-forms.textarea 
                    name="description"
                    label="Job Description"
                    placeholder="Describe the role, responsibilities, and what makes this opportunity exciting..."
                    :value="$job->description"
                    rows="6"
                    help="Minimum 100 characters"
                    required />

                <!-- Required Skills -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Required Skills *</label>
                    <div class="space-y-3" id="skills-container">
                        <div class="flex space-x-2">
                            <input 
                                type="text"
                                id="skill-input"
                                name="skill_input"
                                placeholder="Add a skill (e.g. Python, Machine Learning, SQL)"
                                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            <button 
                                type="button"
                                id="add-skill-btn"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
                                <i class="ri-add-line"></i>
                                <span>Add</span>
                            </button>
                        </div>
                        
                        <div id="skills-list" class="space-y-2" style="display: none;">
                            <!-- Skills will be added here dynamically -->
                        </div>
                        <p class="text-slate-500 text-xs">Add at least one skill requirement</p>
                        
                        <!-- Debug info -->
                        <div class="mt-2 p-2 bg-gray-100 rounded text-xs">
                            <p>Debug: Skills count: <span id="skills-count">0</span></p>
                            <p>Current input: <span id="current-input"></span></p>
                        </div>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category *</label>
                    <select name="category_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $job->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
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
                                placeholder="Min salary"
                                :value="$job->salary_min" />
                            <x-forms.input 
                                type="number"
                                name="salary_max"
                                placeholder="Max salary"
                                :value="$job->salary_max" />
                        </div>
                    </div>


                    <!-- Application Deadline -->
                    <div>
                        <x-forms.input 
                            type="date"
                            name="application_deadline"
                            label="Application Deadline *"
                            :value="$job->application_deadline->format('Y-m-d')"
                            required />
                    </div>
                </div>

                <!-- Job Status -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Job Status *</label>
                    <select name="status" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="active" {{ $job->status === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed</option>
                        <option value="archived" {{ $job->status === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-8 border-t border-slate-200">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('jobs.show', $job) }}"
                           class="flex items-center space-x-2 text-slate-600 hover:text-slate-800 transition-colors">
                            <i class="ri-arrow-left-line"></i>
                            <span>Cancel</span>
                        </a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <x-forms.button 
                            type="submit"
                            variant="primary"
                            icon="ri-save-line">
                            Update Job
                        </x-forms.button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('job-edit-form');
            const locationTypeSelect = document.getElementById('location-type');
            const locationField = document.getElementById('location-field');
            const locationInput = locationField.querySelector('input[name="location"]');
            
            // Skills management
            const skillInput = document.getElementById('skill-input');
            const addSkillBtn = document.getElementById('add-skill-btn');
            const skillsList = document.getElementById('skills-list');
            const skillsCount = document.getElementById('skills-count');
            const currentInput = document.getElementById('current-input');
            let skills = @json($job->required_skills ?? []);
            
            // Initialize skills display
            updateSkillsDisplay();
            updateDebugInfo();
            
            // Add skill function
            function addSkill() {
                const skill = skillInput.value.trim();
                if (skill) {
                    skills.push(skill);
                    skillInput.value = '';
                    updateSkillsDisplay();
                    updateDebugInfo();
                    console.log('Skill added:', skill, 'Total skills:', skills);
                }
            }
            
            // Remove skill function
            function removeSkill(index) {
                skills.splice(index, 1);
                updateSkillsDisplay();
                updateDebugInfo();
                console.log('Skill removed at index:', index, 'Total skills:', skills);
            }
            
            // Update skills display
            function updateSkillsDisplay() {
                if (skills.length === 0) {
                    skillsList.style.display = 'none';
                } else {
                    skillsList.style.display = 'block';
                    skillsList.innerHTML = skills.map((skill, index) => `
                        <div class="flex items-center justify-between bg-slate-50 px-4 py-2 rounded-lg">
                            <span class="text-sm text-slate-700">${skill}</span>
                            <button type="button" 
                                    onclick="removeSkill(${index})"
                                    class="text-slate-400 hover:text-red-500 transition-colors">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                            <input type="hidden" name="required_skills[]" value="${skill}">
                        </div>
                    `).join('');
                }
            }
            
            // Update debug info
            function updateDebugInfo() {
                skillsCount.textContent = skills.length;
                currentInput.textContent = skillInput.value;
            }
            
            // Event listeners
            addSkillBtn.addEventListener('click', addSkill);
            skillInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSkill();
                }
            });
            skillInput.addEventListener('input', updateDebugInfo);
            
            // Make removeSkill globally available
            window.removeSkill = removeSkill;
            
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
                if (skills.length === 0) {
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
        });
    </script>
    @endpush
@endsection
