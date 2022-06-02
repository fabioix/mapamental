@extends('layouts.app')
@section('title', 'Mapa Mental')

<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

@section('content')
    <div class="sair flex shadow-lg z-50"
        style="position: fixed; top: 0px; left: 0px; background-color: rgb(61, 61, 61); width: 371; padding-top:16px; padding-left: 20px">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="bg-red-500 hover:bg-red-700 text-white focus:outline-0 font-bold py-2 px-4 rounded shadow-lg"
                type="submit">Sair</button>
        </form>
        <form action="{{ route('mapa.novo') }}" method="POST">
            @csrf
            <button
                class="bg-purple-500 hover:bg-purple-700 focus:outline-0 text-white font-bold py-2 px-4 rounded shadow-lg"
                style="margin-left:82px" type="submit">Limpar Mapa Mental</button>
        </form>
    </div>
    <br><br><br><br>
    <?php
    $linha = 1;
    ?>
    <div class="grid place-content-center h-auto">
        <div id="corp">
            @foreach ($mapa as $mapas)
                @include('mapa.constructor.cards')
                <?php
                $linha = $mapas->lin;
                ?>
            @endforeach
        </div>
    </div>
@endsection

<script>
    function save(id) {
        var name = 'formsave' + id;
        document.getElementById(name).submit();
    }

    function load() {
        var margin = -(document.getElementById("texto1").offsetWidth + 20) / 2 + 100;
        document.getElementById("corp").style = 'margin-left:' + margin;
        document.querySelector("[data-ultimo='1']").classList.add('border-purple-500');
        window.scrollTo(4260, 0);
    }


    $(document).on("click", function() {
        if (document.activeElement == '[object HTMLTextAreaElement]') {
            document.querySelector('.border-purple-500').classList.remove(
                'border-purple-500');
            document.querySelector("[data-id='" + document.activeElement.getAttribute('data-idtexto') + "']")
                .classList.add('border-purple-500');
        }
    });

    $(document).on('keyup', function(e) {

        let right = 39;
        let left = 37;
        let up = 38;
        let down = 40;
        let tab = 9;
        let deletar = 46;
        let enter = 13;

        var posixMax = parseInt(document.querySelector('#select1').getAttribute('data-width')) - 200;

        // if (e.keyCode === right || e.keyCode === left || e.keyCode === up || e.keyCode === down || e.keyCode ===
        //     tab || e.keyCode === deletar) {

        switch (e.keyCode) {
            case right:
                var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                var name = 'textarea' + id;
                document.getElementById(name).blur();

                var posix = parseInt(document.querySelector('.border-purple-500').getAttribute(
                    'data-posix'));
                var width = parseInt(document.querySelector('.border-purple-500').getAttribute(
                    'data-width'));
                var lin = parseInt(document.querySelector('.border-purple-500').getAttribute('data-lin'));
                var selector = posix + width;
                if (selector <= posixMax) {
                    for (let i = 0; i <= document.querySelectorAll("[data-posix='" + selector + "']")
                        .length; i++) {
                        if (parseInt(document.querySelectorAll("[data-posix='" + selector + "']")[i]
                                .getAttribute('data-lin')) ==
                            lin) {
                            document.querySelector('.border-purple-500').classList.remove(
                                'border-purple-500');
                            document.querySelectorAll("[data-posix='" + selector + "']")[i].classList.add(
                                'border-purple-500');
                        }
                    }
                }
                break;

            case left:
                var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                var name = 'textarea' + id;
                document.getElementById(name).blur();

                var posix = parseInt(document.querySelector('.border-purple-500').getAttribute(
                    'data-posix'));
                var lin = parseInt(document.querySelector('.border-purple-500').getAttribute('data-lin'));
                var selector = 0;
                for (var j = posix - 200; j >= 0; j = j - 200) {
                    for (var i = 0; i < document.querySelectorAll("[data-lin='" + lin + "']").length; i++) {
                        if (document.querySelectorAll("[data-lin='" + lin + "']")[i].getAttribute(
                                'data-posix') == j) {
                            if (selector <= j) {
                                selector = j;
                            } else {
                                break;
                            }
                            if (selector >= 0) {
                                for (let i = 0; i <= document.querySelectorAll("[data-posix='" + selector +
                                        "']").length; i++) {
                                    if (parseInt(document.querySelectorAll("[data-posix='" + selector +
                                            "']")[i].getAttribute('data-lin')) == lin) {
                                        document.querySelector('.border-purple-500').classList.remove(
                                            'border-purple-500');
                                        document.querySelectorAll("[data-posix='" + selector + "']")[i]
                                            .classList.add(
                                                'border-purple-500');
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
                break;

            case up:
                var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                var name = 'textarea' + id;
                document.getElementById(name).blur();

                var father = parseInt(document.querySelector('.border-purple-500').getAttribute(
                    'data-father'));
                if (father > 0) {
                    document.querySelector('.border-purple-500').classList.remove('border-purple-500');
                    document.querySelector("[data-id='" + father + "']").classList.add(
                        'border-purple-500');
                }

                break;

            case down:
                var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                var name = 'textarea' + id;
                document.getElementById(name).blur();

                var id = parseInt(document.querySelector('.border-purple-500').getAttribute('data-id'));
                var posix = parseInt(document.querySelector('.border-purple-500').getAttribute(
                    'data-posix'));
                if (id > 0) {
                    for (let i = 0; i <= document.querySelectorAll("[data-father='" + id +
                            "']").length; i++) {
                        if (parseInt(document.querySelectorAll("[data-father='" + id +
                                "']")[i].getAttribute('data-posix')) == posix) {
                            document.querySelector('.border-purple-500').classList.remove(
                                'border-purple-500');
                            document.querySelectorAll("[data-father='" + id + "']")[i]
                                .classList.add(
                                    'border-purple-500');
                            break;
                        }
                    }

                }
                break;
            case tab:
                var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                var name = 'textarea' + id;
                document.getElementById(name).blur();
                
                var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                var name = 'formadd' + id;
                document.getElementById(name).submit();

                break;

            case deletar:
                if (document.activeElement != '[object HTMLTextAreaElement]') {
                    var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                    var name = 'formdelete' + id;
                    document.getElementById(name).submit();
                }
                break;

            case enter:
                if (document.activeElement == '[object HTMLTextAreaElement]') {
                    var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                    var name = 'textarea' + id;
                    document.getElementById(name).blur();
                }
                break;

            default:
                if (document.activeElement != '[object HTMLTextAreaElement]') {
                    var id = document.querySelector('.border-purple-500').getAttribute('data-id');
                    var name = 'textarea' + id;
                    document.getElementById(name).focus();
                }
                break;
        }
        // }
    });
</script>
