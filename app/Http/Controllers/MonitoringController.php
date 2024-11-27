<?php

namespace App\Http\Controllers;

use App\Models\MasterJenisPo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function kirimsetor()
    {
        //
        $data = [];
        $data['title']='Monitoring Kirim Setor';
        $data['tanggal1'] = request()->tanggal1 ?? date('Y-m-d',strtotime("monday this week"));
        $data['tanggal2'] = request()->tanggal2 ?? date('Y-m-d',strtotime("saturday this week"));
        $data['rekap']=[];
        $jenispo = MasterJenisPo::select(
            'idjenis',
            DB::raw("CASE 
                WHEN idjenis = 1 THEN 'Kaos' 
                WHEN idjenis = 2 THEN 'Kemeja' 
                WHEN idjenis = 3 THEN 'Celana' 
                ELSE 'lainnya' END as jenis")
        )
        ->orderBy('idjenis')
        ->groupBy('idjenis')
        ->get();
        foreach($jenispo as $j){
            $data['rekap'][] = (object) array(
                'idjenis'   => $j->idjenis,
                'nama'      => $j->jenis,
                'jumlah'    => $this->countJumlah($j->idjenis,$data['tanggal1'],$data['tanggal2']),
                'dz'        => $this->count($j->idjenis,$data['tanggal1'],$data['tanggal2']) ? $this->count($j->idjenis,$data['tanggal1'],$data['tanggal2'])/12 : 0,
                'pcs'       => $this->count($j->idjenis,$data['tanggal1'],$data['tanggal2']),
                // 'detail'    => $this->detail($j->idjenis,$data['tanggal1'],$data['tanggal2']),
            );
        }
        $data['kaos']=
        $data['kaos']=$this->detail(1,$data['tanggal1'],$data['tanggal2']);
        $data['kemeja']=
        $data['kemeja']=$this->detail(2,$data['tanggal1'],$data['tanggal2']);
        $data['celana']=
        $data['celana']=$this->detail(3,$data['tanggal1'],$data['tanggal2']);
        // dd($data['rekap']);
        return view('page.monitoring.kirimsetor',$data);
    }

    function countJumlah($idjenis,$tanggal1,$tanggal2){
        $hasil = 0; 
        $query = DB::table('kelolapo_kirim_setor as kbp')
                    ->selectRaw('count(DISTINCT kbp.kode_po) as total, mjp.nama_jenis_po, mjp.perkalian')
                    ->join('produksi_po as p', 'p.id_produksi_po', '=', 'kbp.idpo')
                    ->leftJoin('master_jenis_po as mjp', 'mjp.nama_jenis_po', '=', 'p.nama_po')
                    ->where('mjp.idjenis', $idjenis)
                    ->where('kbp.kategori_cmt', 'JAHIT')
                    ->where('kbp.progress', 'KIRIM')
                    ->where('kbp.hapus', 0)
                    ->where('mjp.tampil', 1)
                    ->whereNotIn('kbp.id_master_cmt', [63]);
        if (!empty($tanggal1) && !empty($tanggal2)) {
            $query->whereBetween(DB::raw('DATE(kbp.create_date)'), [$tanggal1, $tanggal2]);
        }
        $query->groupBy('mjp.nama_jenis_po');
        $query->groupBy('mjp.perkalian');
        $row = $query->first();
        if ($row && $row->total > 0) {
            $hasil = $row->total;
            if ($row->nama_jenis_po == "SKF" || strtoupper($row->nama_jenis_po) == "SIMULASI SKF") {
                $hasil = round($row->total * $row->perkalian);
            }
        }

        return $hasil;
    }

    function count($idjenis,$tanggal1,$tanggal2){
        $hasil = 0; 
        $query = DB::table('kelolapo_kirim_setor as kbp')
                    // ->selectRaw('kbp.qty_tot_pcs, p.id_produksi_po, p.kode_po')
                    ->selectRaw('COALESCE(SUM(kbp.qty_tot_pcs),0) as total ')
                    ->join('produksi_po as p', 'p.id_produksi_po', '=', 'kbp.idpo')
                    ->leftJoin('master_jenis_po as mjp', 'mjp.nama_jenis_po', '=', 'p.nama_po')
                    ->where('mjp.idjenis', $idjenis)
                    ->where('kbp.kategori_cmt', 'JAHIT')
                    ->where('kbp.progress', 'KIRIM')
                    ->where('kbp.hapus', 0)
                    ->where('mjp.tampil', 1)
                    ->whereNotIn('kbp.id_master_cmt', [63]);
        if (!empty($tanggal1) && !empty($tanggal2)) {
            $query->whereBetween(DB::raw('DATE(kbp.create_date)'), [$tanggal1, $tanggal2]);
        }
        $hasil = $query->first();

        return isset($hasil->total) ? $hasil->total : 0;
    }

    function detail($idjenis,$tanggal1,$tanggal2){
        $hasil = []; 
        $query = DB::table('kelolapo_kirim_setor as kbp')
                    ->selectRaw('p.id_produksi_po, p.kode_po,kbp.qty_tot_pcs as pcs')
                    ->join('produksi_po as p', 'p.id_produksi_po', '=', 'kbp.idpo')
                    ->leftJoin('master_jenis_po as mjp', 'mjp.nama_jenis_po', '=', 'p.nama_po')
                    ->where('mjp.idjenis', $idjenis)
                    ->where('kbp.kategori_cmt', 'JAHIT')
                    ->where('kbp.progress', 'KIRIM')
                    ->where('kbp.hapus', 0)
                    ->where('mjp.tampil', 1)
                    ->whereNotIn('kbp.id_master_cmt', [63]);
        if (!empty($tanggal1) && !empty($tanggal2)) {
            $query->whereBetween(DB::raw('DATE(kbp.create_date)'), [$tanggal1, $tanggal2]);
        }
        $hasil = $query->get();

        return $hasil;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
