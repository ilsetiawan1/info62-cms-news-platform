@extends('layouts.public')

@section('title', $page['title'])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
    <article class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700/60 p-6 md:p-10 shadow-sm">
        <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-8">
            {{ $page['title'] }}
        </h1>
        
        <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-300 leading-relaxed text-base space-y-6">
            <p>{{ $page['content'] }}</p>
        </div>
    </article>
</div>
@endsection
