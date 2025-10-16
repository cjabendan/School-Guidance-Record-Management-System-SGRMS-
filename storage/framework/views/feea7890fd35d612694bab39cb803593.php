<div>
    <div class="flex space-x-4 mb-4">
        <button wire:click="switchTab('system')" class="<?php echo e($tab === 'system' ? 'font-bold' : ''); ?>">
            System
        </button>
        <button wire:click="switchTab('chatbot')" class="<?php echo e($tab === 'chatbot' ? 'font-bold' : ''); ?>">
            Chatbot
        </button>
        <button wire:click="switchTab('counselor')" class="<?php echo e($tab === 'counselor' ? 'font-bold' : ''); ?>">
            Counselor
        </button>
        <button wire:click="switchTab('parent')" class="<?php echo e($tab === 'parent' ? 'font-bold' : ''); ?>">
            Parent
        </button>
        <button wire:click="switchTab('student')" class="<?php echo e($tab === 'student' ? 'font-bold' : ''); ?>">
            Student
        </button>
    </div>

    <div class="mt-6">
        <!--[if BLOCK]><![endif]--><?php if($tab === 'system'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-settings');

$__html = app('livewire')->mount($__name, $__params, 'lw-1032236261-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php elseif($tab === 'chatbot'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-chatbot');

$__html = app('livewire')->mount($__name, $__params, 'lw-1032236261-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php elseif($tab === 'counselor'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-counselor');

$__html = app('livewire')->mount($__name, $__params, 'lw-1032236261-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php elseif($tab === 'parent'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-parent');

$__html = app('livewire')->mount($__name, $__params, 'lw-1032236261-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php elseif($tab === 'student'): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('settings.system-student');

$__html = app('livewire')->mount($__name, $__params, 'lw-1032236261-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>
<?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/livewire/settings/system.blade.php ENDPATH**/ ?>