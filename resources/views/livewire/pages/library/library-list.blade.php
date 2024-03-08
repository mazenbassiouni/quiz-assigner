<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Documents Library
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-800 select-none">

                @if (!$type && $first_filter)
                    <div class="flex justify-center">
                        <span class="text-3xl font-semibold">Select document type</span>
                    </div>

                    <div class="flex justify-around items-center h-80">
                        <div wire:click="setType('specialized')" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-academic-cap"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold">Specialized</div>
                        </div>

                        <div wire:click="setType('general')" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-cog-6-tooth"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold">General</div>
                        </div>

                        <div wire:click="setType()" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-clipboard-document-list"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold">All Docs</div>
                        </div>
                    </div>
                @elseif($type == 'specialized' && !$branch && $first_filter)
                    <div class="flex justify-between">
                        <x-filament::icon wire:click="backFirst()"
                            icon="heroicon-o-chevron-left"
                            class="h-8 w-8 dark:text-gray-400 cursor-pointer hover:scale-110"
                        />
                        <span class="text-3xl font-semibold">Select document branch</span>
                        <span></span>
                    </div>

                    <div class="flex justify-around items-center h-80 select-none">
                        <div wire:click="setBranch('OEM Courses')" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-rectangle-stack"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold" >OEM Courses</div>
                        </div>

                        <div wire:click="setBranch('Maintenance')" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-wrench-screwdriver"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold" >Maintenance</div>
                        </div>

                        <div wire:click="setBranch('Tech Docs')" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-beaker"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold" >Tech Docs</div>
                        </div>

                        <div wire:click="setBranch('General')" class="flex flex-col items-center justify-center cursor-pointer rounded-full h-56 w-56 hover:shadow-custom hover:scale-105">
                            <x-filament::icon
                                icon="heroicon-o-cog-6-tooth"
                                class="h-20 w-20 dark:text-gray-400"
                            />
                            <div class="text-center text-2xl font-semibold">General</div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-between">
                        <div class="flex">
                            <div class="flex items-center">
                                <span class="font-medium">Type:</span>
                                <x-filament::input.wrapper class="ml-2 w-44">
                                    <x-filament::input.select wire:model.live="type">
                                        <option value="">All</option>
                                        <option value="general">General</option>
                                        <option value="specialized">Specialized</option>
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>
    
                            <div class="flex items-center ml-4">
                                <span class="font-medium">Department:</span>
                                <x-filament::input.wrapper class="ml-2 w-44">
                                    <x-filament::input.select wire:model.live="department_id" :disabled="$type != 'specialized'">
                                        <option value="">All</option>
                                        @foreach ($departments as $dep)
                                            <option value="{{$dep->id}}">{{$dep->name}}</option>
                                        @endforeach
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>
    
                            <div class="flex items-center ml-4">
                                <span class="font-medium">Branch:</span>
                                <x-filament::input.wrapper class="ml-2 w-44">
                                    <x-filament::input.select wire:model.live="branch" :disabled="$type != 'specialized'">
                                        <option value="">All</option>
                                        @foreach ($branches as $br)
                                            <option value="{{$br}}">{{$br}}</option>
                                        @endforeach
                                    </x-filament::input.select>
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                        <div>
                            <x-filament::button wire:click="resetAll" size="md" color="gray">
                                Clear
                            </x-filament::button>
                        </div>
                    </div>
                    <hr class="mt-6 mb-4">
                    @foreach ($documents as $doc)
                        <div class="flex justify-between px-6 py-3 my-3 last:mb-0 border-b rounded-xl border-gray-200 hover:bg-gray-50/75">
                            <span>
                                {{$doc->name}}
                            </span>
                            <div class="relative" wire:loading.class="opacity-50">
                                <div class="z-50 w-full h-full absolute" wire:loading>
                        
                                </div>
                                <span class="cursor-pointer" wire:click="download('{{$doc->path}}','{{$doc->name}}')">
                                    <x-filament::icon
                                        icon="heroicon-s-folder-arrow-down"
                                        class="h-5 w-5 text-gray-600"
                                    />
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</div>