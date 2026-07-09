<?php

namespace App\Containers\AdminSection\Voucher\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Voucher\Actions\CreateVoucherAction;
use App\Containers\AdminSection\Voucher\Actions\DetailVoucherAction;
use App\Containers\AdminSection\Voucher\Actions\ListVoucherAction;
use App\Containers\AdminSection\Voucher\Actions\UpdateVoucherAction;
use App\Containers\AdminSection\Voucher\UI\API\Requests\CreateVoucherRequest;
use App\Containers\AdminSection\Voucher\UI\API\Requests\ListVoucherRequest;
use App\Containers\AdminSection\Voucher\UI\API\Requests\UpdateVoucherRequest;
use App\Containers\AdminSection\Voucher\UI\API\Transformers\VoucherTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class VoucherController extends ApiController
{
    public function listVoucher(ListVoucherRequest $request, ListVoucherAction $action)
    {
        $data = $action->run($request);
        return Response::create($data, VoucherTransformer::class);
    }

    public function detail(Request $request, DetailVoucherAction $action)
    {
        $data = $action->run($request->id);
        return Response::create($data, VoucherTransformer::class);
    }

    public function create(CreateVoucherRequest $request, CreateVoucherAction $action)
    {
        $data = $action->run($request->validated());
        return Response::create($data, VoucherTransformer::class);
    }

    public function update(UpdateVoucherRequest $request, UpdateVoucherAction $action)
    {
        $data = $action->run($request->id, $request->validated());
        return Response::create($data, VoucherTransformer::class);
    }

}
