@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'rounded-lg border border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 shadow-sm'
]) }}>
