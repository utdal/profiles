<div class="card profile-card">
  <a href="{{ $profile->url }}" aria-label="{{$profile->name}}, view profile">
    <img class="card-img-top" src="{{ $profile->image_url }}" alt="profile image">
  </a>
  <div class="card-body">
      @if($profile->isInMemoriam())
          <small class="badge badge-secondary">In Memory of</small>
      @endif
      <h5 class="card-title profile-name">
        <a href="{{ $profile->url }}">{{$profile->name}}</a>
      </h5>
      @if($profile->information[0]->distinguished_title)
      <p class="card-text profile-title">{{ $profile->information[0]->distinguished_title }}</p>
      @endif
      @if($profile->information[0]->title)
      <p class="card-text profile-title">{{ $profile->information[0]->title }}</p>
      @endif
  </div>
  <a href="{{ $profile->url }}" class="card-footer card-link text-center" aria-label="{{$profile->name}}, view profile">
      @if($profile->isInMemoriam())
          Profile In Memoriam
      @else
          View Profile
      @endif
  </a>
</div>
