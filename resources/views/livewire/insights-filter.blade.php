@push('scripts')
    <script>
        const button = document.getElementById('apply_filters')

        button.addEventListener('click', function() {
            let selected_semesters = buildFilterParameters('semesters_options');
            let selected_schools = buildFilterParameters('schools_options');
            let weeks_before_semester_start = document.getElementById('weeks_before_semester_start').value;
            let weeks_before_semester_end = document.getElementById('weeks_before_semester_end').value;
            Livewire.emit('applyFilters', selected_semesters, selected_schools, weeks_before_semester_start, weeks_before_semester_start);
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
                                checked="{{ in_array($value, $semester_options) ?? false}}" 
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
                                checked="{{ in_array($value, $school_options) ?? false}}" 
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

        /* .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            padding: 12px 16px;
            z-index: 1;
        } */

        .dropdown-content-item {
            display: block;
        }

        .dropdown-menu#advanced_settings {
            width: 350% !important;
        }
    </style>
</div>

