@extends('layouts.layout')

@section('content')
<section class="bg-white dark:bg-gray-900 min-h-screen">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        <div class="mx-auto max-w-screen-sm text-center flex flex-col items-center justify-center">
            <h1 class="mb-4 text-7xl tracking-tight font-extrabold lg:text-9xl text-primary-600 dark:text-primary-500">403</h1>
            <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Account not activated.</p>
            <p class="mb-4 text-lg font-light text-gray-500 dark:text-gray-400">Sorry, your account has not been activated by the admin yet. Please wait for your account to be activated. </p>
            <a href="{{url('presence')}}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Refresh</a>
        </div>
    </div>
</section>

@endsection