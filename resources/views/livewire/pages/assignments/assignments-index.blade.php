<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Assigned Quizzes
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-800 select-none">
                @foreach (auth()->user()->assignedQuizzes as $quiz)
                    <div class="flex justify-between px-6 py-3 my-3 last:mb-0 border-b rounded-xl border-gray-200 hover:bg-gray-50/75">
                        <span>
                            <b>{{ count($quiz->questions) }}</b> questions in <b>{{ $quiz->duration }}</b> minutes 
                        </span>
                        <span class="cursor-pointer">
                            <x-filament::button :href="route('take.quiz', $quiz->id)" tag="a" wire:navigate size="md" color="gray">
                                Take Quiz
                            </x-filament::button>
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>