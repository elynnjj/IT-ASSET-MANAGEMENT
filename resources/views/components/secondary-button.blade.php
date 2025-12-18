<button {{ $attributes->merge(['type' => 'button', 'class' => 'interactive-button interactive-button-secondary']) }}>
    <span class="button-content">
    {{ $slot }}
    </span>
</button>
