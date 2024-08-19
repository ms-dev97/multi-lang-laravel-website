<div class="date">
    <time datetime="{{ $date }}">
        {{ Carbon\Carbon::parse($date)->isoFormat('Do MMMM YYYY') }}
    </time>
</div>