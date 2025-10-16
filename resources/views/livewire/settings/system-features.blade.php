 <div class="space-y-6">


     <h2 class="text-lg font-semibold mt-6">Features</h2>
     @foreach ($features as $feature)
         <div class="flex items-center justify-between">
             <span>{{ $feature['name'] }}</span>
             <button wire:click="toggleFeature('{{ $feature['key'] }}')"
                 class="px-3 py-1 rounded {{ $feature['enabled'] ? 'bg-green-500' : 'bg-gray-400' }}">
                 {{ $feature['enabled'] ? 'Enabled' : 'Disabled' }}

             </button>

         </div>
     @endforeach
     <div class="mt-3 text-sm text-gray-500" wire:loading>
         Saving changes...
     </div>
 </div>
 </div>
