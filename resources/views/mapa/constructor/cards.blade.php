@if ($mapas->lin != $linha)
    </div>
    <div class="flex">
@endif

<div id="texto{{ $mapas->id }}"
    style="margin-left:20px; margin-top:-100px; width: {{ $width - 20 }}px; position: absolute; transform: translateX({{ $mapas->posix }}px) translateY({{ $mapas->lin * 100 }}px)"
    class="text-center">

    <div class="w-auto rounded overflow-hidden rounded-full border-4 border-purple-500 py-0 px-0">
        <form action="{{ route('mapa.save') }}" id="formsave{{ $mapas->id }}" method="POST">
            @csrf
            <textarea onchange="save({{ $mapas->id }})" class="bg-transparent focus:outline-0 resize-none text-center"
                name="texto" id="{{ $mapas->id }}" cols="{{ $width / 10 - 4 }}" rows="2"
                placeholder="Escreva algo...">{{ $mapas->texto }}</textarea>
            <input type="hidden" value="{{ $mapas->id }}" name="id">
        </form>
        <form action="{{ route('mapa.delete') }}" method="POST" style="margin: 0; position: absolute; top: 15; right: 10; padding: 0;">
            @csrf
            <input type="hidden" name="id" value="{{ $mapas->id }}">
            <input type="hidden" name="child" value="{{ $mapas->child }}">
            <input type="hidden" name="posix" value="{{ $mapas->posix }}">
            <input type="hidden" name="father" value="{{ $mapas->father_id }}">
            <input type="hidden" name="lin" value="{{ $mapas->lin }}">
            <button style="color: red">x</button>
        </form>
    </div>

    <form action="{{ route('mapa.add') }}" method="POST" class="grid place-content-center block">
        @csrf
        <input type="hidden" name="id" value="{{ $mapas->id }}">
        <input type="hidden" name="child" value="{{ $mapas->child }}">
        <input type="hidden" name="posix" value="{{ $mapas->posix }}">
        <input type="hidden" name="lin" value="{{ $mapas->lin }}">
        <button><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="bi bi-arrow-down"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z" />
            </svg></button>
    </form>
</div>
