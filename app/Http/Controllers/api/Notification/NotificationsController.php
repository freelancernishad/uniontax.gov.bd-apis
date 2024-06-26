<?php

namespace App\Http\Controllers\api\Notification;

use App\Http\Controllers\Controller;

use App\Models\Notifications;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{







    function getOS() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $os_platform  = "Unknown OS Platform";

        $os_array     = array(
                              '/windows nt 10/i'      =>  'Windows 10',
                              '/windows nt 6.3/i'     =>  'Windows 8.1',
                              '/windows nt 6.2/i'     =>  'Windows 8',
                              '/windows nt 6.1/i'     =>  'Windows 7',
                              '/windows nt 6.0/i'     =>  'Windows Vista',
                              '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                              '/windows nt 5.1/i'     =>  'Windows XP',
                              '/windows xp/i'         =>  'Windows XP',
                              '/windows nt 5.0/i'     =>  'Windows 2000',
                              '/windows me/i'         =>  'Windows ME',
                              '/win98/i'              =>  'Windows 98',
                              '/win95/i'              =>  'Windows 95',
                              '/win16/i'              =>  'Windows 3.11',
                              '/macintosh|mac os x/i' =>  'Mac OS X',
                              '/mac_powerpc/i'        =>  'Mac OS 9',
                              '/linux/i'              =>  'Linux',
                              '/ubuntu/i'             =>  'Ubuntu',
                              '/iphone/i'             =>  'iPhone',
                              '/ipod/i'               =>  'iPod',
                              '/ipad/i'               =>  'iPad',
                              '/android/i'            =>  'Android',
                              '/blackberry/i'         =>  'BlackBerry',
                              '/webos/i'              =>  'Mobile'
                        );

        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;

        return $os_platform;
    }

    function getBrowser() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $browser        = "Unknown Browser";

        $browser_array = array(
                                '/msie/i'      => 'Internet Explorer',
                                '/firefox/i'   => 'Firefox',
                                '/safari/i'    => 'Safari',
                                '/chrome/i'    => 'Chrome',
                                '/edge/i'      => 'Edge',
                                '/opera/i'     => 'Opera',
                                '/netscape/i'  => 'Netscape',
                                '/maxthon/i'   => 'Maxthon',
                                '/konqueror/i' => 'Konqueror',
                                '/mobile/i'    => 'Handheld Browser'
                         );

        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;

        return $browser;
    }

    function getDeviceName() {

        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        return explode(";",explode(")",explode("(",$user_agent)[1])[0])[2];
    }




        function details(){


       //return $user_agent = $_SERVER['HTTP_USER_AGENT'];



    return $user_os        = $this->getDeviceName();
    $user_os        = $this->getOS();
     $user_browser   = $this->getBrowser();

    $device_details = "<strong>Browser: </strong>".$user_browser."<br /><strong>Operating System: </strong>".$user_os."";

    print_r($device_details);

    echo("<br /><br /><br />".$_SERVER['HTTP_USER_AGENT']."");

        }











        public function notifications(Request $request){




            $url = url()->current();
            $domain =  explode('//',$url);

             $subdomain =  explode('.', $domain[1]);

               if($subdomain[0]=='www'){

                    $subdomainCount =  count($subdomain);
                    $subdomainget = $subdomain[1];

               }else{


                   $subdomainCount =  count($subdomain);
                   $subdomainget = $subdomain[0];

               }




// return $request->all();




    $devicename        = $this->getDeviceName();
    $operatingsystem        = $this->getOS();
     $browser   = $this->getBrowser();




            $data=array('union'=>$subdomainget,'roles'=>$request->roles,"key"=>$request->key,"browser"=>$browser,"operatingsystem"=>$operatingsystem,"devicename"=>$devicename);

           $dataCount =  Notifications::where(['union'=>$subdomainget,'roles'=>$request->roles])->count();
           if($dataCount>0){

            echo 0;
           }else{
            Notifications::create($data);
            //   DB::table('notifications')->insert($data);
            echo 1;
           }


        }










    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function show(Notifications $notifications)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function edit(Notifications $notifications)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notifications $notifications)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notifications  $notifications
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notifications $notifications)
    {
        //
    }
}
