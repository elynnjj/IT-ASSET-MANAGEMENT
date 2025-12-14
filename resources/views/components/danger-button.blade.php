<button {{ $attributes->merge(['type' => 'submit', 'class' => 'interactive-button interactive-button-danger']) }}>
    <span class="button-content">
        <span class="button-text">{{ $slot }}</span>
        <span class="button-spinner"></span>
    </span>
</button>
