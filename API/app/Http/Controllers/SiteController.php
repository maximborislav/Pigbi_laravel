<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\favorite;
/*use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Stream;
use Prewk\XmlStringStreamer\Parser;*/

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $estates = DB::table('estates')->paginate(30);
        return view('homepage',['estates' => $estates]);
    }

    public function saved() {
        return view('saved');
    }

    public function approved() {
        return view('approved');
    }

    public function application() {
        return view('application');
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

    public function autocomplete(Request $request)
    {
        $term=$request->term;

        $data = \App\Estate::where('Address','LIKE','%'.$term.'%')
        ->take(10)
        ->select('Address')
        ->get();
        $result=array();
        // print_r($data);
        foreach ($data as $key => $value){
            $result[] = 
            ['value' =>
                json_decode(json_decode($value)->Address)->{"commons:FullStreetAddress"}
                .json_decode(json_decode($value)->Address)->{"commons:City"}
                .json_decode(json_decode($value)->Address)->{"commons:StateOrProvince"}
                .json_decode(json_decode($value)->Address)->{"commons:Country"}
            ];
        }
        return response()->json($result);
    }

    public function search(Request $request)
    {
        $term = $request->get("Address");
        $data = \App\Estate::where('Address','LIKE','%'.$term.'%')->paginate(30);
        return view('homepage',['estates' => $data]);
    }
    
    public function increasesort(Request $request)
    {
        $data = \App\Estate::orderBy('ListPrice')->paginate(30);
        return view('homepage',['estates' => $data]);
    }

    public function decreasesort(Request $request)
    {
        $data = \App\Estate::orderBy('ListPrice', 'desc')->paginate(30);
        return view('homepage',['estates' => $data]);
    }

    public function newestsort(Request $request)
    {
        $data = \App\Estate::orderBy('ListingDate', 'desc')->paginate(30);
        return view('homepage',['estates' => $data]);
    }

    public function detail(Request $request)
    {
        $id = $request->get("ID");
        $value = \App\Estate::where('id', $id)->get()->first();
        /*$estate['address'] = [
            ['address1' =>  
                            json_decode(json_decode($value)->Address)->{"commons:FullStreetAddress"}.
                            json_decode(json_decode($value)->Address)->{"commons:City"}],
            ['address2' =>  
                            json_decode(json_decode($value)->Address)->{"commons:StateOrProvince"}.
                            json_decode(json_decode($value)->Address)->{"commons:Country"}],
            ['description' => json_decode($value)->ListingDescription]
        ];*/
        return response()->json($value);
    }

    //filters

    public function MinPricefilterfunc(Request $request){
        $price = $request->get("price");
        $data = \App\Estate::whereBetween('ListPrice', [$price, 2000000])->paginate(30);
        return view('homepage', ['estates' => $data]);
    }
    
    public function MaxPricefilterfunc(Request $request){
        $price = $request->get("price");
        $data = \App\Estate::whereBetween('ListPrice', [10000, $price])->paginate(30);
        return view('homepage', ['estates' => $data]);
    }
    
    public function Favoritefunc(Request $request){
        $data1 = $request->get("userid");
        $data2 = $request->get("houseid");
        $DBvar = new favorite();
        $DBvar->userid = $data1;
        $DBvar->houseid = $data2;
        $DBvar->save();
    }
    
    public function Favoriteajaxfunc(){
        $userid = session("userid");
        $db = new favorite();
        $result = $db->select("houseid")->where("userid", $userid)->get();
        return json_encode($result);
    }
    
//    public function ten_thirty() {
//        $data = \App\Estate::whereBetween('ListPrice', [10000, 30000])->paginate(30);
//        // print_r($data);
//        return view('homepage', ['estates' => $data]);
//    }
//
//    public function thirty_firty() {
//        $data = \App\Estate::whereBetween('ListPrice', [30000, 40000])->paginate(30);
//        // print_r($data);
//        return view('homepage', ['estates' => $data]);
//    }
//
//    public function forty_sixty() {
//        $data = \App\Estate::whereBetween('ListPrice', [40000, 60000])->paginate(30);
//        // print_r($data);
//        return view('homepage', ['estates' => $data]);
//    }
//
//    public function sixty_hundred() {
//        $data = \App\Estate::whereBetween('ListPrice', [60000, 100000])->paginate(30);
//        // print_r($data);
//        return view('homepage', ['estates' => $data]);
//    }
//
//    public function hundred_twentyhun() {
//        $data = \App\Estate::whereBetween('ListPrice', [100000, 120000])->paginate(30);
//        // print_r($data);
//        return view('homepage', ['estates' => $data]);
//    }

    // End of Filter by Price
    
    // Filter of Bed
    
    public function Oneplusfunc() {
        $data = \App\Estate::whereBetween('Bedrooms', [1, 6])->paginate(30);
        return view('homepage', ['estates' => $data]);
    }
    
    public function Twoplusfunc() {
        $data = \App\Estate::whereBetween('Bedrooms', [2, 6])->paginate(30);
        return view('homepage', ['estates' => $data]);
    }
    
    public function Threeplufunc() {
        $data = \App\Estate::whereBetween('Bedrooms', [3, 6])->paginate(30);
        return view('homepage', ['estates' => $data]);
    }
    
    public function Fourplusfunc(){
        $data = \App\Estate::whereBetween('Bedrooms', [4, 6])->paginate(30);
        return view('homepage', ['estates' => $data]);
    }
    
    // End of Filter of Bed
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
