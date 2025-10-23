document.addEventListener('DOMContentLoaded', function () {
    const todoRoot = document.querySelector('.todo');
    if (!todoRoot) return;

    const userId = todoRoot.dataset.userId || 'guest';
    const storageKey = `sgrms_todo_${userId}`;

    const form = document.getElementById('todo-form');
    const input = document.getElementById('todo-input');
    const list = document.getElementById('todo-list');

    // Utility: Load tasks from localStorage
    function loadTasks() {
        try {
            const raw = localStorage.getItem(storageKey);
            return raw ? JSON.parse(raw) : [];
        } catch {
            return [];
        }
    }

    // Utility: Save tasks
    function saveTasks(tasks) {
        localStorage.setItem(storageKey, JSON.stringify(tasks));
    }

    // Escape HTML for safety
    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Render all tasks
    function render() {
        const tasks = loadTasks();
        list.innerHTML = '';
        tasks.forEach((t, idx) => {
            const li = document.createElement('li');
            li.className = 'todo-item';
            li.dataset.index = idx;

            li.innerHTML = `
                <label class="todo-row">
                    <input type="checkbox" class="todo-check" ${t.done ? 'checked' : ''} />
                    <span class="todo-text" contenteditable="false" tabindex="0">${escapeHtml(t.text)}</span>
                </label>
                <div class="todo-actions">
                    <button class="todo-delete" aria-label="Delete">✕</button>
                </div>
            `;
            list.appendChild(li);
        });
    }

    // Add task
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const val = input.value.trim();
        if (!val) return;
        const tasks = loadTasks();
        tasks.unshift({ text: val, done: false, created_at: Date.now() });
        saveTasks(tasks);
        input.value = '';
        render();
    });

    // Delete
    list.addEventListener('click', function (e) {
        const li = e.target.closest('.todo-item');
        if (!li) return;
        const idx = Number(li.dataset.index);
        const tasks = loadTasks();

        if (e.target.classList.contains('todo-delete')) {
            tasks.splice(idx, 1);
            saveTasks(tasks);
            render();
        }
    });

    // Double-click to enable editing
    list.addEventListener('dblclick', function (e) {
        const textEl = e.target.closest('.todo-text');
        if (!textEl) return;
        const li = e.target.closest('.todo-item');
        li.classList.add('editing'); // disable hover while editing
        textEl.setAttribute('contenteditable', 'true');
        textEl.focus();
        placeCaretAtEnd(textEl);
    });

    // Handle Enter key while editing
    list.addEventListener('keydown', function (e) {
        if (!e.target.classList.contains('todo-text')) return;
        if (e.key === 'Enter') {
            e.preventDefault();
            e.target.blur();
        }
    });

    // Save on blur and exit editing
    list.addEventListener('blur', function (e) {
        if (!e.target.classList.contains('todo-text')) return;
        const li = e.target.closest('.todo-item');
        const idx = Number(li.dataset.index);
        const tasks = loadTasks();
        tasks[idx].text = e.target.innerText.trim();
        saveTasks(tasks);
        e.target.setAttribute('contenteditable', 'false');
        li.classList.remove('editing'); // re-enable hover
        render();
    }, true);

    // Mark as done (auto-remove)
    list.addEventListener('change', function (e) {
        if (!e.target.classList.contains('todo-check')) return;
        const li = e.target.closest('.todo-item');
        const idx = Number(li.dataset.index);
        const tasks = loadTasks();
        tasks[idx].done = e.target.checked;
        saveTasks(tasks);

        if (e.target.checked) {
            setTimeout(() => {
                const newTasks = loadTasks();
                newTasks.splice(idx, 1);
                saveTasks(newTasks);
                render();
            }, 350);
        }
    });

    // Temporary hover disable on click press
    list.addEventListener('mousedown', e => {
        const li = e.target.closest('.todo-item');
        if (li) li.classList.add('editing');
    });

    list.addEventListener('mouseup', e => {
        const li = e.target.closest('.todo-item');
        if (li && !li.querySelector('.todo-text[contenteditable="true"]')) {
            li.classList.remove('editing');
        }
    });

    // Auto-hide scrollbar logic
    const todoList = document.querySelector('.todo-list');
    if (todoList) {
        let scrollTimer;

        todoList.addEventListener('scroll', () => {
            todoList.classList.add('scrolling');
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                todoList.classList.remove('scrolling');
            }, 800);
        });

        todoList.addEventListener('mouseenter', () => {
            todoList.classList.add('scrolling');
        });

        todoList.addEventListener('mouseleave', () => {
            todoList.classList.remove('scrolling');
        });
    }

    // Initialize
    render();

    // Helper: Place caret at end
    function placeCaretAtEnd(el) {
        el.focus();
        if (typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
            const range = document.createRange();
            range.selectNodeContents(el);
            range.collapse(false);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (typeof document.body.createTextRange != "undefined") {
            const textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.collapse(false);
            textRange.select();
        }
    }
});
