@php
    $record = $getRecord();
    $name = $getName();
    $isDisabled = $isDisabled();
    $isEditable = $isEditable();
    $placeholder = $getPlaceholder();
    $extraAttributes = $getExtraAttributeBag();
    $currentValue = $getState();
    $textSizeClass = $entry->getTextSizeClass();
    $textColorClasses = $entry->getTextColorClasses();
    $textColorStyles = $entry->getTextColorStyles();
    $showBorder = $entry->shouldShowBorder();
    $hasColor = !empty($textColorClasses) || !empty($textColorStyles);
    $defaultTextColor = $hasColor ? '' : 'text-gray-950 dark:text-white';
    $iconHtml = $entry->getIconHtml();
    $iconPosition = $entry->getIconPositionValue();
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div {{ $extraAttributes }} class="w-full min-w-0">
        @if ($showBorder)
            <input type="text" x-data="{ value: @js($currentValue), originalValue: @js($currentValue), isFocused: false, isUpdating: false }" x-model="value" x-on:focus="isFocused = true"
                x-on:blur="
                    if (!isUpdating) {
                        isFocused = false;
                        if (value !== originalValue) {
                            isUpdating = true;
                            $wire.call('updateEditableEntry', @js($name), value, @js($record->getKey()))
                                .then(() => { originalValue = value; isUpdating = false; })
                                .catch(() => { value = originalValue; isUpdating = false; });
                        }
                    }
                "
                x-on:keydown.enter="
                    $event.preventDefault();
                    if (!isUpdating && value !== originalValue) {
                        isUpdating = true;
                        $event.target.blur();
                        $wire.call('updateEditableEntry', @js($name), value, @js($record->getKey()))
                            .then(() => { originalValue = value; isUpdating = false; })
                            .catch(() => { value = originalValue; isUpdating = false; });
                    } else {
                        $event.target.blur();
                    }
                "
                @if ($isDisabled || !$isEditable)
            disabled
        @endif
        @if ($placeholder)
            placeholder="{{ $placeholder }}"
        @endif
        @if ($textColorStyles)
            style="{{ $textColorStyles }}"
        @endif
        :class="isFocused ?
            'block w-full min-w-0 rounded-lg border border-gray-300 bg-transparent pl-4 pr-4 py-3 {{ $textSizeClass }} {{ $textColorClasses }} {{ $defaultTextColor }} transition duration-75 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:border-gray-600 dark:focus:ring-primary-500 dark:focus:border-primary-500' :
            'block w-full min-w-0 rounded-lg border border-gray-200 bg-transparent pl-4 pr-4 py-3 {{ $textSizeClass }} {{ $textColorClasses }} {{ $defaultTextColor }} focus:outline-none dark:border-gray-600'"
        class="outline-none"
        style="min-width: 0; width: 100%;"
        />
    @else
        <div class="flex items-center gap-2 w-full min-w-0">
            @if ($iconHtml && $iconPosition->value === 'before')
                <div class="shrink-0">
                    {!! $iconHtml !!}
                </div>
            @endif
            <input type="text" x-data="{ value: @js($currentValue), originalValue: @js($currentValue), isFocused: false, isUpdating: false }" x-model="value" x-on:focus="isFocused = true"
                x-on:blur="
                        if (!isUpdating) {
                            isFocused = false;
                            if (value !== originalValue) {
                                isUpdating = true;
                                $wire.call('updateEditableEntry', @js($name), value, @js($record->getKey()))
                                    .then(() => { originalValue = value; isUpdating = false; })
                                    .catch(() => { value = originalValue; isUpdating = false; });
                            }
                        }
                    "
                x-on:keydown.enter="
                        $event.preventDefault();
                        if (!isUpdating && value !== originalValue) {
                            isUpdating = true;
                            $event.target.blur();
                            $wire.call('updateEditableEntry', @js($name), value, @js($record->getKey()))
                                .then(() => { originalValue = value; isUpdating = false; })
                                .catch(() => { value = originalValue; isUpdating = false; });
                        } else {
                            $event.target.blur();
                        }
                    "
                @if ($isDisabled || !$isEditable)
            disabled
            @endif
            @if ($placeholder)
                placeholder="{{ $placeholder }}"
            @endif
            @if ($textColorStyles)
                style="{{ $textColorStyles }}"
            @endif
            class="block flex-1 min-w-0 border-none bg-transparent p-0 {{ $textSizeClass }} {{ $textColorClasses }} {{ $defaultTextColor }} outline-none"
            style="min-width: 0; width: 100%;"
            />
            @if ($iconHtml && $iconPosition->value === 'after')
                <div class="shrink-0">
                    {!! $iconHtml !!}
                </div>
            @endif
        </div>
        @endif
    </div>
</x-dynamic-component>
