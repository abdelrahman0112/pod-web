@props([
    'steps' => [],
    'currentStep' => 1
])

<div class="mb-8">
    <div class="flex items-center justify-between">
        @foreach($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $isActive = $stepNumber === $currentStep;
                $isCompleted = $stepNumber < $currentStep;
                $isUpcoming = $stepNumber > $currentStep;
            @endphp
            
            <div class="flex items-center {{ $index < count($steps) - 1 ? 'flex-1' : '' }}">
                <!-- Step Circle -->
                <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 
                    {{ $isCompleted ? 'bg-indigo-600 border-indigo-600 text-white' : '' }}
                    {{ $isActive ? 'bg-white border-indigo-600 text-indigo-600' : '' }}
                    {{ $isUpcoming ? 'bg-white border-slate-300 text-slate-400' : '' }}">
                    
                    @if($isCompleted)
                        <i class="ri-check-line text-sm"></i>
                    @else
                        <span class="text-sm font-medium">{{ $stepNumber }}</span>
                    @endif
                </div>
                
                <!-- Step Label -->
                <div class="ml-3 {{ $isActive ? 'text-indigo-600' : ($isCompleted ? 'text-slate-900' : 'text-slate-500') }}">
                    <p class="text-sm font-medium">{{ $step }}</p>
                </div>
                
                <!-- Connector Line -->
                @if($index < count($steps) - 1)
                    <div class="flex-1 mx-4 h-0.5 {{ $isCompleted ? 'bg-indigo-600' : 'bg-slate-300' }}"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>
