<div>
    <div class="flex space-x-4 mb-4">
        <button wire:click="switchTab('system')" class="{{ $tab === 'system' ? 'font-bold' : '' }}">
            System
        </button>
        <button wire:click="switchTab('chatbot')" class="{{ $tab === 'chatbot' ? 'font-bold' : '' }}">
            Chatbot
        </button>
        <button wire:click="switchTab('counselor')" class="{{ $tab === 'counselor' ? 'font-bold' : '' }}">
            Counselor
        </button>
        <button wire:click="switchTab('parent')" class="{{ $tab === 'parent' ? 'font-bold' : '' }}">
            Parent
        </button>
        <button wire:click="switchTab('student')" class="{{ $tab === 'student' ? 'font-bold' : '' }}">
            Student
        </button>
    </div>

    <div class="mt-6">
        @if ($tab === 'system')
            @livewire('settings.system-settings')
        @elseif ($tab === 'chatbot')
            @livewire('settings.system-chatbot')
        @elseif ($tab === 'counselor')
            @livewire('settings.system-counselor')
        @elseif ($tab === 'parent')
            @livewire('settings.system-parent')
        @elseif ($tab === 'student')
            @livewire('settings.system-student')
        @endif
    </div>
</div>
