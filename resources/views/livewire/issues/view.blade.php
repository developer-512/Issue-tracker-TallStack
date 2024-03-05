<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Issue;
new #[Layout('layouts.app')] class extends Component {
    public Issue $issue;

    public function mount(Issue $issue)
    {
        $this->issue=$issue;
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
                    <div class="flex justify-between ">
                        <p>User: <span class="font-semibold">{{$issue->user->name}}</span></p>
                        <p>Assigned To: <span class="font-semibold">{{$issue->assignedTo->name??'Not Assigned Yet'}}</span></p>
                    </div>
                    <div class="flex justify-between">
                        <p>Priority:
                            <span  class="text-{{$issue->priority=='low'?'blue-600':($issue->priority=='medium'?'warning-600':'red-700')}}" >{{Str::of($issue->priority)->ucfirst()}}</span>
                        </p>
                        <p>Status: <span class="font-semibold">{{$issue->status}}</span></p>
                    </div>
                </div>

            </div>
            <div class="p-6 text-gray-900 dark:text-gray-100 space-y-3">
                <livewire:issues.comments :issue="$issue"/>
            </div>
        </div>
    </div>
</div>
