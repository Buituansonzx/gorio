<?php

namespace App\Containers\MobileSection\Profile\Actions;

use App\Containers\MobileSection\Profile\Tasks\FindTripByIDTask;
use App\Ship\Parents\Actions\Action as ParentAction;
use Illuminate\Http\Request;

final class FindTripByIDAction extends ParentAction
{
    public function __construct(private readonly FindTripByIDTask $findTripByIDTask)
    {
    }

    public function run(Request $request)
    {
        return $this->findTripByIDTask->run($request->id);
    }
}
