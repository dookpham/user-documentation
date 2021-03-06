<?hh

namespace Hack\UserDocumentation\Operators\PHV\Examples\Iteration;

function phv_iteration(): void {
  $m = Map {'a' => 1, 'b' => 2, 'c' => 3 };
  // Don't care about the values
  foreach ($m as $key => $_) {
    var_dump($key); // dumps 'a', 'b', 'c'
  }
}

phv_iteration();
