<?php

namespace GatherUp\Http\Controllers\Api\VersionOne;

use GatherUp\Http\Controllers\Controller;

class DocumentationController extends Controller
{
  public function get() {
    return response()->json(['success' => true]);
  }
}
