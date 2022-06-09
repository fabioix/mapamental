@extends('layouts.app')
@section('title', 'Mapa Mental')

@section('content')
    <div style="position:fixed; left:50%">
        <div style="width: 200px" class="justify-center	items-center">
            <form action="{{ route('mapa.new') }}" method="POST">
                @csrf
                <button
                    class="bg-purple-500 hover:bg-purple-700 focus:outline-0 text-white font-bold py-2 px-4 rounded shadow-lg">Novo
                    Mapa</button>
            </form>
        </div>
    </div>
    <div class="flex-inline w-50" style="position:fixed; left:45%; top: 100px; width:20%; height:100%">
        @foreach ($mapa as $mapas)
            <form action="{{ route('mapa.remove') }}" method="POST">
                @csrf
                <input type="hidden" name="idmapa" value="{{ $mapas->id }}">
                <button style="color: red">x</button>
            </form>
            <a href="{{ route('mapa.index', $mapas->id) }}">
                <div class="max-w-sm rounded overflow-hidden shadow-lg">
                    <div class="px-6 py-4">
                        <div class="font-bold text-xl mb-2">Mapa {{ $mapas->id }}</div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
