<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use GatherUp\Http\Controllers\Controller;

class SignUpController extends Controller
{
  public function store() {
    return response()->json(['success' => true]);
  }

  public function destroy() {
    return response()->json(['success' => true]);
  }
}
