<div class="row ml-1">
    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_no]">Number</label>
        <input type="text" class="form-control"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_no]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][patent_no]"
               value="{{ $member['patent_no'] ?? '' }}">
    </div>

    <!-- jurisdiction selectpr -->
    <div class="col col-lg-3 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][jurisdiction]">Jurisdiction</label>
        <select class="form-control"
            id="jurisdiction_{{ $parent_id }}_{{ $index }}"
            name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][jurisdiction]">
            <option value="">Select a value</option>
            @foreach($jurisdictions as $jurisdiction)
                <option value="{{ $jurisdiction }}"
                    @selected(old("data.{$parent_id}.data.members.patent_{$index}.jurisdiction", $member['jurisdiction'] ?? '') === $jurisdiction)>
                    {{ $jurisdiction }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][status]">Status</label>
        <select class="form-control"
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
        <input type="text" class="form-control datepicker month"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][filed_date]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][filed_date]"
               value="{{ $member['filed_date'] ?? '' }}">
    </div>

    <div class="col col-lg-2 col-12">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][published_date]">Published Date</label>
        <input type="text" class="form-control datepicker month"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][published_date]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][published_date]"
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

    <div class="col col-lg-8 col-12 mt-2">
        <label for="data[{{ $parent_id }}][data][members][patent_{{ $index }}][title]">Title <small class="text-muted">(optional)</small></label>
        <input type="text" class="form-control"
               id="data[{{ $parent_id }}][data][members][patent_{{ $index }}][title]"
               name="data[{{ $parent_id }}][data][members][patent_{{ $index }}][title]"
               value="{{ $member['title'] ?? '' }}">
    </div>
</div>