@props([
    'name' => 'skills',
    'label' => 'Skills',
    'placeholder' => 'Add a skill (e.g. Python, Machine Learning, SQL)',
    'help' => 'Add at least one skill requirement',
    'required' => false,
    'existingSkills' => []
])

<div>
    <label class="block text-sm font-medium text-slate-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="space-y-3" id="{{ $name }}-container">
        <div class="flex space-x-2">
            <input 
                type="text"
                id="{{ $name }}-input"
                name="{{ $name }}_input"
                placeholder="{{ $placeholder }}"
                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
            <button 
                type="button"
                id="add-{{ $name }}-btn"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center space-x-2">
                <i class="ri-add-line"></i>
                <span>Add</span>
            </button>
        </div>
        
        <div id="{{ $name }}-list" class="space-y-2" style="display: {{ count($existingSkills) > 0 ? 'block' : 'none' }};">
            @foreach($existingSkills as $index => $skill)
                <div class="flex items-center justify-between bg-slate-50 px-4 py-2 rounded-lg">
                    <span class="text-sm text-slate-700">{{ $skill }}</span>
                    <button type="button" 
                            onclick="removeSkill_{{ $name }}({{ $index }})"
                            class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                    <input type="hidden" name="{{ $name }}[]" value="{{ $skill }}">
                </div>
            @endforeach
        </div>
        @if($help)
            <p class="text-slate-500 text-xs">{{ $help }}</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const skillInput = document.getElementById('{{ $name }}-input');
    const addSkillBtn = document.getElementById('add-{{ $name }}-btn');
    const skillsList = document.getElementById('{{ $name }}-list');
    let skills = @json($existingSkills);
    
    // Add skill function
    function addSkill() {
        const skill = skillInput.value.trim();
        if (skill) {
            skills.push(skill);
            skillInput.value = '';
            updateSkillsDisplay();
        }
    }
    
    // Remove skill function (must be in window scope to be called from HTML)
    window.removeSkill_{{ $name }} = function(index) {
        skills.splice(index, 1);
        updateSkillsDisplay();
    };
    
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
                            onclick="removeSkill_{{ $name }}(${index})"
                            class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                    <input type="hidden" name="{{ $name }}[]" value="${skill}">
                </div>
            `).join('');
        }
    }
    
    // Event listeners
    addSkillBtn.addEventListener('click', addSkill);
    skillInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSkill();
        }
    });
});
</script>
