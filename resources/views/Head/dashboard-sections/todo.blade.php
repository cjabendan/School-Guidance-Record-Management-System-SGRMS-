<div class="todo" data-user-id="{{ auth()->id() }}">
	<div class="todo-header">
		<h3>To-Do</h3>
	</div>

	<form id="todo-form" class="todo-form">
		<input type="text" id="todo-input" placeholder="Add a task..." autocomplete="off" />
		<button type="submit" id="todo-add">Add</button>
	</form>

	<ul id="todo-list" class="todo-list" aria-live="polite"></ul>

	<script src="{{ asset('js/todo.js') }}"></script>
</div>