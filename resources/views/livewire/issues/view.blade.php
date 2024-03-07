<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Issue;
use App\Models\User;
use WireUi\Traits\WireUiActions;


new #[Layout('layouts.app')] class extends Component {
    use WireUiActions;
    public Issue $issue;
    public $assigned_to;
    public $Team_members,$status,$priority,$active_status;

    public function mount(Issue $issue)
    {
        $this->issue=$issue;
        $this->active_status=$this->issue->status;
        $this->assigned_to=$this->issue->assigned_to;
        $this->Team_members = User::where('role', 'team_member')
            ->get()
            ->map(function ($user) {
                return ['name' => $user->name, 'id' => $user->id];
            })->toArray();
       $this->status= array_map(function ($value,$index) {
            return ['id' => $index, 'name' => $value];
        }, Issue::STATUS,array_keys(Issue::STATUS));
    }
    public function assignThis()
    {
        $this->issue->update(['assigned_to'=>$this->assigned_to]);
        $this->notification()->success(
            $title = 'Issue Assigned',
            $description = "This Issue is assigned to {$this->issue->assignedTo->name}",
        );
    }
    public function statusUpdate()
    {
        $this->issue->update(['status'=>$this->active_status]);
        $status=Issue::STATUS[$this->active_status];
        $this->notification()->success(
            $title = 'Issue Ticket Status updated',
            $description = "This Issue Ticket Status changes to {$status}",
        );
    }
}; ?>

<x-slot name="header">
    <div class="flex justify-between">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $issue->title }} <span class="text-xs">by {{$issue->user->name}}</span>
        </h2>
        <div>
            <x-button secondary href="{{route('issues.index')}}">Back to All</x-button>
        </div>
    </div>

</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-3 border-b-2">
               <h2 class="text-2xl">{{$issue->title}}</h2>
                <p class="mt-2">{{$issue->description}}</p>
                <div class="grid grid-cols-2 space-x-6">
                    <div class="grid grid-cols-1 ">
                        <p>User: <span class="font-semibold">{{$issue->user->name}}</span></p>
                        <p><div class="grid grid-cols-1">Assigned To:
                        @if(auth()->user()->isAdmin())
                                <form wire:submit="assignThis">
                                    <x-native-select
                                        label=""
                                        wire:model="assigned_to"
                                        :options="$Team_members"
                                        wire:change="assignThis"
                                        option-value="id"
                                        option-label="name"
                                        placeholder="Not Assigned Yet"
                                        class="w-2"
                                    />
                                </form>
                            @else
                                <span class="font-semibold">{{$issue->assignedTo->name??'Not Assigned Yet'}}</span>
                        @endif
                    </div>
                        </p>
                    </div>
                    <div class="grid grid-cols-1 space-y-4">
                        <p>Priority:
                            <span  class="text-{{$issue->priority=='low'?'blue-600':($issue->priority=='medium'?'warning-600':'red-700')}}" >{{Str::of($issue->priority)->ucfirst()}}</span>
                        </p>
                        <p><div class="grid grid-cols-1">Status:
                        @if(!auth()->user()->isUser())
                            <form wire:submit="statusUpdate">
                                <x-native-select
                                    label=""
                                    wire:model="active_status"
                                    :options="$status"
                                    wire:change="statusUpdate"
                                    option-value="id"
                                    option-label="name"
                                    placeholder="Update Status"
                                />
                            </form>
                            @else
                                <span class="font-semibold">{{$issue->status}}</span>
                            @endif
                        </div>
                        </p>
                    </div>
                </div>

            </div>
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-3">
                <livewire:issues.comments :issue="$issue"/>
            </div>
        </div>
    </div>
</div>
