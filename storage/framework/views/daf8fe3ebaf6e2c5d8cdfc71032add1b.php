<div class="todo" data-user-id="<?php echo e(auth()->id()); ?>">
	<div class="todo-header">
		<h3>To-Do</h3>
	</div>

	<form id="todo-form" class="todo-form">
		<input type="text" id="todo-input" placeholder="Add a task..." autocomplete="off" />
		<button type="submit" id="todo-add">Add</button>
	</form>

	<ul id="todo-list" class="todo-list" aria-live="polite"></ul>

	<script src="<?php echo e(asset('js/todo.js')); ?>"></script>
</div><?php /**PATH C:\Users\Administrator\School-Guidance-Record-Management-System-SGRMS\resources\views/Counselor/dashboard-sections/todo.blade.php ENDPATH**/ ?>