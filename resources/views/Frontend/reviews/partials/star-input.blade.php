{{-- frontend/reviews/partials/star-input.blade.php --}}
{{-- pass $fieldName and optionally $currentValue for edit --}}

@php
    // old() takes priority so validation errors repopulate correctly
    $selected = (int) old($fieldName, $currentValue ?? 0);
@endphp

<div class="star-rating-wrap" id="wrap_{{ $fieldName }}">
    @for($i = 1; $i <= 5; $i++)
        <input type="radio"
               id="{{ $fieldName }}_{{ $i }}"
               name="{{ $fieldName }}"
               value="{{ $i }}"
               class="d-none"
               {{ $selected === $i ? 'checked' : '' }}
               required>

        <label for="{{ $fieldName }}_{{ $i }}"
               class="star-label"
               data-val="{{ $i }}"
               style="font-size: 2rem; cursor: pointer; color: {{ $selected >= $i ? '#f5a623' : '#ccc' }}; margin-right: 2px;">
            &#9733;
        </label>
    @endfor

    @error($fieldName)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
</div>

<script>
(function () {
    var wrap   = document.getElementById('wrap_{{ $fieldName }}');
    var labels = wrap.querySelectorAll('.star-label');

    function highlight(upTo) {
        labels.forEach(function (l) {
            l.style.color = parseInt(l.dataset.val) <= upTo ? '#f5a623' : '#ccc';
        });
    }

    function getChecked() {
        var checked = wrap.querySelector('input[type=radio]:checked');
        return checked ? parseInt(checked.value) : 0;
    }

    labels.forEach(function (label) {
        // hover — fill stars up to this one
        label.addEventListener('mouseover', function () {
            highlight(parseInt(this.dataset.val));
        });

        // mouseout — reset to selected value
        label.addEventListener('mouseout', function () {
            highlight(getChecked());
        });

        // click — lock the selection
        label.addEventListener('click', function () {
            highlight(parseInt(this.dataset.val));
        });
    });
})();
</script>
