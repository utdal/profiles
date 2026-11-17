
<div class="placeholder">
    <div class="placeholder-name"></div>
    <div class="placeholder-btn archive"></div>
    <div class="placeholder-btn edit"></div>
</div>
<div class="placeholder">
    <div class="placeholder-title"></div>
</div>
<div class="placeholder">
    <i class="placeholder-icon fa fa-fw fa-envelope" aria-label="Email address"></i> <div class="placeholder-contact"></div>
</div>
<div class="placeholder">
    <i class="placeholder-icon fa fa-fw fa-phone" aria-label="Phone number"></i> <div class="placeholder-contact"></div>
</div>
<div class="placeholder">
    <i class="placeholder-icon fa fa-fw fa-map-marker" aria-label="Location"></i> <div class="placeholder-contact"></div>
</div>
<div class="placeholder">
    <i class="placeholder-icon fa fa-fw fa-tags" aria-label="Tag"></i>
    @foreach(range(1, 3) as $i)
        <div class="placeholder-btn tag"></div>
    @endforeach
</div>
<style>
    .placeholder {
        height: 1rem;
        margin-bottom: 0.5rem;
        display: flex;
        gap: 0.5rem;
        overflow: hidden;
    }

    .placeholder-name {
        border-radius: 3px;
        background-color: #e9ecef;
        flex-grow: 0.80;
        height: 1rem;
    }
    
    .placeholder-btn {
        height: 0.85rem;
        flex-grow: 0.2;
        border-radius: 3px;
    }
    .placeholder-btn.archive {
        background-color: #dd94aa;
    }
    
    .placeholder-btn.edit {
        background-color: #7faaa6;
    }

    .placeholder-btn.tag {
        background-color: #7faaa6;
        flex-grow: 20;
    }

    .placeholder-title {
        border-radius: 3px;
        background-color: #e9ecef;
        flex-grow: 1;
        height: 1rem;
    }
    
    .placeholder-contact {
        border-radius: 3px;
        background-color: #e9ecef;
        flex-grow: 100;
    }

    .placeholder-icon {
        color: #e9ecef;
        flex-grow: 0.5;
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    @media (max-width: 576px) { /* Below Bootstrap sm (576px) */
    .flex-wrap-sm-only {
        flex-wrap: wrap !important;
    }
}
</style>