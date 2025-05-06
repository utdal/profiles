<div>
    <form wire:submit.prevent>

        <p>
            <a
                id="export_all"
                style="text-decoration: none !important;"
                class="clickable"
                wire:click="toggleExportAll"
                aria-haspopup="true"
                aria-controls="export_buttons_all"
                aria-expanded="{{ $show_export_all ? 'true' : 'false' }}"
                aria-label="Download all student applications"
            >
                Export all applications
            </a>
        </p>

        @if($show_export_all)
            <div id="export_buttons_all" class="mt-1 p-2 mb-3 rounded bg-light fade-in d-flex justify-content-center">
                <button
                    class="btn btn-sm btn-outline-primary mr-2"
                    wire:click="$emit('exportToPdf', 1)"
                    type="button"
                >
                    <i class="fas fa-file-pdf"></i> PDF
                </button>
                <button
                    class="btn btn-sm btn-outline-primary"
                    wire:click="$emit('exportToExcel', 1)"
                    type="button"
                >
                    <i class="fas fa-file-excel"></i> Excel
                </button>
            </div>
        @endif

        @if($filter_summary)
            <p>
                <a
                    id="export_filtered"
                    style="text-decoration: none !important;"
                    class="clickable"
                    wire:click="toggleExportFiltered"
                    aria-haspopup="true"
                    aria-controls="export_buttons_filtered"
                    aria-expanded="{{ $show_export_filtered ? 'true' : 'false' }}"
                    aria-label="Download filtered student applications"
                >
                    Export Applications {{ $filter_summary }}
                </a>
            </p>

            @if($show_export_filtered)
                <div id="export_buttons_filtered" class="mt-1 p-2 mb-3 rounded bg-light fade-in d-flex justify-content-center">
                    <button
                        class="btn btn-sm btn-outline-primary mr-2"
                        wire:click="$emit('exportToPdf', 0)"
                        type="button"
                    >
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <button
                        class="btn btn-sm btn-outline-primary"
                        wire:click="$emit('exportToExcel', 0)"
                        type="button"
                    >
                        <i class="fas fa-file-excel"></i> Excel
                    </button>
                </div>
            @endif
        @endif

    </form>

    <script>
        window.addEventListener('flashExportButtons', (event) => {
            const idMap = {
                all: 'export_buttons_all',
                filtered: 'export_buttons_filtered',
            };
            const el = document.getElementById(idMap[event.detail.target]);
            el?.classList.add('flash-highlight');
            setTimeout(() => {
                el?.classList.remove('flash-highlight');
            }, 600);
        });
    </script>

    <style>
        @keyframes flashHighlight {
            0% { background-color: #e3f2fd; }
            50% { background-color: #bbdefb; }
            100% { background-color: #e3f2fd; }
        }

        .flash-highlight {
            animation: flashHighlight 0.6s ease-in-out;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>
