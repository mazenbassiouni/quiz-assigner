<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight flex justify-between">
        <div>
            <b>{{ count($quiz->questions) }}</b> questions / <b>{{ $quiz->duration }}</b> minutes
        </div>
        <div class="select-none" id="quizTimer">00:00:00</div>
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form class="p-6 text-gray-800 select-none text-lg" wire:submit="save(Object.fromEntries(new FormData($event.target)))">
                @foreach ($quiz->questions as $key => $qst)
                    @php
                        $qst = \App\Models\Question::find($qst);
                    @endphp
                    <div class="my-3">
                        <div>
                            <b class="mr-4">{{$loop->iteration}})</b>{{$qst->title}}
                        </div>
                        @if ($qst->type == 'mcq')
                            @foreach ($qst->answers as $asr)
                                <div class="flex items-center pl-10 my-1">
                                    <input type="radio" id="{{$qst->id}}-{{$asr->id}}" class="mr-3" name="{{$qst->id}}" value="{{$asr->id}}">
                                    <label for="{{$qst->id}}-{{$asr->id}}">{{$asr->title}}</label>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center pl-10 my-1">
                                <input type="radio" id="{{$qst->id}}-true" class="mr-3" name="{{$qst->id}}" value="1">
                                <label for="{{$qst->id}}-true">True</label>
                            </div>

                            <div class="flex items-center pl-10 my-1">
                                <input type="radio" id="{{$qst->id}}-false" class="mr-3" name="{{$qst->id}}" value="0">
                                <label for="{{$qst->id}}-false">False</label>
                            </div>
                        @endif
                    </div>
                @endforeach

                <div class="flex justify-end mt-5">
                    <x-filament::button type="submit" size="md" color="gray" id="submitQuiz" wire:loading.attr="disabled">
                        <svg wire:loading aria-hidden="true" class="inline w-5 h-5 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-gray-600 dark:fill-gray-300" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                        </svg>
                        Submit Answers
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Set the date we're counting down to
    var countDownDate = new Date().getTime() + {{$quiz->duration}}*60*1000;
    
    // Update the count down every 1 second
    var x = setInterval(function() {
    
        // Get today's date and time
        var now = new Date().getTime();
            
        // Find the distance between now and the count down date
        var distance = countDownDate - now;
            
        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        hours = hours < 10 ? '0'+ hours : hours;
        minutes = minutes < 10 ? '0'+ minutes : minutes;
        seconds = seconds < 10 ? '0'+ seconds : seconds;
            
        // Output the result in an element with id="demo"
        document.getElementById("quizTimer").innerHTML = hours + ":"
        + minutes + ":" + seconds;
            
        if(distance < 60000){
            document.getElementById("quizTimer").classList.add('text-red-500');
        }

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("quizTimer").innerHTML = "TIME IS UP!";
            document.getElementById("submitQuiz").click();
        }
    }, 1000);
    </script>