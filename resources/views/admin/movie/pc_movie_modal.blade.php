<button type="button" class="btn btn-light border" data-bs-toggle="modal"
data-bs-target="#movie-{{ $movie->id.'pc' }}">
    <i class="bi bi-play-circle"></i>PC用動画再生
</button>

<!-- Modal -->
<div class="modal fade" id="movie-{{ $movie->id.'pc' }}"
data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
aria-labelledby="movie-{{ $movie->id.'pc' }}Label" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width:800px;">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">

            <h5 class="modal-title" id="movie-{{ $movie->id.'pc' }}Label"
            >{{ $movie->name.'PC' }}</h5>

            <button onclick="videoPause()"
            type="button" class="btn text-white" data-bs-dismiss="modal" aria-label="Close"
            ><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body bg-dark">

            <video class="bg_video"
            playsinline
            controls
            width="100%"
            poster=""
            ><source src="{{ $movie->pc }}" />
            </video>

        </div>
      </div>
    </div>


</div>
