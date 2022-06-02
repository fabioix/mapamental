@if ($width = $mapas->child * 200 == 0)
    <?php $width = 200; ?>
@else
    <?php $width = $mapas->child * 200; ?>
@endif

<style>
    .form-ocult {
        visibility: hidden;
    }
    .body-card:hover .form-ocult{
        visibility: visible;
    }
</style>

<div class="body-card" id="texto{{ $mapas->id }}"
    style="margin-left:20px; margin-top:-100px; width: {{ $width - 20 }}px; position: absolute; transform: translateX({{ $mapas->posix }}px) translateY({{ $mapas->lin * 90 }}px)"
    class="text-center"> 
    <div class="flex flex-row">
        <div id="select{{$mapas->id}}" @if ($loop->last)
            data-ultimo="1"
        @endif data-father="{{$mapas->father_id}}" data-posix="{{$mapas->posix}}" data-id="{{$mapas->id}}" data-lin="{{$mapas->lin}}" data-width="{{$width}}" class="w-auto rounded shadow-md overflow-hidden rounded-full border-2 flex flex-row justify-center"
            style="width: 100%;">
            <form action="{{ route('mapa.save') }}" id="formsave{{ $mapas->id }}" method="POST" style="width: 100%"
                class="text-sm m-auto">
                @csrf
                <textarea onchange="save({{ $mapas->id }})" class="bg-transparent text-center focus:outline-0 resize-none text-center"
                    name="texto" id="textarea{{ $mapas->id }}" data-idtexto="{{ $mapas->id }}" style="width: 90%; margin-left:5%;" rows="3"
                    placeholder="Escreva algo...">{{ $mapas->texto }}</textarea>
                <input type="hidden" value="{{ $mapas->id }}" name="id">
            </form>
        </div>
        @if ($mapas->id != 1)
            <form action="{{ route('mapa.delete') }}" id="formdelete{{ $mapas->id }}" method="POST" class="justify-center m-auto mx-0 form-ocult">
                @csrf
                <input type="hidden" name="child" value="{{ $mapas->child }}">
                <input type="hidden" name="posix" value="{{ $mapas->posix }}">
                <input type="hidden" name="father" value="{{ $mapas->father_id }}">
                <input type="hidden" name="lin" value="{{ $mapas->lin }}">
                <button style="color: red">x</button>
            </form>
        @endif
    </div>
    <form action="{{ route('mapa.add') }}" id="formadd{{ $mapas->id }}" method="POST" class="grid place-content-center block form-ocult">
        @csrf
        <input type="hidden" name="id" value="{{ $mapas->id }}">
        <input type="hidden" name="child" value="{{ $mapas->child }}">
        <input type="hidden" name="posix" value="{{ $mapas->posix }}">
        <input type="hidden" name="lin" value="{{ $mapas->lin }}">
        <button>+</button>
    </form>
</div>
