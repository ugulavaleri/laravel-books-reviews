@php use Illuminate\Support\Str; @endphp
@extends('layout.app')

@section('content')
    <h1 class="mb-10 text-2xl">Books</h1>
    <div>
        <form action="{{ route('books.index') }}" method="GET" class="flex gap-3">
            @csrf
            <input type="text" placeholder="search.." class="input mb-[30px]" name="title" value="{{ request('title') }}">
            <input type="hidden" name="filter" value="{{ request('filter') }}">
            <button class="btn">Submit</button>
            <a href="{{ route('books.index') }}" class="btn">Clear</a>
        </form>
    </div>
    @php
        $filters = [
          '' => 'Latest',
          'popular_last_month' => 'Popular last Month',
          'popular_last_6months' => 'Popular last 6 Months',
          'highest_rated_last_month' => 'Highest Rated Last Month',
          'highest_rated_last_6months' => 'Highest Rated Last 6 Months'
        ]
    @endphp

    <div class="filter-container flex gap-3">
        @foreach($filters as $filter => $label)
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $filter] ) }}" class="{{ request('filter') === $filter || (request('filter') === null && $filter === '') ? 'filter-item-active' : 'filter-item' }}">{{ $label }}</a>
        @endforeach
    </div>

    <ul>
        @forelse($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div
                        class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">by {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                <x-star-rating :rating="$book->reviews_avg_rating"/>
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>

    <div>
        {{ $books->links() }}
    </div>

@endsection
