@push('scripts')
    <script>
        const button = document.getElementById('apply_filters');
        let highlightValue = @js($current_semester);
        let charts_completed = 0;
        let total_charts = 4;
        let img_route_bar = "{{asset('img/no-results-found.png')}}";
        let img_route_doughnut = "{{asset('img/no-results-found2.png')}}";

        button.addEventListener('click', function() {
            showLoadingModal();
            let selected_semesters = buildFilterParameters('semesters_options');
            let selected_schools = buildFilterParameters('schools_options');
            let weeks_before_semester_start = document.getElementById('weeks_before_semester_start').value;
            let weeks_before_semester_end = document.getElementById('weeks_before_semester_end').value;
            Livewire.emit('applyFilters', selected_semesters, selected_schools, weeks_before_semester_start, weeks_before_semester_start);
        });

        Livewire.on('chartAnimationComplete', () => {
            charts_completed++;
            if (charts_completed === total_charts) {
                hideLoadingModal();
                charts_completed = 0;
            }
        });

        function buildFilterParameters(fieldset_selector) {
            const fieldset = document.getElementById(fieldset_selector);
            const checkboxes = fieldset.querySelectorAll('input[type=checkbox]');
            const checkboxArray = Array.from(checkboxes)
                                    .filter(checkbox => checkbox.checked) // Filter only checked checkboxes
                                    .map(checkbox => checkbox.name);
            return checkboxArray;
        }
        
        function selectAllToggle(fieldset_selector) {
            fieldset = document.getElementById(fieldset_selector);
            checkboxes = fieldset.querySelectorAll('input[type=checkbox]');
            if (fieldset.dataset.allchecked === 'true' ) {
                checkboxes.forEach(chckbx => chckbx.checked = false ); 
                fieldset.dataset.allchecked = 'false';
            }
            else { 
                checkboxes.forEach(chckbx => chckbx.checked = true ); 
                fieldset.dataset.allchecked = 'true';
            }
        }

        function showLoadingModal() {
            document.getElementById('loadingModal').style.display = 'flex';
        }

        function hideLoadingModal() {
            document.getElementById('loadingModal').style.display = 'none';
            }

        function setLabelFontSize() {
            const width = window.innerWidth;
            return width < 576 ? 10 : width < 768 ? 12 : width < 992 ? 14 : 16; // Adjust sizes as needed
        }

    </script>
@endpush

