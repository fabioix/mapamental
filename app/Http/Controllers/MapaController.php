<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdate;
use App\Models\Mapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaController extends Controller
{
    public function home()
    {
        $mapa = DB::select('select * from mapaselects order by id');
        return view('mapa.home', compact('mapa'));
    }
    public function new()
    {
        $data = DB::table('mapaselects')->count() + 1;
        DB::insert('insert into mapaselects (id) values (?)', [$data]);
        return redirect()->route('mapa.home');
    }
    public function mapa($idmapa)
    {
        $margin = DB::table('mapas')->orderByDesc('posix')->first();
        $mapa = DB::select('select * from mapas where idmapa = ? order by id', [$idmapa]);
        return view('mapa.index', compact('mapa', 'idmapa', 'margin'));
    }
    public function novo(Request $request)
    {
        $idmapa = $request->idmapa;
        DB::table('mapas')->where('idmapa', '=', $idmapa)->delete();
        DB::insert('insert into mapas (idmapa, child, posix, lin, texto, father_id) values (?, 0, 0, 1, "", 0)', [$idmapa]);
        return redirect()->route('mapa.index', $idmapa);
    }
    public function save(StoreUpdate $request)
    {
        $idmapa = $request->idmapa;
        DB::update('update mapas set texto = ? where id = ?', [$request->texto, $request->id]);
        return redirect()->route('mapa.index', $idmapa);
    }
    public function add(Request $request)
    {
        $idmapa = $request->idmapa;

        $position = ($request->child * 200) + $request->posix;
        if ($request->child > 0) {
            DB::table('mapas')->where([
                ['posix', '>=', intval($position)],
                ['idmapa', $idmapa],
            ])->increment('posix', 200);
        }
        DB::insert('insert into mapas (idmapa, child, posix, lin, texto, father_id) values (?, 0, ?, ?, "", ?)', [$idmapa, $position, $request->lin + 1, $request->id]);

        $father = $request->id;
        $child = 1 + $request->child;
        if ($child > 1) {
            while ($father > 0) {
                DB::update('update mapas set child = ? where id = ?', [$child, $father]);
                $data = DB::table('mapas')->where([
                    ['id', $father],
                    ['idmapa', $idmapa],
                ])->first();
                $father = $data->father_id;
                if ($father > 0) {
                    $data = DB::table('mapas')->where([
                        ['id', $father],
                        ['idmapa', $idmapa],
                    ])->first();
                    $child = $data->child + 1;
                }
            }
        } else {
            DB::update('update mapas set child = ? where id = ?', [$child, $father]);
        }
        return redirect()->route('mapa.index', $idmapa);
    }
    public function remove(Request $request)
    {
        $idmapa = $request->idmapa;
        DB::table('mapas')->where('idmapa', '=', $idmapa)->delete();
        DB::table('mapaselects')->where('id', '=', $idmapa)->delete();
        return redirect()->route('mapa.home');
    }
    public function delete(Request $request)
    {
        $idmapa = $request->idmapa;
        $child = $request->child;
        $lin = $request->lin;
        $posix = $request->posix;
        $father = $request->father;

        if ($child > 0) {
            DB::table('mapas')->where([
                ['posix', '>=', intval($posix)],
                ['posix', '<=', intval($posix) + ((intval($child) - 1) * 200)],
                ['lin', '>=', intval($lin)],
                ['idmapa', $idmapa],
            ])->delete();

            DB::table('mapas')->where([
                ['posix', '>', intval($posix)],
                ['idmapa', $idmapa],
                ])->decrement('posix', ($child * 200));

            while ($father > 0) {
                DB::table('mapas')->where('id', '=', $father)->decrement('child', $child);
                $data = DB::table('mapas')->where('id', $father)->first();
                $father = $data->father_id;
            }
        } else {
            DB::table('mapas')->where([
                ['posix', '=', intval($posix)],
                ['lin', '=', intval($lin)],
                ['idmapa', $idmapa],
            ])->delete();
            $data_child = DB::table('mapas')->where('id', $father)->first();
            $child_father = $data_child->child;
            if ($child_father > 1) {
                DB::table('mapas')->where([
                    ['posix', '>', intval($posix)],
                    ['idmapa', $idmapa],
                    ])->decrement('posix', 200);
                while ($father > 0) {
                    DB::table('mapas')->where('id', '=', $father)->decrement('child', 1);
                    $data = DB::table('mapas')->where('id', $father)->first();
                    $father = $data->father_id;
                }
            } else {
                DB::table('mapas')->where('id', '=', $father)->decrement('child', 1);
            }
        }


        return redirect()->route('mapa.index', $idmapa);
    }
}
