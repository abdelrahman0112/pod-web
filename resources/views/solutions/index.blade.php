@extends('layouts.app')

@section('title', 'AI Solutions & Tools')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-2xl px-6">
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-2xl mb-6">
                <i class="ri-lightbulb-line text-5xl text-indigo-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-slate-800 mb-4">AI Cool Solutions & Tools Coming Soon!</h1>
            <p class="text-lg text-slate-600 mb-8">
                We're working hard to bring you amazing AI-powered solutions and tools to enhance your workflow and productivity.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
                    <i class="ri-sparkling-line text-3xl text-purple-600 mb-3"></i>
                    <h3 class="font-semibold text-slate-800 mb-2">Innovative Tools</h3>
                    <p class="text-sm text-slate-600">Cutting-edge AI solutions to supercharge your work</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
                    <i class="ri-robot-line text-3xl text-indigo-600 mb-3"></i>
                    <h3 class="font-semibold text-slate-800 mb-2">AI-Powered</h3>
                    <p class="text-sm text-slate-600">Leverage the power of artificial intelligence</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100">
                    <i class="ri-flashlight-line text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-slate-800 mb-2">Coming Soon</h3>
                    <p class="text-sm text-slate-600">Stay tuned for exciting updates</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