<div>
    <div class="row mt-3">
        <div>
            <div class="dropdown dropdown-options" id="semesters_options" data-allchecked="true">
                <button class="dropdown-toggle btn btn-sm btn-light" data-toggle="dropdown">Select Semesters
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-content-options">
                    <a class="small ml-4" style="cursor: pointer;" onclick="selectAllToggle('semesters_options')">Select/Unselect All</a>
                    @foreach($semester_options as $key => $value)
                        <span class="dropdown-content-item">
                            <input type="checkbox"
                                id="semester_{{$key}}"
                                name="{{$value}}"
                                value="{{$value}}"
                                {{ in_array($value, $semesters_selected) ? 'checked' : '' }}  
                                aria-describedby="semester-selection"
                            />{{$value}}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown dropdown-options" id="schools_options" data-allchecked="true">
                <button class="dropdown-toggle btn btn-sm btn-light" data-toggle="dropdown">Select Schools
                    <span class="caret"></span>
                </button>
                <div class="dropdown-menu dropdown-content-options">
                    <a class="small ml-4" style="cursor: pointer;" onclick="selectAllToggle('schools_options')">Select/Unselect All</a>
                    @foreach($school_options as $key => $value)
                        <span class="dropdown-content-item">
                            <input type="checkbox"
                                id="school_{{$key}}"
                                name="{{$value}}"
                                value="{{$value}}"
                                {{ in_array($value, $school_options) ? 'checked' : '' }}   
                                aria-describedby="school-selection"
                            />{{$value}}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <span class="dropdown ml-3">
            <button
                class="btn btn-info btn-sm dropdown-toggle"
                type="button"
                id="advance_settings"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-controls="advance_settings"
                aria-expanded="false"
            >
                <i class="fas fa-cog fa-fw"></i> Advanced Settings 
            </button>
            <div class="dropdown-menu p-3" id="advanced_settings" aria-labelledby="advance_settings">
                <div class="mt-3">
                    <small class="form-text text-muted">Students typically submit their applications before the start of the semester. By default, we include applications filed within a range starting 4 weeks before the semester begins and ending 4 weeks before it ends. For example, for Summer 2023, the included application period would be from May 4th, 2023, to August 8th, 2023. You can adjust this timeframe by changing the number of weeks below, then clicking 'Apply Filters'.</small>
                </div>
                <div class="row">
                    <div class="form-group mt-2 col-md-6">
                        <small class="form-text text-muted" for="weeks_before_semester_start">Weeks before semester's start:</small>
                        <input type="number" class="form-control form-text text-muted" id="weeks_before_semester_start" name="weeks_before_semester_start" value="4" step="1" min="3" max="6">
                    </div>
                    <div class="form-group mt-2 col-md-6">
                        <small class="form-text text-muted" for="weeks_before_semester_end">Weeks before semester's end:</small>
                        <input type="number" class="form-control form-text text-muted" id="weeks_before_semester_end" name="weeks_before_semester_end" value="4" step="1" min="3" max="6">
                    </div>
                </div>
            </div>
        </span>
        
        <div class="chart-actions ml-3">
            <button class="btn btn-primary btn-sm" id="apply_filters"><span class="fa fa-check fa-fw"></span> Apply Filters</button>
        </div>
    </div>
    
    <div class="row mt-3">
        <p class="small text-muted" wire:model="title" id="filters_title" class="text-muted mt-3">Showing Results For: {{$title[0]}} | {{$title[1]}}</p>
    </div>
    
    @php
        $style = $charts_loaded ? 'display:none;' : 'display:flex;'
    @endphp

    <!-- Modal -->
    <div id="loadingModal" class="modal" style="$style">
        <div class="modal-content">
            <div class="bar-chart">
                <div class="y-axis"></div>
                <div class="x-axis"></div>
                <div class="bars-container">
                    <div class="bar" style="--bar-height: 40%; --bar-color: #FFE6AD;"></div>
                    <div class="bar" style="--bar-height: 60%; --bar-color: #9AD0F6;"></div>
                    <div class="bar" style="--bar-height: 80%; --bar-color: #FFCFA3;"></div>
                    <div class="bar" style="--bar-height: 20%; --bar-color: #FFB1C1;"></div>
                </div>
            </div>
            <p>Loading charts, please wait...</p>
        </div>
    </div>

    <style>
        .dropdown-options {
            /* position: relative; */
            display: inline-block;
        }

        .dropdown-content-options {
            /* padding: 12px 16px; */
            margin: 0px !important;
            min-width: 160px;
        }

        .dropdown-options:hover .dropdown-content-options {
            display: block;
        }

        .dropdown-content-item {
            display: block;
        }

        .dropdown-menu#advanced_settings {
            width: 350% !important;
        }

        /* Modal styling */
        .modal {
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
            width: 300px; /* Set a fixed width for the modal */
        }

        .bar-chart {
            position: relative;
            width: 100%;
            height: 100px; /* Adjust height as needed */
        }

        .bars-container {
            display: flex;
            justify-content: space-around;
            align-items: flex-end; /* Align bars to the bottom */
            height: 100%;
        }

        .bar {
            width: 20%;
            background-color: var(--bar-color);
            height: var(--bar-height);
            animation: heightChange 1s ease-in-out infinite alternate; /* Changed animation name */
        }

        .bar:nth-child(1) {
            animation-delay: 0s;
        }

        .bar:nth-child(2) {
            animation-delay: 0.2s;
        }

        .bar:nth-child(3) {
            animation-delay: 0.4s;
        }

        .bar:nth-child(4) {
            animation-delay: 0.6s;
        }

        @keyframes heightChange {
            0% {
                height: var(--bar-height); /* Initial height */
                background-color: var(--bar-color);
            }
            50% {
                height: calc(var(--bar-height) + 20%); /* Increase height by 20% */
                background-color: lighten(var(--bar-color), 20%);
            }
            100% {
                height: var(--bar-height); /* Return to original height */
                background-color: var(--bar-color);
            }
        }

        .y-axis {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 1px;
            background-color: #CECECE; /* Y-axis color */
            height: 100%; /* Full height of the chart */
        }

        .x-axis {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 1px; /* X-axis thickness */
            background-color: #CECECE; /* X-axis color */
        }
    </style>
</div>

