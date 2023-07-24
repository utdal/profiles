<?php

namespace App\Http\Livewire;

use App\Helpers\Contracts\LdapHelperContract;
use App\Http\Livewire\Concerns\ConvertEmptyStringsToNull;
use App\User;
use App\UserDelegation;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class UserDelegations extends Component
{
    use AuthorizesRequests, ConvertEmptyStringsToNull;

    /** @var User */
    public $user;

    public $new_delegation = [];

    protected $rules = [
        'new_delegation.name' => 'required|alpha_num',
        'new_delegation.starting' => 'required|date',
        'new_delegation.until' => 'sometimes|nullable|date',
        'new_delegation.gets_reminders' => 'sometimes|boolean',
    ];

    protected $messages = [
        'new_delegation.name.required' => 'Missing username. You must select a person from the search drop-down to fill this field.',
        'new_delegation.name.alpha_num' => 'Invalid username. The delegate username must be alphanumeric.',
        'new_delegation.starting.required' => 'A starting date is required.',
        'new_delegation.starting.date' => 'The starting date must be a valid date.',
        'new_delegation.until.date' => 'The optional ending date must be a valid date.',
        'new_delegation.gets_reminders.boolean' => 'The gets reminders field must be true/false.',
    ];

    protected $listeners = [
        'profiles.directorySearch.selected' => 'selectPerson',
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->resetNewDelegation();
    }

    public function resetNewDelegation(): void
    {
        $this->new_delegation = [
            'starting' => now()->toFormattedDateString(),
        ];
    }

    public function selectPerson(string $username): void
    {
        $this->new_delegation['name'] = $username;
    }

    public function add(LdapHelperContract $ldap): void
    {
        $this->authorize('create', [UserDelegation::class, $this->user]);
        $this->validate();

        $new_delegate = $ldap->getUser($this->new_delegation['name']);

        if ($new_delegate) {
            $this->user->delegates()->attach($new_delegate->id, [
                'starting' => $this->new_delegation['starting'] ?? now(),
                'until' => $this->new_delegation['until'] ?? null,
                'gets_reminders' => $this->new_delegation['gets_reminders'] ?? false,
            ]);

            $this->emit('alert', "Delegate saved.", 'success');
            $this->emit('profiles.directorySearch.reset');
            $this->resetNewDelegation();
        } else {
            $this->emit('alert', "Unable to save delegate", 'danger');
        }
    }

    public function destroy(UserDelegation $delegation): void
    {
        $this->authorize('delete', $delegation);

        $delegation->delete();

        $this->emit('alert', "Removed delegation", 'success');
    }

    public function render(): View|ViewContract
    {
        return view('livewire.user-delegations', [
            'delegations' => $this->user->delegations()->with('delegate')->get(),
        ]);
    }
}
