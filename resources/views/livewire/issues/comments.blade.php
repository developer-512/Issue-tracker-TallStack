<?php

use Livewire\Volt\Component;
use App\Models\Comment;
use App\Models\Issue;
use \WireUi\Traits\WireUiActions;

new class extends Component {
    use WireUiActions;
//    public Comment $comment;
    public $comments,$issue,$comment;

    public function mount(Issue $issue)
    {
        $this->issue=$issue;
        $this->comments=$issue->comments()->get();
    }
public function saveComment()
{
    $validate=$this->validate(['comment'=>['required','string','min:10']]);
    $this->issue->comments()->create([
       'comment'=>$this->comment,
        'user_id'=>auth()->id()
    ]);
    $this->notification()->success(
        $title = 'Comment posted',
        $description = 'Your Comment is successfully posted. ',
    );
    $this->comment='';
    $this->mount($this->issue);
}
}; ?>

<div class="space-y-4">
    <h2 class="text-lg mt-4">
        Comments:
    </h2>

    <form wire:submit="saveComment" post>

        <x-textarea wire:model="comment" label="New Comment"></x-textarea>
        <div class="mt-4">
            <x-button type="submit" >Post Comment</x-button>
        </div>


    </form>
        <div class="mt-5">
            @forelse($comments as $comment)
                <div class="flex mt-3">
                    <x-card title="{{$comment->user->name}} ({{$comment->user->id==auth()->id()?'OP':'Team'}})" class="w-full" shadow="lg">
                        {{$comment->comment}}
                    </x-card>
                </div>
            @empty

                <p class="text-lg text-center">No Comments on this Issue</p>
            @endforelse
        </div>
</div>
