<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel Cloudinary Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/clipboard@2.0.11/dist/clipboard.min.js"></script>
    <script src="https://unpkg.com/preline@2.1.0/index.js"></script>
</head>

<body class="bg-slate-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-3xl mx-auto">
        <div class="py-8 text-center">
            <h1 class="text-4xl font-bold tracking-tight">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
                    Laravel Upload file to Cloudinary
                </span>
            </h1>
        </div>
        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-md px-8 pt-6 pb-8 mb-4 border-t-4 border-indigo-600/70">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2" for="file-input">
                    Select File
                </label>
                <input type="file" name="file" id="file-input" class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none
                file:bg-gray-100 file:border-0
                file:me-4
                file:py-3 file:px-4
                file:cursor-pointer">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="py-2 px-3 bg-indigo-500 text-white text-sm font-semibold rounded-md shadow focus:outline-none" tabindex="-1">
                    Upload file
                </button>
            </div>
        </form>
        <div class="bg-white rounded-md shadow-md px-4">
            <h2 class="pt-6 pb-2 text-2xl font-bold border-b">Uploaded Files</h2>
            @foreach ($uploadedFiles as $uploadedFile)
            <div class="flex gap-x-3 py-4 border-b">
                <div class="grow">
                    <h3 class="flex gap-x-1.5 font-semibold text-gray-800 pb-2 items-center">
                        <a href="{{ $uploadedFile->url }}" class="hover:underline" target="_blank">{{ $uploadedFile->file_name }}</a> <span class="text-sm text-gray-500 font-normal">{{ $uploadedFile->created_at->diffForHumans() }}</span>
                    </h3>
                    <input type="hidden" id="hs-clipboard-tooltip" value="{{ $uploadedFile->url }}">
                    <button type="button" class="js-clipboard-example [--trigger:focus] hs-tooltip relative py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-mono rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" data-clipboard-target="#hs-clipboard-tooltip" data-clipboard-action="copy" data-clipboard-success-text="Copied">
                        <span class="text-xs">{{ $uploadedFile->url }}</span>
                        <span class="border-s ps-3.5">
                            <svg class="js-clipboard-default size-4 group-hover:rotate-6 transition" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"></rect>
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            </svg>

                            <svg class="js-clipboard-success hidden size-4 text-blue-600 rotate-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        </span>
                        <span class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity hidden invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-lg shadow-sm" role="tooltip">
                            Copied
                        </span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <script>
        // INITIALIZATION OF CLIPBOARD
        // =======================================================
        (function() {
            window.addEventListener('load', () => {
                const $clipboards = document.querySelectorAll('.js-clipboard-example');
                $clipboards.forEach((el) => {
                    const isToggleTooltip = HSStaticMethods.getClassProperty(el, '--is-toggle-tooltip') === 'false' ? false : true;
                    const clipboard = new ClipboardJS(el, {
                        text: (trigger) => {
                            const clipboardText = trigger.dataset.clipboardText;

                            if (clipboardText) return clipboardText;

                            const clipboardTarget = trigger.dataset.clipboardTarget;
                            const $element = document.querySelector(clipboardTarget);

                            if (
                                $element.tagName === 'SELECT' ||
                                $element.tagName === 'INPUT' ||
                                $element.tagName === 'TEXTAREA'
                            ) return $element.value
                            else return $element.textContent;
                        }
                    });
                    clipboard.on('success', () => {
                        const $default = el.querySelector('.js-clipboard-default');
                        const $success = el.querySelector('.js-clipboard-success');
                        const $successText = el.querySelector('.js-clipboard-success-text');
                        const successText = el.dataset.clipboardSuccessText || '';
                        const tooltip = el.closest('.hs-tooltip');
                        const $tooltip = HSTooltip.getInstance(tooltip, true);
                        let oldSuccessText;

                        if ($successText) {
                            oldSuccessText = $successText.textContent
                            $successText.textContent = successText
                        }
                        if ($default && $success) {
                            $default.style.display = 'none'
                            $success.style.display = 'block'
                        }
                        if (tooltip && isToggleTooltip) HSTooltip.show(tooltip);
                        if (tooltip && !isToggleTooltip) $tooltip.element.popperInstance.update();

                        setTimeout(function() {
                            if ($successText && oldSuccessText) $successText.textContent = oldSuccessText;
                            if (tooltip && isToggleTooltip) HSTooltip.hide(tooltip);
                            if (tooltip && !isToggleTooltip) $tooltip.element.popperInstance.update();
                            if ($default && $success) {
                                $success.style.display = '';
                                $default.style.display = '';
                            }
                        }, 800);
                    });
                });
            })
        })()
    </script>

</body>

</html>