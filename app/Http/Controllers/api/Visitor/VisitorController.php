<?php

namespace App\Http\Controllers\api\Visitor;

use App\Http\Controllers\Controller;

use App\Models\Visitor;
use App\Models\Uniouninfo;
use Illuminate\Http\Request;

class VisitorController extends Controller
{

public function visitorCount(Request $request)
{
    $union = $request->union;
    if($union){
        $visitorCounting = cache()->remember('visitor-'.$union, 60*60*24, function () use($union) {
            return Visitor::where(['union'=>$union])->count();
        });
return $visitorCounting;


    }
    $visitorCounting = cache()->remember('visitor-uniontax', 60*60*24, function () {
        return Visitor::where(['union'=>''])->count();
    });
return $visitorCounting;
    return Visitor::where(['union'=>''])->count();


    return Visitor::count();
}
public function visitorcreate(Request $request)
{
    $ip =  $_SERVER['REMOTE_ADDR'];
    $url = url()->current();
$domain =  explode('//',$url);
 $subdomain =  explode('.', $domain[1]);
 $subdomainCount =  count($subdomain);
 if($subdomainCount>1){


    $visitorWhere = [
        'ip'=>$ip,
        'date'=>date('d-m-Y'),
        'union'=>$subdomain[0],
    ];
 $Visitor = Visitor::where($visitorWhere)->count();
  if($Visitor<1){
      $datainsert = [
          'ip'=>$ip,
          'union'=>$subdomain[0],
          'date'=>date('d-m-Y'),
          'month'=>date('F'),
          'year'=>date('Y'),
      ];
      Visitor::create($datainsert);
    }
    }else{

        $visitorWhere = [
            'ip'=>$ip,
            'union'=>'all',
            'date'=>date('d-m-Y'),
        ];
       $Visitor = Visitor::where($visitorWhere)->count();
      if($Visitor<1){
        $datainsert = [
            'ip'=>$ip,
            'union'=>'all',
            'date'=>date('d-m-Y'),
            'month'=>date('F'),
            'year'=>date('Y'),
        ];
        Visitor::create($datainsert);
      }
    }



  }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


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
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {
        //
    }
}
