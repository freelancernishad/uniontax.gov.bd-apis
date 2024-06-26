<?php

namespace App\Http\Controllers\API\Geo;

use App\Http\Controllers\Controller;
use lemonpatwari\bangladeshgeocode\Models\Division;
use lemonpatwari\bangladeshgeocode\Models\District;
use lemonpatwari\bangladeshgeocode\Models\Thana;
use lemonpatwari\bangladeshgeocode\Models\Union;
use Illuminate\Http\Request;

class CountryApiController extends Controller
{

    public function getdivisions(Request $r)
    {
        $id =  $r->id;
        if($id){

          return  $getdivisions = cache()->remember('getdivisions-'.$id, 60*60*24, function () use($id) {
                return Division::find($id);
            });



        }

        return  $getdivisions = cache()->remember('getdivisions', 60*60*24, function () {
            return Division::all();
        });
    }

    public function getdistrict(Request $r)
    {
         $ownid =  $r->ownid;
        $id =  $r->id;
        if($ownid){

            return  cache()->remember('ownid-'.$ownid, 60*60*24, function () use($ownid) {
                return District::find($ownid);
            });

        }
        if($id){

            return  cache()->remember('division_id-'.$id, 60*60*24, function () use($id) {
                return District::where(['division_id'=>$id])->get();
            });


        }
        return  cache()->remember('getdistricts', 60*60*24, function () {
            return District::all();
        });



    }

    public function getthana(Request $r)
    {
        $id =  $r->id;

        if($id){
            return  cache()->remember('getthana-'.$id, 60*60*24, function () use($id) {
                return  Thana::where('district_id',$id)->get();
            });

        }
        return  cache()->remember('Thanas', 60*60*24, function () {
            return  Thana::all();
        });

    }


    public function getunioun(Request $r)
    {
        $id =  $r->id;
        if($id){
            return Union::where('thana_id',$id)->get();
        }
        return Union::all();
    }

    public function gotoUnion(Request $r)
    {
        $name =  $r->input('id');
if($name=='Banglabandha'){
    echo 'http://www.banglabanda.localhost:8000/';

}else if($name=='Bhojoanpur'){
    echo 'http://www.bhojoanpur.localhost:8000/';
}else if($name=='Buraburi'){
    echo 'http://www.buraburi.localhost:8000/';
}else if($name=='Debnagar'){
    echo 'http://www.debnagar.localhost:8000/';
}else if($name=='Salbahan'){
    echo 'http://www.salbahan.localhost:8000/';
}else if($name=='Tentulia'){
    echo 'http://www.tetulia.localhost:8000/';
}else if($name=='Timaihat'){
    echo 'http://www.tirnaihat.localhost:8000/';
}else{
    echo 0;
}

    }
}
