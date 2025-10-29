@extends('layouts.app')

@section('title', 'Apply for Internship')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Apply for Internship
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Design content will be pasted here -->
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="px-6 py-8">
                <!-- Introduction -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Internship Application</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    About Our Internship Program
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Join our data science internship program and gain hands-on experience with real-world projects. You'll work alongside experienced professionals and contribute to meaningful data-driven solutions.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('internships.store') }}" class="space-y-8">
                    @csrf

                    <!-- Personal Information -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" name="full_name" id="full_name" value="{{ old('full_name', auth()->user()->name) }}" required
                                       class="input-field @error('full_name') border-red-300 @enderror">
                                @error('full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                                       class="input-field @error('email') border-red-300 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                                       class="input-field @error('phone') border-red-300 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="university" class="block text-sm font-medium text-gray-700 mb-2">University/Institution</label>
                                <input type="text" name="university" id="university" value="{{ old('university') }}"
                                       placeholder="e.g., Cairo University"
                                       class="input-field @error('university') border-red-300 @enderror">
                                @error('university')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="major" class="block text-sm font-medium text-gray-700 mb-2">Major/Field of Study</label>
                                <input type="text" name="major" id="major" value="{{ old('major') }}"
                                       placeholder="e.g., Computer Science, Statistics"
                                       class="input-field @error('major') border-red-300 @enderror">
                                @error('major')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-2">Expected Graduation Year</label>
                                <select name="graduation_year" id="graduation_year"
                                        class="input-field @error('graduation_year') border-red-300 @enderror">
                                    <option value="">Select Year</option>
                                    @for($year = 2024; $year <= 2030; $year++)
                                        <option value="{{ $year }}" {{ old('graduation_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                                @error('graduation_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gpa" class="block text-sm font-medium text-gray-700 mb-2">GPA (Optional)</label>
                                <input type="number" name="gpa" id="gpa" value="{{ old('gpa') }}" step="0.01" min="0" max="4"
                                       placeholder="e.g., 3.75"
                                       class="input-field @error('gpa') border-red-300 @enderror">
                                @error('gpa')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Experience & Skills -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Experience & Skills</h4>
                        <div class="space-y-6">
                            <div>
                                <label for="experience" class="block text-sm font-medium text-gray-700 mb-2">Previous Experience</label>
                                <textarea name="experience" id="experience" rows="4"
                                          placeholder="Describe any previous internships, projects, or relevant work experience..."
                                          class="input-field @error('experience') border-red-300 @enderror">{{ old('experience') }}</textarea>
                                @error('experience')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">Technical Skills *</label>
                                <input type="text" name="skills" id="skills" value="{{ old('skills') }}" required
                                       placeholder="Python, SQL, Machine Learning, Data Analysis, etc. (comma separated)"
                                       class="input-field @error('skills') border-red-300 @enderror">
                                <p class="text-xs text-gray-500 mt-1">List your technical skills separated by commas</p>
                                @error('skills')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="interests" class="block text-sm font-medium text-gray-700 mb-2">Areas of Interest *</label>
                                <input type="text" name="interests" id="interests" value="{{ old('interests') }}" required
                                       placeholder="Machine Learning, Data Visualization, NLP, Computer Vision, etc."
                                       class="input-field @error('interests') border-red-300 @enderror">
                                <p class="text-xs text-gray-500 mt-1">What areas of data science interest you most?</p>
                                @error('interests')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Availability</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="availability_start" class="block text-sm font-medium text-gray-700 mb-2">Available Start Date *</label>
                                <input type="date" name="availability_start" id="availability_start" 
                                       value="{{ old('availability_start') }}" required
                                       class="input-field @error('availability_start') border-red-300 @enderror">
                                @error('availability_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="availability_end" class="block text-sm font-medium text-gray-700 mb-2">Available End Date *</label>
                                <input type="date" name="availability_end" id="availability_end" 
                                       value="{{ old('availability_end') }}" required
                                       class="input-field @error('availability_end') border-red-300 @enderror">
                                @error('availability_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Motivation -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Motivation</h4>
                        <div>
                            <label for="motivation" class="block text-sm font-medium text-gray-700 mb-2">Why do you want to join our internship program? *</label>
                            <textarea name="motivation" id="motivation" rows="6" required
                                      placeholder="Tell us why you're interested in this internship and what you hope to achieve..."
                                      class="input-field @error('motivation') border-red-300 @enderror">{{ old('motivation') }}</textarea>
                            @error('motivation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Portfolio Links -->
                    <div>
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Portfolio & Links (Optional)</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="portfolio_github" class="block text-sm font-medium text-gray-700 mb-2">GitHub Profile</label>
                                <input type="url" name="portfolio_links[github]" id="portfolio_github" 
                                       value="{{ old('portfolio_links.github') }}"
                                       placeholder="https://github.com/yourusername"
                                       class="input-field @error('portfolio_links.github') border-red-300 @enderror">
                                @error('portfolio_links.github')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="portfolio_linkedin" class="block text-sm font-medium text-gray-700 mb-2">LinkedIn Profile</label>
                                <input type="url" name="portfolio_links[linkedin]" id="portfolio_linkedin" 
                                       value="{{ old('portfolio_links.linkedin') }}"
                                       placeholder="https://linkedin.com/in/yourprofile"
                                       class="input-field @error('portfolio_links.linkedin') border-red-300 @enderror">
                                @error('portfolio_links.linkedin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="portfolio_website" class="block text-sm font-medium text-gray-700 mb-2">Personal Website/Portfolio</label>
                                <input type="url" name="portfolio_links[website]" id="portfolio_website" 
                                       value="{{ old('portfolio_links.website') }}"
                                       placeholder="https://yourportfolio.com"
                                       class="input-field @error('portfolio_links.website') border-red-300 @enderror">
                                @error('portfolio_links.website')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="portfolio_other" class="block text-sm font-medium text-gray-700 mb-2">Other Relevant Links</label>
                                <input type="url" name="portfolio_links[other]" id="portfolio_other" 
                                       value="{{ old('portfolio_links.other') }}"
                                       placeholder="Kaggle, Medium, etc."
                                       class="input-field @error('portfolio_links.other') border-red-300 @enderror">
                                @error('portfolio_links.other')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-start">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 block text-sm text-gray-900">
                                I agree to the terms and conditions of the internship program and understand that this application will be reviewed by the People Of Data team. *
                            </label>
                        </div>
                        @error('terms')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('home') }}" class="btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
