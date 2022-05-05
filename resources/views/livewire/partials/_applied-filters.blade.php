<div class="applied_filters">
    @foreach($filter_names as $filter_name)
        @if($this->$filter_name !== '')
            <span wire:key="filter_badge_{{ $filter_name }}" class="badge badge-primary mr-1 mb-3">
                {{ Str::before($filter_name, '_filter') }}: 
                @if(isset($filter_value_names[$filter_name][$this->$filter_name]))
                    {{ $filter_value_names[$filter_name][$this->$filter_name] }}
                @elseif(in_array("{$this->$filter_name}", ['0', '1', '-1']))
                    {{ ['0' => 'No', '1' => 'Yes', '-1' => 'n/a']["{$this->$filter_name}"] }}
                @else
                    {{ $this->$filter_name }}
                @endif
                <button
                    wire:click="resetFilter('{{ $filter_name }}')"
                    type="button"
                    class="close float-none ml-2"
                    style="font-size: 1rem;"
                    aria-label="Clear Filter"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </span>
        @endif
    @endforeach
</div>