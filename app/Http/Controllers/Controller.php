<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function email_partial_hide($email)
    {
      $em = explode("@",$email);
      $name = $em[0];
      $len = strlen($name);
      $showLen = floor($len/2);
      $str_arr = str_split($name);
      for($ii=$showLen;$ii<$len;$ii++){
          $str_arr[$ii] = '*';
      }
      $em[0] = implode('',$str_arr);
      $email = implode('@',$em);
      return $email;
    }
}
