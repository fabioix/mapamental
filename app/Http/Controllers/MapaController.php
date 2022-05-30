<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUpdate;
use App\Models\Mapa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapaController extends Controller
{
    public function mapa()
    {
        $mapa = DB::select('select * from mapas order by lin');
        $margin = DB::table('mapas')->orderByDesc('posix')->first();
        return view('mapa.index', compact('mapa', 'margin'));
    }
    public function novo()
    {
        DB::table('mapas')->truncate();
        DB::insert('insert into mapas (child, posix, lin, texto, father_id) values (0, 0, 1, "", 0)');
        return redirect()->route('mapa.index');
    }
    public function save(StoreUpdate $request)
    {
        DB::update('update mapas set texto = ? where id = ?', [$request->texto, $request->id]);
        return redirect()->route('mapa.index');
    }
    public function add(Request $request)
    {
        $position = ($request->child * 200) + $request->posix;
        if ($request->child > 0) {
            DB::table('mapas')->where('posix', '>=', $position)->increment('posix', 200);
        }
        DB::insert('insert into mapas (child, posix, lin, texto, father_id) values (0, ?, ?, "", ?)', [$position, $request->lin + 1, $request->id]);

        $father = $request->id;
        $child = 1 + $request->child;
        if ($child > 1) {
            while ($father > 0) {
                DB::update('update mapas set child = ? where id = ?', [$child, $father]);
                $data = DB::table('mapas')->where('id', $father)->first();
                $father = $data->father_id;
                if ($father > 0) {
                    $data = DB::table('mapas')->where('id', $father)->first();
                    $child = $data->child + 1;
                }
            }
        } else {
            DB::update('update mapas set child = ? where id = ?', [$child, $father]);
        }
        return redirect()->route('mapa.index');
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        $child = $request->child;
        $lin = $request->lin;
        $posix = $request->posix;
        $father = $request->father;

        DB::table('mapas')->where([
            ['posix', '>=', $posix],
            ['posix', '<=', $posix + (($child - 1) * 200)],
            ['lin', '>=', $lin],
        ])->delete();
        DB::table('mapas')->where('posix', '>', $posix + (($child - 1) * 200))->decrement('posix', $child * 200);

        while ($father > 0) {
            DB::table('mapas')->where('id', '=', $father)->decrement('child', $child);
            $data = DB::table('mapas')->where('id', $father)->first();
            $father = $data->father_id;
        }
        return redirect()->route('mapa.index');
    }
}
