<button type="button" class="btn btn-light border" data-bs-toggle="modal"
data-bs-target="#movie-{{ $movie->id.'mobile' }}">
    <i class="bi bi-play-circle"></i>モバイル用動画再生
</button>

<!-- Modal -->
<div class="modal fade" id="movie-{{ $movie->id.'mobile' }}"
data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
aria-labelledby="movie-{{ $movie->id.'mobile' }}Label" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
      <div class="modal-content">
        <div class="modal-header bg-dark text-white">

            <h5 class="modal-title" id="movie-{{ $movie->id.'mobile' }}Label"
            >{{ $movie->name.'モバイル' }}</h5>

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
            ><source src="{{ $movie->mobile }}" />
            </video>

        </div>
      </div>
    </div>


</div>
