@extends('layouts.guest')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 text-center">
    <svg class="mx-auto h-16 w-16 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Invitation Expired</h1>
    <p class="text-gray-600 mb-8">This invitation has expired or is no longer valid.</p>

</div>
@endsection