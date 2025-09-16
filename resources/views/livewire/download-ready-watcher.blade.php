<div>

    <div @if($polling) wire:poll="check" @endif></div>

    @if($ready)
        <div class="card bg-light mb-3" style="width: 25rem !important;">
            <div class="card-header"><h5>Download ready <i class="fas fa-check"></i></h5></div>
            <div class="card-body justify-content-center d-flex flex-column">
                @if ($download['description'])
                    <p class="small text-muted card-text" style="font-style: italic;">
                        Your file containing <span style="font-weight: bold;"> {{ $download['description'] }}</span> is available for download.
                    </p>
                @endif
                <a href="{{ $download['url'] }}" class="btn btn-primary" title="Download {{ $download['filename'] ?? 'File' }}">
                    Download <i class="fas fa-download"></i>
                </a>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const download_in_process_card = document.getElementById('download_in_process_card');

            window.addEventListener('pdfDownloadReady', event => {
                download_in_process_card.classList.add('d-none');
            });
        });
    </script>

</div>
