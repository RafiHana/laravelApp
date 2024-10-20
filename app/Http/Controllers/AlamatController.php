<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\City;
use App\Models\Province;

use Illuminate\Http\Request;

class AlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alamats = Alamat::paginate(10);
        return view('alamat.index', ['alamats' => $alamats]);
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

    public function sync_province()
    {
        $err_message = '';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => ['key: ' . env('RAJAONGKIR_KEY')],
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.rajaongkir.com/starter/province',
            CURLOPT_POST => false,
        ]);
        $resp = curl_exec($curl);
        if (!$resp) {
            $err_message = 'Error: "' . curl_error($curl) . '" - Code:' . curl_errno($curl);
        }
        curl_close($curl);

        if (empty($err_message)) {
            $json = json_decode($resp, true);
            Province::truncate(); // Clear all existing provinces
            foreach ($json['rajaongkir']['results'] as $result) {
                Province::create($result);
            }
            return redirect('/alamat')->with('status_message', ['type' => 'info', 'text' => 'Province synced!']);
        } else {
            return redirect('/alamat')->with('status_message', ['type' => 'danger', 'text' => $err_message]);
        }
    }
    public function sync_city()
    {
        $err_message = '';
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_HTTPHEADER => ['key: ' . env('RAJAONGKIR_KEY')],
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.rajaongkir.com/starter/city',
            CURLOPT_POST => false,
        ]);
        $resp = curl_exec($curl);
        if (!$resp) {
            $err_message = 'Error: "' . curl_error($curl) . '" - Code:' . curl_errno($curl);
        }
        curl_close($curl);

        if (empty($err_message)) {
            $json = json_decode($resp, true);
            City::truncate(); // Clear all existing cities
            foreach ($json['rajaongkir']['results'] as $result) {
                City::create($result);
            }
            return redirect('/alamat')->with('status_message', ['type' => 'info', 'text' => 'City synced!']);
        } else {
            return redirect('/alamat')->with('status_message', ['type' => 'danger', 'text' => $err_message]);
        }
    }
}
