<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Issue;
use \WireUi\Traits\WireUiActions;

new #[Layout('layouts.app')] class extends Component {
    use WireUiActions;
    public $title,$description,$status,$priority,$team;
    public Issue $issue;
    public function mount(Issue $issue)
    {
        $this->title=$issue->title??'';
        $this->description=$issue->description??'';
        $this->status=$issue->status??'';
        $this->priority=$issue->priority??'low';

        $this->team=(auth()->user()->isTeamMember()||auth()->user()->isAdmin());
    }


    public function submitIssue()
    {
        $validated=$this->validate([
            'title'=>['required','string','min:5'],
            'description'=>['required','string','min:25'],
            'priority'=>['required'],
        ]);
        if(isset($this->issue->id)){
            $this->issue->update([$validated]);
            $this->notification()->success(
                $title = 'Issue Updated',
                $description = 'Your Issue is successfully updated '
            );
        }else{
            auth()->user()->issues()->create($validated);

            $this->notification()->success(
                $title = 'Issue Submitted',
                $description = 'Your Issue is successfully submitted ',
            );
        }

    }
    //
}; ?>

<x-slot name="header">
    <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Report New Issue') }}
        </h2>
        <div>
            <x-button secondary href="{{route('issues.index')}}">Back to All</x-button>
        </div>
    </div>

</x-slot>

<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-3">
                <form  wire:submit="submitIssue" method="post" class="space-y-4">
                    <x-input wire:model="title" label="Title"></x-input>
                    <x-textarea wire:model="description" class="text-white dark:text-white" label="Description"></x-textarea>
                    @if($team)
                    <x-native-select
                    wire:model="status"
                    :options="['open', 'in_progress', 'closed']"
                    label="Status"
                    />
                    @endif
                    <x-native-select
                        wire:model="priority"
                        :options="['low', 'medium', 'high']"
                        label="Priority"
                    />
                    <div class="flex justify-center">
                        <x-button danger type="submit">Submit</x-button>
                    </div>
                    <x-errors/>
                </form>
            </div>
        </div>
    </div>
</div>
