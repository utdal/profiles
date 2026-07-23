<div class="row ml-1">
    <input type="hidden"
           name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_internal_id]"
           value="{{ $member['patent_internal_id'] ?? '' }}">

    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_no]">Number</label>
        <input type="text" class="form-control mb-1"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_no]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_no]"
               value="{{ $member['patent_no'] ?? '' }}">
    </div>

    <!-- jurisdiction selectpr -->
    @use(App\Helpers\Country)
    <div class="col col-lg-3 col-12">
        <label for="jurisdiction_{{ $parent_id }}_{{ $index }}">Jurisdiction</label>
        <select class="form-control mb-1"
            id="jurisdiction_{{ $parent_id }}_{{ $index }}"
            name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][jurisdiction]"
            required
        >
            <option value="">Select a jurisdiction…</option>
            @foreach(Country::all() as $code => $name)
                <option
                    value="{{ $code }}"
                    @selected(old("data.{$parent_id}.data.members.patent_{$index}.jurisdiction", $member['jurisdiction'] ?? '') === $code)
                >
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][status]">Status</label>
        <select class="form-control mb-1"
                id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][status]"
                name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][status]">
            @php $current_status = $member['status'] ?? 'published'; @endphp
            <option value="granted" {{ $current_status === 'published' ? 'selected' : '' }}>Published</option>
            <option value="pending" {{ $current_status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="expired" {{ $current_status === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
    </div>

    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][filed_date]">Filed Date</label>
        <input type="text" class="form-control mb-1"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][filed_date]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][filed_date]"
               data-provide="datepicker"
               data-date-format="MM d, yyyy"
               data-date-autoclose="true"
               value="{{ $member['filed_date'] ?? '' }}">
    </div>

    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][published_date]">Published Date</label>
        <input type="text" class="form-control mb-1"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][published_date]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][published_date]"
               data-provide="datepicker"
               data-date-format="MM d, yyyy"
               data-date-autoclose="true"
               value="{{ $member['published_date'] ?? '' }}">
    </div>

    <div class="col col-lg-1 col-12 subrecord-actions d-flex justify-content-end">
        <button type="button" class="btn btn-sm subrow-duplicate" title="Duplicate this member">
            <i class="fas fa-copy text-secondary"></i>
        </button>
        <button type="button" class="btn btn-sm trash" title="Remove this member">
            <i class="fas fa-times text-danger"></i>
        </button>
    </div>

    <div class="col col-lg-11 col-12 mt-1">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][title]">Title <small class="text-muted">(optional)</small></label>
        <input type="text" class="form-control mb-5"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][title]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][title]"
               value="{{ $member['title'] ?? '' }}">
    </div>
</div>