@if (session('success'))
    <div id="flash-success"
         class="mb-4 rounded-md bg-green-200 p-3 text-green-800 border border-green-200">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="flash-error"
         class="mb-4 rounded-md bg-red-200 p-3 text-red-800 border border-red-200">
        {{ session('error') }}
    </div>
@endif
