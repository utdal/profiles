{{-- Component must have $sort_field, $sort_descending, and sortBy() --}}
<th style="min-width:3em">
    <a wire:click.prevent="sortBy('{{ $field }}')" href="#">
        <span wire:ignore>{!! $title ?? ucfirst($field) !!}</span>
        @if ($sort_field !== $field)
            <i class="fas fa-sort"><span class="mr-1"></span></i>
        @elseif ($sort_descending)
            <i class="fas fa-sort-down"><span class="mr-1"></span></i>
        @else
            <i class="fas fa-sort-up"><span class="mr-1"></span></i>
        @endif
    </a>
</th>