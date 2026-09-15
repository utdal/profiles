@extends('profiles.edit.layout')

@section('section_name', 'Patents')

@php
    /**
     * Helper: compute the next available subrow ID for a patent.
     * Looks at the existing members object keys, extracts the integer part of "patent_N",
     * and returns max + 1. Returns 1 if no members exist.
     */
    $next_subrow_id = function ($members) {
        if (empty($members)) {
            return 1;
        }
        $max_n = 0;
        foreach (array_keys($members) as $key) {
            if (preg_match('/^patent_(\d+)$/', $key, $m)) {
                $max_n = max($max_n, (int) $m[1]);
            }
        }
        return $max_n + 1;
    };
@endphp

@section('form')
    @foreach ($data as $patent)
        <div class="row record form-group level lower-border"
             data-row-id="{{ $patent->id }}"
             data-supports-subrows="true">

            @include('profiles.edit._actions')

            <div class="col col-12">
                <input type="hidden" name="data[{{ $patent->id }}][id]" value="{{ $patent->id }}">
                <label for="data[{{ $patent->id }}][data][title]">Patents Group Title <small class="text-muted">(Enter an individual patent or a group of related patents)</small></label>
                <input type="text" class="form-control"
                       id="data[{{ $patent->id }}][data][title]"
                       name="data[{{ $patent->id }}][data][title]"
                       value="{{ $patent->title }}">
            </div>

            <div class="col col-12 mt-2">
                <div class="subrecords-controls d-flex align-items-center">
                    <button type="button"
                            class="btn btn-sm btn-link p-0 mr-3"
                            aria-expanded="false"
                            data-toggle-subrecords>
                        <i class="fas fa-chevron-down when-expanded" aria-hidden="true"></i>
                        <i class="fas fa-chevron-right when-collapsed" aria-hidden="true"></i>
                        <span>Patents Information</span>
                        <span class="subrecord-count badge badge-secondary ml-1">0</span>
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-outline-primary"
                            data-toggle="add_subrow">
                        <i class="fas fa-plus"></i> Add New Patent to Group
                    </button>
                </div>

                <div class="subrecords mt-2 border-left pl-3 ml-3"
                     data-next-subrow-id="{{ $next_subrow_id($patent->members ?? []) }}"
                     style="display:none;">

                    {{-- Hidden template to add_subrow() to clone new members --}}
                    <template id="patents-member-template-{{ $patent->id }}">
                        <div class="subrecord form-group" data-subrow-id="__template__">
                            @include('profiles.edit._patent_member_fields', [
                                'parent_id' => '__parent__',
                                'index' => '__index__',
                                'member' => null,
                            ])
                        </div>
                    </template>

                    {{-- Existing members — keys like patent_1, patent_2, ... preserved --}}
                    @foreach ($patent->members ?? [] as $member_key => $member)
                        @php
                            // Extract the integer part of "patent_N" for use as data-subrow-id
                            preg_match('/^patent_(\d+)$/', $member_key, $m);
                            $subrow_id = $m[1] ?? $loop->iteration;
                        @endphp
                        <div class="subrecord form-group" data-subrow-id="{{ $subrow_id }}">
                            @include('profiles.edit._patent_member_fields', [
                                'parent_id' => $patent->id,
                                'index' => $subrow_id,
                                'member' => $member,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endsection