<?php

namespace App\Http\Livewire;

use App\Helpers\Contracts\LdapHelperContract;
use Livewire\Component;

class DirectorySearch extends Component
{
    public $query = '';

    public $people = [];

    public $input_id = 'directorySearch';

    public $input_name = 'selected_username';

    public $aria_describedby = '';

    public $required = true;

    public $selected_username = '';

    public $displayname_attribute = 'displayname';

    public $username_attribute = 'samaccountname';

    public $title_attribute = 'title';

    protected $listeners = [
        'profiles.directorySearch.reset' => 'resetAll',
    ];

    protected $ldap;

    protected $limit = 15;

    public function mount(LdapHelperContract $ldap)
    {
        $this->query = '';
        $this->people = [];
        $this->selected_username = '';
        $this->displayname_attribute = $ldap->displayname_attribute ?? 'displayname';
        $this->username_attribute = $ldap->username_attribute ?? 'samaccountname';
        $this->title_attribute = $ldap->schema->title() ?? 'title';
    }

    public function booted(LdapHelperContract $ldap)
    {
        $this->ldap = $ldap;
    }

    public function resetAll()
    {
        $this->reset(['query', 'people', 'selected_username']);
    }

    public function resetSearch()
    {
        $this->reset(['query', 'people']);
    }

    public function resetSelected()
    {
        $this->reset(['selected_username']);
        $this->dispatch('profiles.directorySearch.selected', username: '');
    }

    public function selectPerson($index)
    {
        $this->query = $this->people[$index][$this->displayname_attribute] ?? $this->query;
        $this->selected_username = $this->people[$index][$this->username_attribute] ?? '';

        $this->dispatch('profiles.directorySearch.selected', username: $this->selected_username);
    }

    public function updatedQuery()
    {
        $this->resetSelected();

        $search_results = $this->ldap->search($this->query, [
            $this->ldap->schema->loginName(),
            $this->ldap->schema->displayName(),
            $this->ldap->schema->department(),
            $this->ldap->schema->title(),
            $this->ldap->schema->email(),
            $this->ldap->schema->telephone(),
            $this->ldap->schema->physicalDeliveryOfficeName(),
        ], true);

        $this->people = array_slice($search_results, 0, $this->limit);

        $this->dispatch('profiles.directorySearch.query.updated');
    }

    public function render()
    {
        return view('livewire.directory-search');
    }
}
