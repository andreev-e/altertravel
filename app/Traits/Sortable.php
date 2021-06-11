<?php
namespace App\Traits;

use Illuminate\Http\Request;

trait Sortable {

      protected $sorts=[
          ['views.desc', 'Самые популярные'],
          ['id.desc', 'Самые новые'],
          ['id.asc', 'Самые старые'],
      ];

      public function sorting_array(Request $request)
      {
          $sorts=$this->sorts;
          if (isset($request->sort)) {
              [$table,$direction]=explode('.',$request->sort);
          } else {
              [$table,$direction]=explode('.',$this->sorts[0][0]);
          }
          if (!in_array($direction,['asc','desc']) or !in_array($table,['id','views'])) {
              abort(404);
          }
          return [$sorts,$table,$direction];
      }

}
