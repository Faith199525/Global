<li class="nav-item">
    <a href="{{ route('users.index') }}"
       class="nav-link {{ Request::is('users*') ? 'active' : '' }}">
        <p>Users</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('categories.index') }}"
       class="nav-link {{ Request::is('category*') ? 'active' : '' }}">
        <p>Category</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ route('threads.index') }}"
       class="nav-link {{ Request::is('threads*') ? 'active' : '' }}">
        <p>Threads</p>
    </a>
</li>


<li class="nav-item">
    <a href="{{ route('replies.index') }}"
       class="nav-link {{ Request::is('replies*') ? 'active' : '' }}">
        <p>Thread Replies</p>
    </a>
</li>





