<div class="review-section" style="max-height: 400px; overflow-y: auto;">
    @if ($ratings->isEmpty())
        <div class="text-center text-secondary">
            <h2>No reviews on the technician!</h2>
        </div>
    @else
        @foreach ($ratings as $rating)
            <div class="review-item">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center">
                        @if ($rating->user->picture)
                            <img src="{{ asset('storage/' . $rating->user->picture) }}" width="30px" height="30px" style="cover: fit;" class="me-2">
                        @else
                            <img src="{{ asset('/images/placeholder.jpg') }}" width="30px" class="me-2">
                        @endif
                        <h6 class="mt-2">{{ $rating->user->firstName }} {{ $rating->user->lastName }}</h6>
                    </div>
                    @if ($rating->user_id === auth()->id())
                        <div class="mt-4">
                            <button class="btn btn-outline-light border-0 pencil-btn">
                                <i class="bi bi-pencil-fill text-primary"></i>
                            </button>
                            <button class="btn btn-outline-light border-0 trash-btn me-1">
                                <i class="bi bi-trash-fill text-secondary"></i>
                            </button>
                        </div>
                    @else
                        <button class="btn btn-outline-light border-0 flag-btn">
                            <i class="bi bi-flag-fill text-danger"></i>
                        </button>
                    @endif
                </div>
                <p>{{ $rating->review }}</p>
                <div class="rating d-flex align-items-center mb-3">
                    @for ($i = 0; $i < $rating->rating; $i++)
                        <i class="bi bi-star-fill text-warning ms-1"></i>
                    @endfor
                    <button class="btn btn-sm btn-outline-light border-0 like-btn" data-rating-id="{{ $rating->id }}">
                        <i class="bi bi-hand-thumbs-up-fill {{ $rating->likes ? 'text-success' : 'text-secondary' }}">{{ $rating->likes }}</i> 
                    </button>
                    <button class="btn btn-sm btn-outline-light border-0 dislike-btn" data-rating-id="{{ $rating->id }}">
                        <i class="bi bi-hand-thumbs-down-fill {{ $rating->dislikes ? 'text-danger' : 'text-secondary' }}">{{ $rating->dislikes }}</i> 
                    </button>
                </div>
            </div>
        @endforeach
    @endif
</div>
