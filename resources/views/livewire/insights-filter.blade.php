    @push('scripts')
    <script>
        const button = document.getElementById("apply_filters")

        button.addEventListener('click', function() {
            let selected_semesters = buildFilterParameters('semesters_options');
            let selected_schools = buildFilterParameters('schools_options');
            Livewire.emit('applyFilters', selected_semesters, selected_schools);
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
            if (fieldset.dataset.allchecked === "true" ) {
                checkboxes.forEach(chckbx => chckbx.checked = false ); 
                fieldset.dataset.allchecked = "false";
            }
            else { 
                checkboxes.forEach(chckbx => chckbx.checked = true ); 
                fieldset.dataset.allchecked = "true";
            }
        }

    </script>
@endpush

<div class="row mt-3">
    <div class="">
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

    <div class="chart-actions ml-3">
        <button class="btn btn-info btn-sm" id="show_advanced_settings" name="show_advanced_settings" data-toggle="show" data-toggle-value="true"  data-toggle-target="#advanced_settings"><span class="fa fa-cog fa-fw"></span> Advanced Settings</button>
    </div>

    <div class="chart-actions ml-3">
        <button class="btn btn-primary btn-sm" id="apply_filters"><span class="fa fa-check fa-fw"></span> Apply Filters</button>
    </div>

    <div class="row" id="advanced_settings">
        <div class="ml-3 mt-3 offset-md-11">
            <button type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="ml-3 mt-3">
            <small>To ensure accurate results, the data displayed is set by default to cover a period from three weeks before the start of the semester to three weeks before its end, as student research applications are reviewed and filed for the following semester. You can adjust this timeframe by using the sliders below to select the number of weeks that best suits your needs.</small>
        </div>
        <div class="form-group mt-2 col-md-3">
            <small class="form-label" for="weeks_before_semester_start">Weeks before semester start:</small>
            <input type="number" class="form-control" id="weeks_before_semester_start" name="weeks_before_semester_start" value="4" step="1" min="3" max="6">
        </div>
        <div class="form-group mt-2 col-md-3">
            <small class="form-label" for="weeks_before_semester_end">Weeks before semester end:</small>
            <input type="number" class="form-control" id="weeks_before_semester_end" name="weeks_before_semester_end" value="4" step="1" min="3" max="6">
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

    </style>
</div>
    

