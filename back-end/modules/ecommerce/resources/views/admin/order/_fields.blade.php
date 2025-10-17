@translatableAlert

<ul class="nav nav-tabs scrollable">
    <li class="nav-item">
        <a class="nav-link active save-tab" data-toggle="pill" href="#ecommerce_order_Info">
            {{ __('Tab Info') }}
        </a>
    </li>
</ul>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="ecommerce_order_Info">
        <div class="row">
            <div class="col-12 col-md-12">
                <table class="table table-striped table-bordered dt-responsive nowrap bootstrap4-styling">
                    <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('ecommerce::order-item.product_name') }}</th>
                            <th>{{ __('ecommerce::order-item.quantity') }}</th>
                            <th>{{ __('ecommerce::order-item.price') }}</th>
                            <th>{{ __('ecommerce::order-item.total_price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->orderItems as $key => $orderItem)
                            @php($key++)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ $orderItem->product_name }}</td>
                                <td>{{ $orderItem->quantity }}</td>
                                <td>@vnmoney($orderItem->price) ₫</td>
                                <td>@vnmoney($orderItem->total_price) ₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                @select([
                    'name' => 'status',
                    'allowClear' => false,
                    'label' => __('ecommerce::order.order_status'),
                    'options' => get_order_status_options()
                ])
            </div>
            <div class="col-6 col-md-6">
                @select([
                    'name' => 'payment_status',
                    'allowClear' => false,
                    'label' => __('ecommerce::order.payment_status'),
                    'options' => get_payment_status_options()
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'created_at',
                    'label' => __('ecommerce::order.created_at'),
                    'value' => $item->created_at,
                    'disabled' => true
                ])
            </div>
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'updated_at',
                    'label' => __('ecommerce::order.updated_at'),
                    'value' => $item->updated_at,
                    'disabled' => true
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'discount_code',
                    'label' => __('ecommerce::order.discount_code'),
                    'value' => $item->discount_code,
                    'disabled' => true
                ])
            </div>
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'discount_amount',
                    'label' => __('ecommerce::order.discount_amount'),
                    'value' => format_money($item->discount_amount),
                    'disabled' => true
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'total_price',
                    'label' => __('ecommerce::order.total_price'),
                    'value' => format_money($item->total_price),
                    'disabled' => true
                ])
            </div>
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'email',
                    'label' => __('ecommerce::order.email'),
                    'value' => $item->email,
                    'disabled' => true
                ])
            </div>
            @php($customerInfo = json_decode($item->shipping_address, true))
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'phone',
                    'label' => __('ecommerce::order.phone'),
                    'value' => $customerInfo['phone'],
                    'disabled' => true
                ])
            </div>
            <div class="col-6 col-md-6">
                @input([
                    'name' => 'email',
                    'label' => __('ecommerce::order.fullname'),
                    'value' => $customerInfo['fullname'],
                    'disabled' => true
                ])
            </div>
            <div class="col-12 col-md-12">
                @input([
                    'name' => 'address',
                    'label' => __('ecommerce::order.address'),
                    'value' => $customerInfo['address'],
                    'disabled' => true
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-6 col-md-6">
                @if ($item->note)
                @textarea([
                    'name' => 'note',
                    'label' => __('ecommerce::order.note'),
                    'value' => $item->note,
                    'disabled' => true
                ])
                @endif
            </div>
        </div>
    </div>
</div>
