<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Issue;
new #[Layout('layouts.app')] class extends Component {



    public function placeholder()
    {
        return <<<'HTML'
                    <div role="status">
                        <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        <span class="sr-only">Loading...</span>
                    </div>
                    HTML;
    }
    public function with() : array
    {

       $issues=auth()->user()->issues()->orderBy('id')->get();
        if(auth()->user()->isTeamMember()){
            $issues=auth()->user()->assignedIssues()->orderBy('id')->get();
        }elseif (auth()->user()->isAdmin()){
            $issues=Issue::all();
        }
        return [
            'issues'=>$issues,
            'statuses'=>Issue::STATUS,
            'priorities'=>Issue::PRIORITY,
            'team'=>(!auth()->user()->isUser())
        ];
    }

}; ?>

<div class="">
    {{--   <p class="mb-5 text-xl font-bold">{{$title}}</p><hr>--}}
    <div class="space-y-2 ">

        @if($issues->isEmpty())


            <div class="text-center">
                <p class="text-xl font-bold">
                    No issues Ticket yet
                </p>
                @if(!$team)
                <p class="text-sm">
                     Submit your Support Issues here
                </p>
                @endif
                <x-button primary icon="plus" class="mt-6" href="{{route('issues.create')}}">Submit New Issue Ticket</x-button>
            </div>
        @else
            <x-button light info icon="plus" class="mt-6 mb-6" href="{{route('issues.create')}}">Submit New Issue Ticket</x-button>
            <div class="grid grid-cols-3 gap-4 mt-12">
                @foreach ($issues as $issue)
                    <x-card wire:key='{{$issue->id}}' class="dark:bg-blue-950 dark:text-white">
                        <div class="flex justify-between">
                            <div>
                                <a href="{{route('issues.view',$issue)}}" wire:navigate class="text-xl font-bold hover:underline dark:text-white hover:text-blue-500">
                                    {{$issue->title}}
                                </a>
                                <p class="text-xs mt-2">{{Str::limit($issue->description,30)}}</p>
                            </div>

{{--                            <div class="text=xs text-gray-400">{{\Carbon\Carbon::parse($note->send_date)->format('d/m/Y')}}</div>--}}
                        </div>
                        <div class="flex items-end justify-between mt-4 space-x-1">
                            <p class="text-sm">Status: <span  class="{{$issue->status=='open'?'text-red-700':($issue->status=='in_progress'?'text-warning-600':'text-accent-green-700')}} font-bold" >{{$statuses[$issue->status]}}</span></p>
                            <p class="text-sm">Priority: <span  class="text-{{$issue->priority=='low'?'blue-600':($issue->priority=='medium'?'warning-600':'red-700')}} font-bold" >{{$priorities[$issue->priority]}}</span></p>
                        </div>
                        <div class="flex items-end justify-between mt-4 space-x-1">
                            <p class="text-xs">@if(!$team) Assigned To : <span class="font-semibold dark:text-white">{{$issue->assignedTo->name??'Not Assigned Yet'}}</span>
                                @else
                                    Issue Reported By : <span class="font-semibold">{{$issue->user->name??'Not Assigned Yet'}}</span>
                            @endif
                            </p>
                            <div>
                                <x-mini-button rounded info icon="eye"/>
                                <x-mini-button rounded negative  icon="trash" wire:click="delete('{{$issue->id}}')"/>

                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>
        @endif

    </div>
</div>
